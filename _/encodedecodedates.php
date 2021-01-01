<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Encode/Decode Dates</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	$thetime = Time();  // get current time/date

	echo("Timecode: $thetime<br>");

	$now = SimpleDB::encodeDateTime($thetime);
	echo("ISO8601 format: $now using encodeDateTime()<br>");

	$dt = SimpleDB::decodeDateTime($now);

	echo("Timecode: $dt using decodeDateTime()<p>");
?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>