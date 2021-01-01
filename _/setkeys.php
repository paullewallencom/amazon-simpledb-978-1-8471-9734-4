<?php require_once('config.inc.php');  ?>
<?php

if (!empty($_POST["key"])) {	// if a value is passed from the key input field save it
	$_SESSION['key'] = $_POST["key"];
}
if (!empty($_POST["secretkey"])) {	// if a value is passed from the secretkey input field save it
	$_SESSION['secretkey'] = $_POST["secretkey"];
}
if (!empty($_POST["s3bucket"])) { 
	$_SESSION['s3bucket'] = $_POST["s3bucket"];
}

?>
<html>
<body>
<h1>Set SimpleDB Keys</h1>
<?php if (!empty($_POST["secretkey"])) { echo('<b>Keys saved </b>'); } ?>
<a href=index.php>Return to Menu</a><p>
<FORM ACTION="setkeys.php" METHOD=post>
Key: <input type=text name="key" size=60 value="<?php echo $_SESSION['key']; ?>">
<br>Secret Key: <input type=password name="secretkey" size=60 >
<br>S3 Bucket: <input type=text name="s3bucket" size=60 value="<?php echo $_SESSION['s3bucket']; ?>"> 
<br>(Must be unique across ALL buckets in S3)
<br>If you do not define a bucket the samples will generate one based on simpledb_book and a random number.

<p><INPUT TYPE=submit VALUE="Set Keys for this session">
<p>The keys are stored in two PHP session variables.

</FORM>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>
