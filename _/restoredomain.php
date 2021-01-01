<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Restore a Domain</h1>
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

  if (!class_exists('SimpleDB')) require_once('sdb.php');  
	if (!class_exists('S3')) require_once 'S3.php';
	
?>

This is a basic SimpleDB restore. It restores a SimpleDB based on a backup stored in S3.<p>
<FORM ACTION="restoredomain.php" METHOD=post>
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
		$xml = simplexml_load_string($rest->body);

		$putAttributesRequest = array();
		
		$counter=0;
		$totalusage = 0;
		foreach ($xml->SelectResult->children() as $child) {
			$item_name = (string)$child->Name; 
			foreach ($child->children() as $kid) {
				if ($kid->getName()=="Attribute") {
					$attr = (string)$kid->Name;
					$attrval = (string)$kid->Value;
		  		if ($putAttributesRequest[$attr])	{
		  			$temp = $putAttributesRequest[$attr]["value"];
						$temp[] = $attrval;
						$putAttributesRequest[$attr] = array("value" => $temp);
					} else {
						$putAttributesRequest[$attr] = array("value" => array($attrval));
					}
				}
			}
			$counter++;
			$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
			unset($putAttributesRequest);
			
			if ($counter==24) {
				if ($sdb->batchPutAttributes($domain,$bulkAttr)) {
					echo("$counter Items created<br>");
					echo("RequestId: ".$sdb->RequestId."<br>");
					echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
					$totalusage += (float)$sdb->BoxUsage;
				} else {
					echo("Items FAILED<br>");
					echo("ErrorCode: ".$sdb->ErrorCode."<p>");
				}
				$counter=0;
				unset($bulkAttr);
			}
	  }
		
		if ($counter>0) {
			if ($sdb->batchPutAttributes($domain,$bulkAttr)) {
				echo("$counter Items created<br>");
				echo("RequestId: ".$sdb->RequestId."<br>");
				echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
				$totalusage += (float)$sdb->BoxUsage;
			} else {
				echo("Items FAILED<br>");
				echo("ErrorCode: ".$sdb->ErrorCode."<p>");
			}
		}
		$endtime = time();
		echo("End Time: $endtime <br>");
		echo("Time Used: ".($endtime-$starttime)." seconds <br>");
		echo("Total Usage: ".SimpleDB::displayUsage($totalusage)." <br>");

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