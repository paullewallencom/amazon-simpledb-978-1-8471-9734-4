<?php require_once('config.inc.php');  ?>
<html>
<body>
<h1>Uploader MP3 Song to S3</h1>
<a href=index.php>Return to Menu</a><p>

<?php
  
  if (!class_exists('S3')) require_once 'S3.php';
  
  // AWS access info
  if (!defined('awsSecretKey')) die("Secret Key not defined");
  
  // add types permitted into the string
  $allowedtypes = "|image/gif|image/jpeg|image/pjpeg|image/png|audio/mpeg|text/plain|application/pdf|";
  $fileType = "|".$_FILES["file"]["type"]."|";
  if (!stristr($allowedtypes,$fileType)) {  
    die("Invalid file type: ".$_FILES["file"]["type"]);
  }
  // Set maximum file size that you permit to be uploaded
  if ($_FILES["file"]["size"] > 2000000) {
    die("File too large: ".$_FILES["file"]["size"]);
  }
  
  // upload file to temporary upload area
  if ($_FILES["file"]["error"] > 0) {
    die("Error: " . $_FILES["file"]["error"]);
  } else   {
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Stored in: " . $_FILES["file"]["tmp_name"]."<p>";
  }
  
  $itemName = $_POST["itemname"];
  
  $uploadFile = $_FILES["file"]["tmp_name"]; // Temporary file name
  $uploadName = $itemName.".".$_FILES["file"]["name"]; // S3 file name
  
  if (!empty($_POST["s3bucket"])) { // fetch bucket name
    $bucketName = $_POST["s3bucket"];
  } else {
    $bucketName = awsS3Bucket;
  }
  
  // Check if our upload file exists
  if (!file_exists($uploadFile) || !is_file($uploadFile))
    exit("\nERROR: No such file: $uploadFile\n\n");
  
  // Check for CURL
  if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
    exit("\nERROR: CURL extension not loaded\n\n");
  
  // Instantiate the class
  $s3 = new S3(awsAccessKey, awsSecretKey);
  
  // List your buckets: check if passed bucket needs to be created
  echo "S3::listBuckets(): <br>\n";
  $bucketList = $s3->listBuckets();
  $bucketfound = false;
  foreach ($bucketList as $bucketListName) {
    if ($bucketListName == $bucketName)  {
      echo("&nbsp;&nbsp;&nbsp;".$bucketListName." FOUND<br>\n");
      $bucketfound = true;
    } else {
      echo("&nbsp;&nbsp;&nbsp;".$bucketListName."<br>\n");
    }
  }
  echo("<br>\n");
  if (!$bucketfound) { // if bucket not found try creating it
    // Create a bucket with public read access
    if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
      echo "Created bucket {$bucketName}".PHP_EOL."<p>\n";
      $bucketfound = true;
    } else {
      echo "S3::putBucket(): Unable to create bucket '{$bucketName}' (it may be owned by someone else)\n";
    }
  }

  if ($bucketfound) { // if bucket exists upload file
    // Put our file (also with public read access)
    if ($s3->putObjectFile($uploadFile, $bucketName, $uploadName, S3::ACL_PUBLIC_READ)) {
      echo "S3::putObjectFile(): File copied to {$bucketName}/".$uploadName."<p>\n";
      
      // Get object info
      $info = $s3->getObjectInfo($bucketName, $uploadName);
      $httpaddr = "http://".$bucketName.".s3.amazonaws.com/".$uploadName;
      echo("Link to download: <a href='$httpaddr'>$httpaddr</a><br>");
      echo "S3::getObjecInfo(): Info for {$bucketName}/".$uploadName."<br>\n";
      foreach ($info as $fattribute => $fvalue) {  // split up attributes
        echo("&nbsp;&nbsp;&nbsp;".$fattribute."=".$fvalue."<br>");
      }
      
      if (!class_exists('SimpleDB')) require_once('sdb.php');  
      $sdb = new SimpleDB(awsAccessKey, awsSecretKey); // create connection

      echo "<br>Update SimpleDB item $itemName<br>"; 
      $putAttributesRequest["FileName"] = array("value" => $uploadName, "replace" => "true"); // replace File name
      $putAttributesRequest["FileKey"] = array("value" => $httpaddr, "replace" => "true"); // Address of the file
    
      $domain = "songs";
      $rest = $sdb->putAttributes($domain,$itemName,$putAttributesRequest);
      if ($rest) {
        echo("Record $itemName updated");
        echo("RequestId: ".$sdb->RequestId."<br>");
        echo("BoxUsage: ".$sdb->BoxUsage." = " . SimpleDB::displayUsage($sdb->BoxUsage)."<br>");
      } else {
        echo("Record $itemName FAILED<br>");
        echo("ErrorCode: ".$sdb->ErrorCode."<p>");
      }
  
    } else {
      echo "S3::putObjectFile(): Failed to copy file\n";
    }
  }
?>
<br>
<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

</body>
</html>