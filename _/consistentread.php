<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>ConsistentRead = True</h1>
<a href=index.php>Return to Menu</a><p>

<?php

if (!class_exists('SimpleDB')) require_once('sdb.php');  

$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection

// If the name of the domain or attribute contains characters other than 
// only letters, numbers, underscores (_), or dollar symbols ($), 
// you must escape the name with a backtick character (`). 
$domain = "testread";

$domainmd = $sdb->domainMetadata($domain);
echo("Domain $domain Metadata requested<br>");
echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");

if (!$domainmd) {
	if($sdb->createDomain($domain)) {
		echo("Domain $domain created<br>");
		echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<p>");
	}
}

// build array of items and attribute/value pairs
$putAttributesRequest = array();
  
$item_name = "testitem"; 
echo "putAttributes() Item 'testitem'<br>"; 
$thetime = Time();  // get current time/date
$now = SimpleDB::encodeDateTime($thetime);
echo("ISO8601 format: $now &lt;-- NEW TIME<br>");

$putAttributesRequest["datetime"] = array("value" => $now, "replace" => "true"); // store date time replace = true

$rest = $sdb->putAttributes($domain,$item_name,$putAttributesRequest);
if ($rest) {
	echo("Item $item_name created<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
} else {
	echo("Item $item_name FAILED<br>");
	echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}

$rest = $sdb->getAttributes($domain,$item_name,null,true);
if ($rest) {
  echo "<br>getAttributes for $item_name ConsistentRead=True<br>"; 
  $datetime = $rest["datetime"];
  if ($now == $datetime) {
	  echo "DateTime: $datetime &lt;-- NEW TIME<br>";
	} else {
	  echo "DateTime: $datetime <b>&lt;-- OLD TIME </b><br>";
	}
  echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
} else {
  echo("Listing FAILED<br>");
  echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}

$rest = $sdb->getAttributes($domain,$item_name);
if ($rest) {
  echo "<br>getAttributes for $item_name<br>"; 
  $now = $rest["datetime"];
  if ($thetime == $datetime) {
	  echo "DateTime: $datetime &lt;-- NEW TIME<br>";
	} else {
	  echo "DateTime: $datetime <b>&lt;-- OLD TIME </b><br>";
	}
  echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
} else {
  echo("Listing FAILED<br>");
  echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}

?>
<p>
Reload/Refresh this screen several times to see eventual consistency in action as well as the new consistent=true read.
<p>
Even though the getAttributes without consistency was read after 
the getAttributes with consistency, the second will usually be 
the old value as eventual consistency has usually now caught up this quickly.


<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>