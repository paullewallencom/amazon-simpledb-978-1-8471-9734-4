<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Conditional Put</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection

	$domain = "car-s";
	$item_name = "car1"; 
	
	$rest = $sdb->getAttributes($domain,$item_name);

	if ($rest) {
	  echo "<b>getAttributes for $item_name</b><pre>"; 
	  print_r($rest);
	  echo "</pre>";
	  echo("RequestId: ".$sdb->RequestId."<br>");
	  echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
	} else {
	  echo("Listing FAILED<br>");
	  echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}
	
	$item_name = "car1"; 
	echo "<b>putAttributes() item car1 IF make is Acura (will succeed)</b><br>"; 
	$putAttributesRequest["style"] = array("value" => "new model"); // put style as new model
	$putExists["make"] = array("value" => "Acura"); // check if make = Acura

	$rest = $sdb->putAttributes($domain,$item_name,$putAttributesRequest, $putExists);
	if ($rest) {
		echo("Item $item_name updated<br>");
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
	} else {
		echo("Item $item_name FAILED<br>");
		echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}

	unset($putExists);
	$item_name = "car1"; 
	echo "<b>putAttributes() item car1 IF length does not exist (will succeed)</b><br>"; 
	$putAttributesRequest["height"] = array("value" => "new height"); // put height
	$putExists["length"] = array("exists" => "false"); // check if attribute length does NOT exist

	$rest = $sdb->putAttributes($domain,$item_name,$putAttributesRequest, $putExists);
	if ($rest) {
		echo("Item $item_name updated<br>");
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
	} else {
		echo("Item $item_name FAILED<br>");
		echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}
	
	unset($putExists);
	$item_name = "car1"; 
	echo "<b>putAttributes() item car1 IF make is Honda (will fail)</b><br>"; 
	$putAttributesRequest["style"] = array("value" => "new model"); // put style as new model
	$putExists["make"] = array("value" => "Honda"); // check if make = Acura

	$rest = $sdb->putAttributes($domain,$item_name,$putAttributesRequest, $putExists);
	if ($rest) {
		echo("Item $item_name updated<br>");
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
	} else {
		echo("Item $item_name FAILED<br>");
		echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}

	unset($putExists);
	$item_name = "car1"; 
	echo "<b>putAttributes() item car1 IF make does not exist (will fail)</b><br>"; 
	$putAttributesRequest["height"] = array("value" => "new height"); // put height
	$putExists["make"] = array("exists" => "false"); // check if attribute make does NOT exist

	$rest = $sdb->putAttributes($domain,$item_name,$putAttributesRequest, $putExists);
	if ($rest) {
		echo("Item $item_name updated<br>");
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
	} else {
		echo("Item $item_name FAILED<br>");
		echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}


?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>