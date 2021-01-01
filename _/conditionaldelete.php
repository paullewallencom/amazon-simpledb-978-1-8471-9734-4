<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Conditional Delete</h1>
<a href=index.php>Return to Menu</a><p>

<?php

if (!class_exists('SimpleDB')) require_once('sdb.php');  

$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection

$domain = "car-s";
$item_name = "car3"; 

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

echo "<b>deleteAttributes item $item_name IF make = Acura (will fail)</b><pre>"; 
$putExists["make"] = array("value" => "Acura"); // check if make = Acura
$rest=$sdb->deleteAttributes($domain,$item_name,null,$putExists); // delete whole Item
if ($rest) {
	echo("Item $item_name updated<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
} else {
	echo("Item $item_name FAILED<br>");
	echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}


unset($putExists);
echo "<b>deleteAttributes item $item_name IF make = Lexus (will succeed)</b><pre>"; 
$putExists["make"] = array("value" => "Lexus"); // check if make = Acura
$rest=$sdb->deleteAttributes($domain,$item_name,null,$putExists); // delete whole Item
if ($rest) {
	echo("Item $item_name updated<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
} else {
	echo("Item $item_name FAILED<br>");
	echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}


?>
<p>Use menu item 7. Create Multiple Items with batchPutAttributes in car-s to refill domain.
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>