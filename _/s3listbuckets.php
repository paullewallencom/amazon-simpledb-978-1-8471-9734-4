<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>S3 List Buckets</h1>
<a href=index.php>Return to Menu</a><p>

<?php
	
	if (!class_exists('S3')) require_once 'S3.php';
	
	// AWS access info
	if (!defined('awsSecretKey')) die("Secret Key not defined");
	
	// Instantiate the class
	$s3 = new S3(awsAccessKey, awsSecretKey);
	
	// List your buckets: check if passed bucket needs to be created
	echo "S3::listBuckets(): <br>\n";
	$bucketList = $s3->listBuckets();
	$bucketfound = false;
	foreach ($bucketList as $bucketListName) {
		echo("&nbsp;&nbsp;&nbsp;".$bucketListName."<br>\n");
		$bucketCont = $s3->getBucket($bucketListName);
		foreach ($bucketCont as $bucketContName) {
			foreach ($bucketContName as $attribute => $value) {  // split up attributes
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$attribute = $value<br>";
			}
		}
		echo("<br>\n");
	}
	
?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>