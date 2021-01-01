<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Fast Restore a Domain</h1>
<a href=index.php>Return to Menu</a><p>
<?php

	if (!empty($_POST["domain"])) {	// if a value is passed from the key input field save it
		$domain = $_POST["domain"];
	} else {
  $domain = "songs";
	}
	$domain = stripslashes($domain);  // remove PHP escaping 
	
	if (!empty($_POST["s3bucket"])) { // fetch bucket name
		$bucketName = $_POST["s3bucket"];
	} else {
		$bucketName = awsS3Bucket;
	}

	$restFileName = $_POST["restFileName"];

  if (!class_exists('SimpleDB')) require_once('sdbfast.php');  
	if (!class_exists('S3')) require_once 'S3.php';
	
?>

This is a basic SimpleDB restore. It quickly restores a SimpleDB based on a backup stored in S3.<p>
<FORM ACTION="fastrestoredomain.php" METHOD=post>
<input type=text name="domain" size=60 value="<?php echo $domain; ?>" /> Domain<br>
<input type=text name="s3bucket" size=60 value="<?php echo $bucketName; ?>" /> Bucket Name<br>

<?php

	// AWS access info
	if (!defined('awsSecretKey')) die("Secret Key not defined");

	$s3 = new S3(awsAccessKey, awsSecretKey);
	$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection

  if ($restFileName) {
  	echo("Restoring: ".$restFileName)."<p>";
  	
		$domainmd = $sdb->domainMetadata($domain);
		echo("Domain $domain Metadata requested<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");

		if (!$domainmd) {
			if($sdb->createDomain($domain)) {
				echo("Domain $domain created<br>");
				echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
			}
		}

  	$rest = $s3->getObject($bucketName, $restFileName);

		$starttime = time();
		echo("Start Time: $starttime <br>");
		if ($sdb->XMLbatchPutAttributes($domain, $rest->body)) {
			echo("$counter Items created<br>");
			echo("RequestId: ".$sdb->RequestId."<br>");
			echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
		} else {
			echo("Items FAILED<br>");
			echo("ErrorCode: ".$sdb->ErrorCode."<p>");
		}
		$endtime = time();
		echo("End Time: $endtime <br>");
		echo("Time Used: ".($endtime-$starttime)." seconds <br>");


  } else {

		// Get the contents of our bucket
		$bucketContents = $s3->getBucket($bucketName);
		echo "S3::getBucket(): List all files in bucket {$bucketName}:<br>\n";
	  foreach ($bucketContents as $attribute => $value) {  // split up attributes
	  	echo("<input type='radio' name='restFileName' value='".$attribute."'> ".$attribute."<br>");
		}
		echo("Select file to restore");
	}

?>

<br><INPUT TYPE=submit VALUE="Restore Selected Domain">
</FORM>

<p>


<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>