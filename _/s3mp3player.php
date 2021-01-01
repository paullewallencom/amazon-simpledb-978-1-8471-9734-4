<?php require_once('config.inc.php');  ?>
<html>
<head>
<script type="text/javascript" src="audio-player/audio-player.js"></script>  
<script type="text/javascript">  
   AudioPlayer.setup("audio-player/player.swf", {  
      width: 290  
   });  
</script>  
</head>
<body>
<h1>S3 MP3 Song Player</h1>
<a href=index.php>Return to Menu</a><p>

<form action="s3mp3player.php" method="post" enctype="multipart/form-data">
Select Item Name/Song to play<br>

<?php 
  if (!class_exists('SimpleDB')) require_once('sdb.php');  

  $sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

  $domain = "songs";
  $sql = "SELECT itemName, Song, FileKey from $domain WHERE FileKey IS NOT NULL";
  $rest = $sdb->select($domain,$sql);
  foreach ($rest as $item) {
    $item_name = $item["Name"];
    $song = $item["Attributes"]["Song"];
    $httpaddr = $item["Attributes"]["FileKey"];
    echo("<input type='radio' name='songaddr' value='".$httpaddr."'> ");
    echo($item_name." / ".$song);
    echo("<br>");
  }
  
  if (!empty($_POST["songaddr"])) { // fetch bucket name
?>
   <p id="audioplayer_1">Alternative content</p>  
   <script type="text/javascript">  
     AudioPlayer.embed("audioplayer_1", {soundFile: "<?php echo($_POST["songaddr"]) ?>"});  
   </script>  
  
<?php } ?>

<p><input type="submit" name="submit" value="Select Song to Play">
</form>

<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>