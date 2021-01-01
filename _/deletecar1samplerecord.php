<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Delete 'car1' item from 'car-s'</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

	$domain = "car-s";	
	
	$item_name = "car1"; 

	echo "deleteAttributes<pre>"; 
	print_r($sdb->deleteAttributes($domain,$item_name)); // delete whole Item
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