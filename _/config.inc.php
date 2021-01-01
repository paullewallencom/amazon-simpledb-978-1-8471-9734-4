<?php
	session_start(); 

	if (!empty($_SESSION['key'])) $key = $_SESSION['key'];
	if (!empty($_SESSION['secretkey'])) $secretkey = $_SESSION['secretkey'];
	if (!empty($_SESSION['s3bucket'])) { 
		$s3bucket = $_SESSION['s3bucket'];
	} else {
		$s3bucket = uniqid("simpledb_book"); // Bucket Name
	}

	// if your own installation, just replace $key and $secretkey with your values
	if (!empty($secretkey)) {
		if (!defined('awsAccessKey')) define('awsAccessKey', $key);  
		if (!defined('awsSecretKey')) define('awsSecretKey', $secretkey);  
		if (!defined('awsS3Bucket')) define('awsS3Bucket', $s3bucket);  
	}
	
	if (!defined('awsNumLength')) define('awsNumLength', 10);	// Total length
	if (!defined('awsNumDecimals')) define('awsNumDecimals', 2);	// Number of decimals
	if (!defined('awsNumNegOffset')) define('awsNumNegOffset', 100000000);	// Negative number offset
	
	if (!defined('debugResponse')) define('debugResponse', false);	// echo getResponse string
	

include_once('sourceprint.php');  
