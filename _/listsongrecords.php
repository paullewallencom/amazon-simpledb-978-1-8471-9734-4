<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Try SQL Queries</h1>
<a href=index.php>Return to Menu</a><p>
<?php
  $domain = "songs";

	if (!empty($_POST["sql"])) {	// if a value is passed from the key input field save it
		$sql = $_POST["sql"];
	} else {
		$sql = "select * from ". $domain;
	}
	$sql = stripslashes($sql);  // remove PHP escaping 

	if (!empty($_POST["domain"])) {	// if a value is passed from the key input field save it
		$domain = $_POST["domain"];
	} else {
  $domain = "songs";
	}
	$domain = stripslashes($domain);  // remove PHP escaping 
	
?>

<FORM ACTION="listsongrecords.php" METHOD=post>
<textarea name="sql" cols=60><?php echo($sql); ?></textarea><br>
<input type=text name="domain" size=60 value="<?php echo $domain; ?>"><INPUT TYPE=submit VALUE="Run SQL">
<p>
Selecting a sample SQL statement from below will insert it into the box above. You may exit the sample to create your own SQL then press the 'Run SQL' button.
<p>
<SELECT ONCHANGE="this.form.sql.value = this.options[this.selectedIndex].value;">
<OPTION></option>
<OPTION>SELECT * FROM songs</option>
<OPTION>SELECT * FROM songs WHERE Song = 'My Way'</option>
<OPTION>SELECT * FROM songs WHERE Year > '2000'</option>
<OPTION>SELECT * FROM songs WHERE Rating LIKE '***%'</option>
<OPTION>SELECT * FROM songs WHERE Rating LIKE '%stars'</option>
<OPTION>SELECT * FROM songs WHERE Genre NOT LIKE 'Jazz%'</option>
<OPTION>SELECT * FROM songs WHERE Year BETWEEN '1980' AND '2000'</option>
<OPTION>SELECT * FROM songs WHERE Year >= '1980' AND Year <=  '2000'</option>

<OPTION>SELECT * FROM songs WHERE Year in('1980','1990','2008')</option>
<OPTION>SELECT * FROM songs WHERE Year='1980' OR Year='1990' OR Year='2008'</option>

<OPTION>SELECT * FROM songs WHERE Year IS NULL</option>
<OPTION>SELECT * FROM songs WHERE every(Rating) = '****'</option>
<OPTION>SELECT * FROM songs WHERE Rating = '****'</option>
<OPTION>SELECT * FROM songs WHERE Year > '1980' INTERSECTION Genre = 'Rock'</option>
<OPTION>SELECT * FROM songs WHERE NOT Year < '1980' INTERSECTION NOT Genre = 'Rock'</option>
<OPTION>SELECT * FROM songs WHERE Year > '1980' OR Rating = '****'</option>
<OPTION>SELECT * FROM songs WHERE Year < '1981' ORDER BY Year ASC</option>
<OPTION>SELECT * FROM songs WHERE Year < '1981' ORDER BY Year DESC</option>
<OPTION>SELECT * FROM songs WHERE Year < '1981' ORDER BY Year</option>
<OPTION>SELECT * FROM songs WHERE Year < '2000' INTERSECTION Artist IS NOT NULL  ORDER BY Artist DESC</option>
<OPTION>SELECT * FROM songs WHERE itemName() IS NOT NULL ORDER BY itemName() DESC</option>
<OPTION>SELECT count(*) FROM songs WHERE Year < '2000'</option>
<OPTION>SELECT count(*) FROM songs LIMIT 3</option>
<OPTION>SELECT * FROM songs WHERE Rating = '****' OR Rating = '4 stars'</option>
<OPTION>SELECT * FROM songs WHERE Genre = 'Rock'</option>
<OPTION>SELECT * FROM songs WHERE Song = 'You''re a Strange Animal'</option>
</SELECT> 
</FORM>

<p>
<?php

  if (!class_exists('SimpleDB')) require_once('sdb.php');  

  $sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

  $rest = $sdb->select($domain,$sql);

	echo(SimpleDB::dumpResults($rest));
  if ($rest) {
    echo("RequestId: ".$sdb->RequestId."<br>");
	  echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
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