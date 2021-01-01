<?php require_once('config.inc.php');  ?>
<html><title>SimpleDB PHP Samples</title><body><h1>SimpleDB PHP Sample Programs - BETA</h1>
<?php
 	if (!defined('awsSecretKey')) echo("<h1><font color=red>Set yout Secret Key with Option 1</font></h1>");
?>

<ol>
<strong>Key/Secret Key</strong>
<li><a href=setkeys.php>Set your key/secret key for the session</a></li>
<li><a href=destroykeys.php>Destroy your key/secret session keys</a></li>

<p><strong>Domains 'car-s'</strong>
<li><a href=listdomains.php>List Domains and Domain Metadata</a></li>
<li><a href=createsampledomain.php>Create Sample domain 'car-s'</a></li> 
<li><a href=deletesampledomain.php>Delete Sample domain 'car-s'</a></li>

<strong>Add Items 'car-s'</strong>
<li><a href=createsamplerecords.php>Create Multiple Items One at a Time in car-s</a></li>
<li><a href=createmultirecords.php>Create Multiple Items with batchPutAttributes in car-s</a></li>

<strong>Consistency 'car-s'</strong>
<li><a href=consistentread.php>Eventual Consistency - ConsistentRead = True</a></li>
<li><a href=conditionalput.php>Conditional Put</a></li>
<li><a href=conditionaldelete.php>Conditional Delete</a></li>

<strong>List Items 'car-s'</strong>
<li><a href=listsamplerecords.php>List All Sample Items in car-s</a></li>
<li><a href=listsamplerecordsnexttoken.php>List All Sample Items with a NextToken car-s</a></li>

<strong>Delete Items/Attributes 'car-s'</strong>
<li><a href=deletecar1samplerecord.php>Delete 'car1' item from 'car-s'</a></li>
<li><a href=deletecar3samplerecorddesc.php>Delete 'car3' item 'year' and one 'color' from 'car-s'</a></li>

<p><strong>Data Normalization</strong>
<li><a href=encodedecodenums.php>Encode/Decode Numbers</a></li>
<li><a href=encodedecodedates.php>Encode/Decode Dates</a></li>
<li><a href=encodedecodeboolean.php>Encode/Decode Boolean</a></li>
<li><a href=encodedecodebase64.php>Encode/Decode Base64</a></li>

<p><strong>Select 'songs'</strong>
<li><a href=createsongsdomain.php>Create Songs Domain with Sample Items</a></li>
<li><a href=selectsamplerecords.php>Select Year='1985' from Songs</a></li>
<li><a href=listsongrecords.php>Try SQL Queries</a></li>
<li><a href=getcar1samplerecord.php>Query all attributes for '112222222' Item</a></li>
<li><a href=deletesongsdomain.php>Delete domain 'songs'</a></li>

<p><strong>Backup SimpleDB to S3</strong>
<li><a href=backupdomain.php>Backup a Domain to S3</a></li>
<li><a href=restoredomain.php>Restore a Domain from S3</a></li>

<li><a href=fastbackupdomain.php>Fast Backup a Domain to S3</a> (alpha)</li>
<li><a href=fastrestoredomain.php>Fast Restore a Domain from S3</a> (alpha)</li>

<p><strong>S3</strong> 
<li><a href=s3listbuckets.php>List Buckets</a></li>
<li><a href=s3upload.php>Upload MP3 Song to S3</a></li>
<li><a href=s3mp3player.php>S3 MP3 Song Player</a></li>

<p><strong>Cache_Lite</strong> 
<li><a href=cachetest.php>Cache Test</a></li>
<li><a href=selectcachetest.php>Select Cache Test</a></li>

<p><strong>API Code</strong>
<br>Cache_Lite must be installed for these to work - <a target=_blank href="http://pear.php.net/package/Cache_Lite">http://pear.php.net/package/Cache_Lite</a>
<li><a href=displayincludes.php>Display 'sdb.php' and 'config.inc.php'</a></li>
<li><a href=displays3includes.php>Display 'S3.php'</a></li>
</ol>
<a href=phpsamples.zip>Download source files</a>

<p>Rich Helms <a href="mailto:rich@webmasterinresidence.ca">rich@webmasterinresidence.ca</a>
<br><a href=http://webmasterinresidence.ca/simpledb/>http://webmasterinresidence.ca/simpledb/</a>
<br><a href=http://webmasterinresidence.ca/>http://webmasterinresidence.ca/</a> Discussion on the book and samples
<br><i>Amazon SimpleDB Developer Guide</i> by Prabhakar Chaganti, Rich Helms <a target=_blank href="http://www.packtpub.com/amazon-simpledb-database-developer-guide/">Packt Publishing Ltd</a>

<?php printsource(__FILE__); ?>
Copyright &copy; 2010, Rich Helms.  All rights reserved.
<!-- My google analytics tracking. Remove for your site. -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-738924-22");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>