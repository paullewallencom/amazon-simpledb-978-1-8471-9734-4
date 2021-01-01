<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Upload MP3 Song to S3</h1>
<a href=index.php>Return to Menu</a><p>

<form action="s3uploader.php" method="post" enctype="multipart/form-data">
1. Select file: <input type="file" name="file" id="file"><br>
2. Select bucket to put the file in.<br>

<?php 
  if (!class_exists('S3')) require_once 'S3.php';
  
  // AWS access info
  if (!defined('awsSecretKey')) die("Secret Key not defined");
  
  // Instantiate the class
  $s3 = new S3(awsAccessKey, awsSecretKey);
  
  // List your buckets: check if passed bucket needs to be created
  $bucketList = $s3->listBuckets();
  foreach ($bucketList as $bucketListName) {
    echo("<input type='radio' name='s3bucket' value='".$bucketListName."'> ".$bucketListName."<br>");
  }
?>
3. Select Item Name/Song<br>

<?php 
  if (!class_exists('SimpleDB')) require_once('sdb.php');  

  $sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

  $domain = "songs";
  $sql = "SELECT itemName, Song, FileKey from $domain";
  $rest = $sdb->select($domain,$sql);
  foreach ($rest as $item) {
    $item_name = $item["Name"];
    $song = $item["Attributes"]["Song"];
    $httpaddr = $item["Attributes"]["FileKey"];
    echo("<input type='radio' name='itemname' value='".$item_name."'> ");
    if (empty($httpaddr)) {
      echo($item_name." / ".$song);
    } else {
      echo("<a href='$httpaddr'>");
      echo($item_name." / ".$song);
      echo("</a>");
    }
    echo("<br>");
  }
?>


<br><input type="submit" name="submit" value="4. Click to upload">
</form>

<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>