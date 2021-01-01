<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Cache Test</h1>
<a href=index.php>Return to Menu</a><p>

<FORM ACTION="cachetest.php" METHOD=post>
<input type="checkbox" name="clearcache" value="Yes" /> Clear this value from cache first<br>
<INPUT TYPE=submit VALUE="Call Cache Test Again">
</FORM>
<p>

<?php

$clearcache = $_POST["clearcache"];
if ($clearcache=="Yes") {  
  $cc = true;
} else {
  $cc = false;
}

// Uses Cache_Lite - http://pear.php.net/package/Cache_Lite
// Include the package
require_once('Cache/Lite.php');

// Set a id for this cache
$id = "select * from songs where itemName() = '112222222'";

// Set a few options
$options = array(
  'cacheDir' => '/tmp/',
  'lifeTime' => 3600
);

// Create a Cache_Lite object
$Cache_Lite = new Cache_Lite($options);

if ($cc) {
  echo("<p><b>Removing from Cache : </b>" . $id . "<p>");
  $Cache_Lite->remove($id, "default"); // clear cache first
}

// Test if thereis a valide cache for this id
if ($data = $Cache_Lite->get($id, "default")) {

  // Content is in $data
  echo("<b>Cache Data : </b>" . $data . "<br>Key : " . $id.  "<p>");

} else { // No valid cache found

  // Put in $data datas to put in cache
  $newdata = "Results from SimpleDB";
  $Cache_Lite->save($newdata, $id, "default");

  echo("<b>Saving in Cache : </b>" . $data. "<br>Key : " . $id . "<p>");

}

?> 
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>