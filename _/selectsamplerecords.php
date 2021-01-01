<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Select Year='1985' from Songs</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection
	
	// create connection
	$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  

	$domain = "songs";	
	$item_year = "1985"; 

	echo "select for $item_name<pre>"; 
	print_r($sdb->select($domain,"select * from $domain where Year='$item_year'"));
	echo "</pre><P>";
	echo("RequestId: ".$sdb->RequestId."<br>");
	echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>