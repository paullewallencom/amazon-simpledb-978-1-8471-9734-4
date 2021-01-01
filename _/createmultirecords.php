<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Create Multiple Items with batchPutAttributes in car-s</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection

	$domain = "car-s";

	// notes from page 60 or SimpleDB Developer Guide
	// Because Amazon SimpleDB makes multiple copies of your data and uses an eventual
	// consistency update model, performing a GetAttributes (p. 66) or Select (p. 74) request
	// (read) immediately after a DeleteAttributes (p. 60) or PutAttributes (p. 71) request (write)
	// might not return the updated data.
	
	$putAttributesRequest = array();

	$item_name = "car1"; 
	$putAttributesRequest["make"] = array("value" => "Acura"); // Single
	$putAttributesRequest["color"] = array("value" => array("Black","Red")); // Multiple
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);

	$item_name = "car2"; 
	unset($putAttributesRequest);  // clear array
	$putAttributesRequest["make"] = array("value" => "BMW");
	$putAttributesRequest["year"] = array("value" => "2008");
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);

	$item_name = "car3"; 
	unset($putAttributesRequest);  // clear array
	$putAttributesRequest["make"] = array("value" => "Lexus"); // Single
	$putAttributesRequest["color"] = array("value" => array("Blue","Red")); // Multiple
	$putAttributesRequest["year"] = array("value" => "2008");
	$putAttributesRequest["desc"] = array("value" => "Sedan", "replace" => "true"); // Replace
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);

	echo "batchPutAttributes()<br>"; 

	if ($sdb->batchPutAttributes($domain,$bulkAttr)) {
		echo("Items created<br>");
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
	} else {
		echo("Items FAILED<br>");
		echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}

?>

<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>