<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Encode/Decode Boolean</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$val1 = true;
	$val2 = false;

	$enval1 = SimpleDB::encodeBoolean($val1);
	$enval2 = SimpleDB::encodeBoolean($val2);

	$bkval1 = SimpleDB::decodeBoolean($enval1);
	$bkval2 = SimpleDB::decodeBoolean($enval2);

	echo("$val1 to $enval1 back $bkval1<br>");
	echo("$val2 to $enval2 back $bkval2<p>");

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>