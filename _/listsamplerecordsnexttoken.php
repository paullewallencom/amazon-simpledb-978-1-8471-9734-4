<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>List Sample Items with NextToken in car-s</h1>
<a href=index.php>Return to Menu</a><p>

A NextToken is returned when all items can not be returned. In this example, the limit forces two selects to get all items.
<p>
<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection


	// IN SELECT ONLY
	// If the name of the domain or attribute contains characters other than 
	// only letters, numbers, underscores (_), or dollar symbols ($), 
	// you must escape the name with a backtick character (`). 
	$domain = "`car-s`";
	
	echo "<p><b>select limit 1</b><pre>"; 
	print_r($sdb->select($domain,"select * from $domain limit 1")); // cause NextToken
	echo "</pre><P>";
	echo("RequestId: ".$sdb->RequestId."<br>");
	echo("BoxUsage: ".$sdb->BoxUsage."<br>");
	echo("NextToken: ".$sdb->NextToken."<br>");
	
	$NextTok = $sdb->NextToken;
	
	if ($NextTok) {
		echo "<p><b>select with the NextToken from previous select</b><pre>"; 
		print_r($sdb->select($domain,"select * from $domain", $NextTok)); // cause NextToken
		echo "</pre><P>";
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage."<br>");
		echo("NextToken: ".$sdb->NextToken."<p>");
	}

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>