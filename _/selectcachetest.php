<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Select Cache Test</h1>
<a href=index.php>Return to Menu</a><p>

<?php

$domain = "songs";

if (!empty($_POST["sql"])) {  // if a value is passed from the key input field save it
  $sql = $_POST["sql"];
} else {
  $sql = "";
}
$sql = stripslashes($sql);  // remove PHP escaping 

if (!empty($_POST["domain"])) {  // if a value is passed from the key input field save it
  $domain = $_POST["domain"];
} else {
$domain = "songs";
}
$domain = stripslashes($domain);  // remove PHP escaping 

$clearcache = $_POST["clearcache"];
if ($clearcache=="Yes") {  
  $cc = true;
} else {
  $cc = false;
}
  
?>
Enter a SQL statement and domain. First the program will try fetching from the cache.
If the cache fails it does the query and stores the result in the cache. Run the 
query several times to see the effect of the cache. As the response from the SimpleDB API
is an array, it is necessary to serialize before storing and unserialize after fetching from the cache.
<FORM ACTION="selectcachetest.php" METHOD=post>
<textarea name="sql" cols=60><?php echo($sql); ?></textarea> SQL<br>
<input type=text name="domain" size=40 value="<?php echo $domain; ?>"> Domain<br>
<input type="checkbox" name="clearcache" value="Yes" /> Clear this value from cache first<br>
<INPUT TYPE=submit VALUE="Run SQL">
</FORM>
<p>

<?php

// Uses Cache_Lite - http://pear.php.net/package/Cache_Lite
// Include the package
require_once('Cache/Lite.php');

// Set a few options
$options = array(
  'cacheDir' => '/tmp/',
  'lifeTime' => 3600
);

if (!class_exists('SimpleDB')) require_once('sdb.php');  
$sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

if (!empty($sql)) {
  
  // Create a Cache_Lite object
  $Cache_Lite = new Cache_Lite($options);

  if ($cc) {
    echo("<p><b>Removing from Cache : </b>" . $sql . "<p>");
    $Cache_Lite->remove($sql, $domain); // clear cache first
  }
  
  // Test if thereis a valide cache for this id
  if ($data = $Cache_Lite->get($sql, $domain)) {
  
    $rest = unserialize($data);
     // Content is in $data
    echo("<p><b>Retrieving from Cache : </b>" . $sql . "<p>");
  
  } else { // No valid cache found

    $rest = $sdb->select($domain,$sql);
    $data = serialize($rest);
  
    // Put in $data datas to put in cache
    $Cache_Lite->save($data, $sql, $domain);
  
    echo("<p><b>Saving in Cache : </b>" . $sql . "<p>");
  
  }

  echo(SimpleDB::dumpResults($rest));
  
  if ($sdb->BoxUsage) {
    echo("RequestId: ".$sdb->RequestId."<br>");
    echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
    echo("NextToken: <pre>".$sdb->NextToken."</pre><br>");
  }
  if ($sdb->ErrorCode) {
    echo("Listing FAILED<br>");
    echo("ErrorCode: ".$sdb->ErrorCode."<p>");
  }
}

?> 
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>