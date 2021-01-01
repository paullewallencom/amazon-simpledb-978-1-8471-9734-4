<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Prep Songs</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	// create connection
	$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  

	$domain = "songs";	

	echo "Update Songs without a FileKey<br>"; 
	$rest = $sdb->select($domain,"select itemName,Song from $domain where FileKey IS NULL");

  if ($rest) {
 		$putAttributesRequest = array();

    foreach ($rest as $item) {
			echo "Item: ".$item["Name"]."<br>";
			$item_name = $item["Name"];
			$song = $item["Attributes"]["Song"];
			$putAttributesRequest["FileKey"] = array("value" => md5($song.$item_name));
			$putAttributesRequest["FileName"] = array("value" => ($song.".mp3"));
			$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
    }
		echo "batchPutAttributes()<br>"; 
	
		// batch put the appributes
		if ($sdb->batchPutAttributes($domain,$bulkAttr)) {
			echo("Items created<br>");
			echo("RequestId: ".$sdb->RequestId."<br>");
			echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
		} else {
			echo("Items FAILED<br>");
			echo("ErrorCode: ".$sdb->ErrorCode."<p>");
		}
  }
	echo "<P>";
	echo("RequestId: ".$sdb->RequestId."<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>