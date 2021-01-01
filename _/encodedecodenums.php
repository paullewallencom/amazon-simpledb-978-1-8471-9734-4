<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Encode/Decode Numbers</h1>
<a href=index.php>Return to Menu</a><p>

<?php

	if (!class_exists('SimpleDB')) require_once('sdb.php');  

	echo("<b>Basic Conversion</b> (Use defined config)<br>");
	echo("<table border=1>");
	echo("<tr><th>Original</th><th>Encoded</th><th>Decoded</th></tr>");

	$testvals = array(27, 2.287, 12584.5963, -5, -5.875, -100000);

	foreach ($testvals as $t) {
		$e = SimpleDB::encodeNum($t);
		$d = SimpleDB::decodeNum($e);
		echo("<tr><td>$t</td><td>$e</td><td>$d</td></tr>");
	}

	echo("</table>");

	echo("<br><b>Advanced Conversion</b> (Override defined config)<br>");
	echo('$sdb->encodeNum(27, 15, 4, 10000000000)<br>');
	echo("27= ".SimpleDB::encodeNum(27, 15, 4, 10000000000)."<p>");

# Function encodeNum($input, $numLen = awsNumLength, $numDec = awsNumDecimals, $numOffset = awsNumNegOffset)
# awsNumLength - Total Number of digits to store a number as
# awsNumDecimals - Number of decimal places
# awsNumNegOffset - Negative number offset to add (abs value of minimum negative number)

?>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>