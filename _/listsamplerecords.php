<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>List Sample Items in car-s</h1>
<a href=index.php>Return to Menu</a><p>

If the name of the domain or attribute contains characters other than only letters, numbers, underscores (_), or dollar symbols ($), you must escape the name with a backtick character (`). This makes using characters like the dash (-) more complex. When a domain or attribute name has a dash you create the domain and the attributes without the backtick. 
<p>The backtick is required for the SELECT operation ONLY. The PHP samples use the domain name car-s to illustrate this. 
<p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

	// IN SELECT ONLY
	// If the name of the domain or attribute contains characters other than 
	// only letters, numbers, underscores (_), or dollar symbols ($), 
	// you must escape the name with a backtick character (`). 
	$domain = "car-s";
	
	$rest = $sdb->select($domain,"select * from `$domain`"); // Show all
	if ($rest) {
		echo "<b>select (all)</b><pre>"; 
		print_r($rest);
		echo "</pre><P>";
		echo("RequestId: ".$sdb->RequestId."<br>");
		echo("BoxUsage: ".$sdb->BoxUsage."<br>");
		echo("NextToken: ".$sdb->NextToken."<br>");
	} else {
		echo("Listing FAILED<br>");
		echo("ErrorCode: ".$sdb->ErrorCode."<p>");
	}

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>