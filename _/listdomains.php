<?php require_once('config.inc.php');  ?>
<html>
<body>
<H1>List domains and Domain Metadata</h1>
<a href=index.php>Return to Menu</a><p><hr>
<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection
	
	// List the domains available
	// $sdb-><b>listDomains() returns list of domains

	$domainList = $sdb->listDomains();
	if ($domainList) {
		echo("listDomains()<br>");
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
	} else {
		echo("Listing FAILED<br>");
		echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}


	if ($domainList) {
		foreach ($domainList as $domainName) {
			echo "Domain: <b>$domainName</b><br>";
			echo 'Domain Metadata<pre>';
			// fetch the domain meta data 
			// see Amazon SimpleDB Developer Guide - page 70 
			$rest = $sdb->domainMetadata($domainName); // returns an array with names
			echo("ItemCount: ".$rest["ItemCount"]."\n");
			echo("ItemNamesSizeBytes: ".$rest["ItemNamesSizeBytes"]."\n");
			echo("AttributeNameCount: ".$rest["AttributeNameCount"]."\n");
			echo("AttributeNamesSizeBytes: ".$rest["AttributeNamesSizeBytes"]."\n");
			echo("AttributeValueCount: ".$rest["AttributeValueCount"]."\n");
			echo("AttributeValuesSizeBytes: ".$rest["AttributeValuesSizeBytes"]."\n");
			echo("Timestamp: ".$rest["Timestamp"]." ". date("M j,Y g:iA",$rest["Timestamp"]) . "\n");
			echo("RequestId: ".$sdb->RequestId."\n");
			echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");

			echo "</pre><hr>"; 
		}
	}

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>