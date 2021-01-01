<?php
  if(file_exists('geshi/geshi.php')) include_once('geshi/geshi.php');
  
  // If the GeSHi - Generic Syntax Highlighter package is installed the source will be formatted when displayed
  // To get GeSHI go to http://qbnz.com/highlighter/
  // Download and copy to a folder named geshi in the phpsample folder

function printsource($filename) {
	$sourcename = substr($filename, strrpos($filename,"/")+1);
  $source = file_get_contents($filename);
  $source = str_ireplace('<?php printsource(__FILE__); ?>', '', $source);
  echo('<table border=1 cellpadding=4><tr><td>');
  echo('<h3>Source - '.$sourcename.'</h3>');
	if (defined('GESHI_VERSION')) {	# Geshi installed
	  $geshi =& new GeSHi($source, 'php');
	  echo $geshi->parse_code(); 
	} else {
		echo('<pre>');
	  $source = str_ireplace('<', '&lt;', $source);
		echo($source);
		echo('</pre>');
	}
	echo('</td></tr></table>');
}