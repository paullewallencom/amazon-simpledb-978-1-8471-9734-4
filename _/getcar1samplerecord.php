<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Query all attributes for '112222222' Item</h1>
<a href=index.php>Return to Menu</a><p>

<?php

if (!class_exists('SimpleDB')) require_once('sdb.php');  

$sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

$domain = "songs";  

// create connection
$sdb = new SimpleDB(awsAccessKey, awsSecretKey);  

$item_name = "112222222"; 

$rest = $sdb->getAttributes($domain,$item_name);

if ($rest) {
  echo "getAttributes for $item_name<pre>"; 
  print_r($rest);
  echo "</pre><P>";
  echo("RequestId: ".$sdb->RequestId."<br>");
  echo("NextToken: <pre>".$sdb->NextToken."</pre><br>");
  $getattrboxattr = (float)($sdb->BoxUsage);
  echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
} else {
  echo("Listing FAILED<br>");
  echo("ErrorCode: ".$sdb->ErrorCode."<p>");
}

echo("<p>Versus using:<P>");
$rest = $sdb->select($domain,"select * from $domain WHERE itemName() = '112222222'");
if ($rest) {
  echo "<b>SELECT * FROM SONGS WHERE itemName() = '112222222'</b><pre>"; 
  print_r($rest);
  echo "</pre><P>";
  echo("RequestId: ".$sdb->RequestId."<br>");
  $getattrboxsel = (float)($sdb->BoxUsage);
  echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
  $percentused = $getattrboxsel/$getattrboxattr;
  echo("$percentused times the cost of using getAttribute<br>");
  echo("NextToken: <pre>".$sdb->NextToken."</pre><br>");
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