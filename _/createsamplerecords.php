<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Create Multiple Items One at a Time in car-s</h1>
<a href=index.php>Return to Menu</a><p>

<?php

if (!class_exists('SimpleDB')) require_once('sdb.php');  

$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection

$domain = "car-s";
	
# You can "create" the domain first to avoid NoSuchDomain error on first creation
# If the domain already exists there is no change
#	$sdb->createDomain($domain);  // create just in case it does not exist
#	echo "createDomain() Domain $domain created<p>";
#	echo("RequestId: ".$sdb->RequestId."<br>");
#	echo("BoxUsage: ".$sdb->BoxUsage."<br>");
	
	// notes from page 60 or SimpleDB Developer Guide
	// Because Amazon SimpleDB makes multiple copies of your data and uses an eventual
	// consistency update model, performing a GetAttributes (p. 66) or Select (p. 74) request
	// (read) immediately after a DeleteAttributes (p. 60) or PutAttributes (p. 71) request (write)
	// might not return the updated data.

$item_name = "car1"; 
echo "<p>putAttributes() item $item_name<br>"; 
$putAttributesRequest["make"] = array("value" => "Acura"); // Example add an attribute
$putAttributesRequest["color"] = array("value" => array("Black","Red")); // Add multiple values

$rest = $sdb->putAttributes($domain,$item_name,$putAttributesRequest);
if ($rest) {
	echo("Item $item_name created");
	echo("RequestId: ".$sdb->RequestId."<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
} else {
	echo("Item $item_name FAILED<br>");
	echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}

$item_name = "car2"; 
echo "<p>putAttributes() item $item_name<br>"; 
$putAttributesRequest["make"] = array("value" => "BMW");
$putAttributesRequest["year"] = array("value" => "2008");
unset($putAttributesRequest["color"]);  // no color in car2 so remove entry

if ($sdb->putAttributes($domain,$item_name,$putAttributesRequest)) {
	echo("Item $item_name created");
	echo("RequestId: ".$sdb->RequestId."<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
} else {
	echo("Item $item_name FAILED<br>");
	echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}

$item_name = "car3"; 
echo "<p>putAttributes() item $item_name<br>"; 
$putAttributesRequest["make"] = array("value" => "Lexus"); // Example add an attribute
$putAttributesRequest["color"] = array("value" => array("Blue","Red")); // Add multiple values
$putAttributesRequest["year"] = array("value" => "2008");
// Replace existing values
$putAttributesRequest["desc"] = array("value" => "Sedan", "replace" => "true"); // replace existing value


if ($sdb->putAttributes($domain,$item_name,$putAttributesRequest)) {
	echo("Item $item_name created");
	echo("RequestId: ".$sdb->RequestId."<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
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