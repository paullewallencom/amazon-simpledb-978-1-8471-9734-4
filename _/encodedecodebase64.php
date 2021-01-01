<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Encode/Decode Base64</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$val = "abcdefghijklmnopqrstuvwxyz'\/*@#$%^&()";

	$enval = SimpleDB::encodeBase64($val);

	$bkval = SimpleDB::decodeBase64($enval);

	echo("Input: $val<p>Base64: $enval<p>Decoded: $bkval<p>");

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>