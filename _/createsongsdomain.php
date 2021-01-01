<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Create Songs Domain with Sample Items</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  	// create connection

	// If the name of the domain or attribute contains characters other than 
	// only letters, numbers, underscores (_), or dollar symbols ($), 
	// you must escape the name with a backtick character (`). 
	$domain = "songs";

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
    
	$item_name = "112222222";
	$putAttributesRequest["Song"] = array("value" => "My Way");
	$putAttributesRequest["Artist"] = array("value" => "Frank Sinatra");
	$putAttributesRequest["Year"] = array("value" => "2002");
	$putAttributesRequest["Genre"] = array("value" => "Easy Listening");
	$putAttributesRequest["Rating"] = array("value" => array("****", "4 stars", "Excellent"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "089997979";
	$putAttributesRequest["Song"] = array("value" => "Hotel California");
	$putAttributesRequest["Artist"] = array("value" => "Gipsy Kings");
	$putAttributesRequest["Genre"] = array("value" => "World");
	$putAttributesRequest["Rating"] = array("value" => "****");
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
  
	$item_name = "982411114";
	$putAttributesRequest["Song"] = array("value" => "Geraldine");
	$putAttributesRequest["Artist"] = array("value" => "Glasvegas");
	$putAttributesRequest["Year"] = array("value" => "2008");
	$putAttributesRequest["Genre"] = array("value" => array("Rock", "Alternative"));
	$putAttributesRequest["Rating"] = array("value" => "****");
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "6767969119";
	$putAttributesRequest["Song"] = array("value" => "Transmission");
	$putAttributesRequest["Artist"] = array("value" => "Joy Division");
	$putAttributesRequest["Year"] = array("value" => "1981");
	$putAttributesRequest["Genre"] = array("value" => "Alternative");
	$putAttributesRequest["Rating"] = array("value" => array("*****", "Excellent"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "6721309888";
	$putAttributesRequest["Song"] = array("value" => "Guzarish");
	$putAttributesRequest["Artist"] = array("value" => "Ghazini");
	$putAttributesRequest["Year"] = array("value" => "2008");
	$putAttributesRequest["Genre"] = array("value" => "Bollywood");
	$putAttributesRequest["Rating"] = array("value" => array("Not rated", "Awful"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "0923424244";
	$putAttributesRequest["Song"] = array("value" => "So What");
	$putAttributesRequest["Artist"] = array("value" => "Miles Davis");
	$putAttributesRequest["Year"] = array("value" => "1959");
	$putAttributesRequest["Genre"] = array("value" => "Jazz");
	$putAttributesRequest["Rating"] = array("value" => array("*****", "Wow!"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "5697878778";
	$putAttributesRequest["Song"] = array("value" => "Allison");
	$putAttributesRequest["Artist"] = array("value" => "Pixies");
	$putAttributesRequest["Year"] = array("value" => "1990");
	$putAttributesRequest["Genre"] = array("value" => "Alternative");
	$putAttributesRequest["Rating"] = array("value" => array("****", "4 stars"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "7233929292";
	$putAttributesRequest["Song"] = array("value" => "Pride");
	$putAttributesRequest["Artist"] = array("value" => "Syntax");
	$putAttributesRequest["Genre"] = array("value" => array("Electronic", "Alternative"));
	$putAttributesRequest["Rating"] = array("value" => array("*****", "Excellent"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "5656600009";
	$putAttributesRequest["Song"] = array("value" => "Acapulco");
	$putAttributesRequest["Artist"] = array("value" => "Neil Diamond");
	$putAttributesRequest["Year"] = array("value" => "1980");
	$putAttributesRequest["Genre"] = array("value" => "Soundtrack");
	$putAttributesRequest["Rating"] = array("value" => array("*", "1 star", "Avoid"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);
    
	$item_name = "1002380899";
	$putAttributesRequest["Song"] = array("value" => "Scream in Blue");
	$putAttributesRequest["Artist"] = array("value" => "Midnight Oil");
	$putAttributesRequest["Year"] = array("value" => "1983");
	$putAttributesRequest["Genre"] = array("value" => "Rock");
	$putAttributesRequest["Rating"] = array("value" => array("***", "3 stars"));
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);
	unset($putAttributesRequest);

	$item_name = "1045845425";
	$putAttributesRequest["Song"] = array("value" => "You're a Strange Animal");
	$putAttributesRequest["Artist"] = array("value" => "Gowan");
	$putAttributesRequest["Year"] = array("value" => "1985");
	$putAttributesRequest["Genre"] = array("value" => "Rock");
	$putAttributesRequest["Rating"] = array("value" => "****");
	$bulkAttr[$item_name] = array("name" => "$item_name", "attributes" => $putAttributesRequest);

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


?>

<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>