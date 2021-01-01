<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Fast Backup a Domain</h1>
<a href=index.php>Return to Menu</a><p>
<?php

	if (!empty($_POST["sql"])) {	// if a value is passed from the key input field save it
		$sql = $_POST["sql"];
	} else {
		$sql = "";
	}
	$sql = stripslashes($sql);  // remove PHP escaping 

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
	
?>

This is a basic Fast SimpleDB backup. It downloads the XML of the SQL command 
that you enter and uploads it to S3 into the bucket you specify.<br>
To back up a complete domain use<br>
select * from domain_name
<FORM ACTION="fastbackupdomain.php" METHOD=post>
<input type=text name="sql" size=60 value="<?php echo $sql; ?>" /> SQL<br>
<input type=text name="domain" size=60 value="<?php echo $domain; ?>" /> Domain<br>
<input type=text name="s3bucket" size=60 value="<?php echo $bucketName; ?>" /> Bucket Name<br>
<input type="checkbox" name="DisplayData" value="Yes" /> Display Backup Data

<br><INPUT TYPE=submit VALUE="Backup Domain">
</FORM>

<p>
<?php

	// AWS access info
	if (!defined('awsSecretKey')) die("Secret Key not defined");

  if (!class_exists('SimpleDB')) require_once('sdbfast.php');  
	if (!class_exists('S3')) require_once 'S3.php';

	if (!empty($sql)) {
	
		$s3 = new S3(awsAccessKey, awsSecretKey);
		
		// List your buckets: check if passed bucket needs to be created
		echo "S3::listBuckets(): <br>\n";
		$bucketList = $s3->listBuckets();
		$bucketfound = false;
		foreach ($bucketList as $bucketListName) {
			if ($bucketListName == $bucketName)	{
				echo("&nbsp;&nbsp;&nbsp;".$bucketListName." FOUND<br>\n");
				$bucketfound = true;
				break;
			}
		}
		echo("<br>\n");
		if (!$bucketfound) { // if bucket not found try creating it
			// Create a bucket with public read access
			if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
				echo "Created bucket {$bucketName}".PHP_EOL."<p>\n";
				$bucketfound = true;
			} else {
				echo "S3::putBucket(): Unable to create bucket '{$bucketName}' (it may be owned by someone else)\n";
			}
		}

		if ($bucketfound) { // if bucket exists upload file

			// download SimpleDB backup XML
		  $sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection
		
#			$result="<Domain>".$domain."</Domain>\n";
			$result="<?xml version=\"1.0\"?>\n<SelectResponse><SelectResult>";
		
			$i = 0;
			$prevNextToken = "";
			$boxusage = (float)0;
			$usagelist = array();
			do {
			  $rest = $sdb->backup($domain,$sql,$prevNextToken);
				$result .= $rest;
				$prevNextToken = $sdb->NextToken;
				$boxusage += (float)$sdb->BoxUsage;
				$usagelist[] = $sdb->BoxUsage;
				$i++;
				if ($i>20) { break; }
			} while (!empty($prevNextToken));	
			
			echo("Total BoxUsage $boxusage = ".SimpleDB::displayUsage($boxusage)."<br>");
			foreach ($usagelist as $indtime) {
				echo("&nbsp;&nbsp;&nbsp;".SimpleDB::displayUsage($indtime)."<br>");
			}
			echo("<br>");

			$result .= "</SelectResult></SelectResponse>";

			if (!empty($result)) {
				$uploadName = $domain . "_backup" . date("_Y_m_d_H_i_s", Time()) . ".simdb";
				// Put our file (also with public read access)
				if ($s3->putObjectString($result, $bucketName, $uploadName, S3::ACL_PUBLIC_READ)) {
					echo "S3::putObjectString(): Copy backup to {$bucketName}/".$uploadName."<p>\n";
			
					// Get object info
					$info = $s3->getObjectInfo($bucketName, $uploadName);
					echo("Link to download: <a href='http://{$bucketName}.s3.amazonaws.com/$uploadName'>http://{$bucketName}.s3.amazonaws.com/$uploadName</a><br>");
					echo "S3::getObjecInfo(): Info for {$bucketName}/".$uploadName."<br>\n";
		      foreach ($info as $fattribute => $fvalue) {  // split up attributes
						echo("&nbsp;&nbsp;&nbsp;".$fattribute."=".$fvalue."<br>");
					}
			
				} else {
					echo "S3::putObjectFile(): Failed to copy file\n";
				}
			} else {
				echo("No backup data to upload");
			}
		}

		if ($_POST["DisplayData"]=="Yes") {
			echo("<p><b>Backup Data</b><br>");
			echo(str_ireplace("<", "&lt;", $result)."<p>"); // uncomment to see XML
		}
	
	}
?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>