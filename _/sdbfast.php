<?php
/**
*
* Copyright (c) 2010, Rich Helms.
* Copyright (c) 2009, Dan Myers.
* Parts copyright (c) 2008, Donovan Schonknecht.
* Expanded by Rich Helms rich@webmasterinresidence.ca
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
* - Redistributions of source code must retain the above copyright notice,
*   this list of conditions and the following disclaimer.
* - Redistributions in binary form must reproduce the above copyright
*   notice, this list of conditions and the following disclaimer in the
*   documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
* IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
* ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
* LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
* CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
* SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
* CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
* ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* This is a modified BSD license (the third clause has been removed).
* The BSD license may be found here:
* http://www.opensource.org/licenses/bsd-license.php
*
* Amazon SimpleDB is a trademark of Amazon.com, Inc. or its affiliates.
*
* SimpleDB is based on Donovan Schonknecht's Amazon S3 PHP class, found here:
* http://undesigned.org.za/2007/10/22/amazon-s3-php-class
*/

/**
* Amazon SimpleDB PHP Fast class
*
* @link http://sourceforge.net/projects/php-sdb/
* version 0.5.0
*
* 0.5.0 - Base version - RH
* 
* Need to define externally 
* Defined keys in config.inc.php
* 
* 	awsAccessKey - AWS Access Key
* 	awsSecretKey - AWS Secret Access Key
* 
*/
class SimpleDB
{
	private static $__accessKey; // AWS Access key
	private static $__secretKey; // AWS Secret key

	public static $useSSL = true;
	public static $verifyHost = 1;
	public static $verifyPeer = 1;

	public $BoxUsage;	// BoxUsage of last response
	public $RequestId;	// RequestId of last response
	public $NextToken;	// NextToken of last response
	public $ErrorCode;	// ErrorCode of last response

	/**
	* Constructor - if you're not using the class statically
	*
	* @param string $accessKey Access key
	* @param string $secretKey Secret key
	* @param boolean $useSSL Enable SSL
	* @return void
	*/
	public function __construct($accessKey = null, $secretKey = null, $useSSL = true) {
		if ($accessKey !== null && $secretKey !== null)
			self::setAuth($accessKey, $secretKey);
		self::$useSSL = $useSSL;
	}

	/**
	* Set AWS access key and secret key
	*
	* @param string $accessKey Access key
	* @param string $secretKey Secret key
	* @return void
	*/
	public static function setAuth($accessKey, $secretKey) {
		self::$__accessKey = $accessKey;
		self::$__secretKey = $secretKey;
	}

	/**
	* Enable or disable VERIFYHOST for SSL connections
	* Only has an effect if $useSSL is true
	*
	* @param boolean $enable Enable VERIFYHOST
	* @return void
	*/
	public static function enableVerifyHost($enable = true) {
		self::$verifyHost = ($enable ? 1 : 0);
	}

	/**
	* Enable or disable VERIFYPEER for SSL connections
	* Only has an effect if $useSSL is true
	*
	* @param boolean $enable Enable VERIFYPEER
	* @return void
	*/
	public static function enableVerifyPeer($enable = true) {
		self::$verifyPeer = ($enable ? 1 : 0);
	}

	/**
	* Create a domain
	*
	* @param string $domain The domain to create
	* @return boolean
	*/
	public function createDomain($domain) {
		SimpleDB::__clearReturns();
		
		$rest = new SimpleDBRequest($domain, 'CreateDomain', 'POST', self::$__accessKey);
		$rest = $rest->getResponse();
		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			SimpleDB::__triggerError('createDomain', $rest->error);
			return false;
		}

		if(isset($rest->body->ResponseMetadata->RequestId)) {
			$this->RequestId = (string)($rest->body->ResponseMetadata->RequestId);
		}
		if(isset($rest->body->ResponseMetadata->BoxUsage)) {
			$this->BoxUsage = (string)($rest->body->ResponseMetadata->BoxUsage);
		}

		return true;
	}

	/**
	* Get a list of domains
	*
	* @return array | false
	*/
	public function listDomains() {
		SimpleDB::__clearReturns();

		$rest = new SimpleDBRequest('', 'ListDomains', 'GET', self::$__accessKey);
		$rest = $rest->getResponse();
		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			SimpleDB::__triggerError('listDomains', $rest->error);
			return false;
		}

		$results = array();
		if (!isset($rest->body->ListDomainsResult))
		{
			return $results;
		}

		foreach($rest->body->ListDomainsResult->DomainName as $d)
		{
			$results[] = (string)$d;
		}

		if(isset($rest->body->ResponseMetadata->RequestId)) {
			$this->RequestId = (string)($rest->body->ResponseMetadata->RequestId);
		}
		if(isset($rest->body->ResponseMetadata->BoxUsage)) {
			$this->BoxUsage = (string)($rest->body->ResponseMetadata->BoxUsage);
		}

		return $results;
	}

	/**
	* Get a domain's metadata
	*
	* @param string $domain The domain
	* @return array | false
	
		Array returned
		(
		    [ItemCount] => 3
		    [ItemNamesSizeBytes] => 16
		    [AttributeNameCount] => 9
		    [AttributeNamesSizeBytes] => 76
		    [AttributeValueCount] => 13
		    [AttributeValuesSizeBytes] => 65
		    [Timestamp] => 1247238402
		)
		
		Sets: BoxUsage and RequestId

	*/
	public function domainMetadata($domain) {
		SimpleDB::__clearReturns();

		$rest = new SimpleDBRequest($domain, 'DomainMetadata', 'GET', self::$__accessKey);
		$rest = $rest->getResponse();
		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			SimpleDB::__triggerError('domainMetadata', $rest->error);
			return false;
		}

		$results = array();
		if (!isset($rest->body->DomainMetadataResult)) {
			return $results;
		}
		if(isset($rest->body->DomainMetadataResult->ItemCount)) {
			$results['ItemCount'] = (string)($rest->body->DomainMetadataResult->ItemCount);
		}
		if(isset($rest->body->DomainMetadataResult->ItemNamesSizeBytes)) {
			$results['ItemNamesSizeBytes'] = (string)($rest->body->DomainMetadataResult->ItemNamesSizeBytes);
		}
		if(isset($rest->body->DomainMetadataResult->AttributeNameCount)) {
			$results['AttributeNameCount'] = (string)($rest->body->DomainMetadataResult->AttributeNameCount);
		}
		if(isset($rest->body->DomainMetadataResult->AttributeNamesSizeBytes)) {
			$results['AttributeNamesSizeBytes'] = (string)($rest->body->DomainMetadataResult->AttributeNamesSizeBytes);
		}
		if(isset($rest->body->DomainMetadataResult->AttributeValueCount))	{
			$results['AttributeValueCount'] = (string)($rest->body->DomainMetadataResult->AttributeValueCount);
		}
		if(isset($rest->body->DomainMetadataResult->AttributeValuesSizeBytes)) {
			$results['AttributeValuesSizeBytes'] = (string)($rest->body->DomainMetadataResult->AttributeValuesSizeBytes);
		}
		if(isset($rest->body->DomainMetadataResult->Timestamp)) {
			$results['Timestamp'] = (string)($rest->body->DomainMetadataResult->Timestamp);
		}
		if(isset($rest->body->ResponseMetadata->RequestId)) {
			$this->RequestId = (string)($rest->body->ResponseMetadata->RequestId);
		}
		if(isset($rest->body->ResponseMetadata->BoxUsage)) {
			$this->BoxUsage = (string)($rest->body->ResponseMetadata->BoxUsage);
		}

		return $results;
	}
		/**
	* Return XMP SelectResult string
	*
	* @param string  $domain The domain being queried
	* @param string  $select The select expression to evaluate.
	* @param string  $nexttoken The token to start from when retrieving results
	* @return string. If no results found return ''
	*/
	public function backup($domain, $select, $nexttoken = null) {
		SimpleDB::__clearReturns();

		$rest = new SimpleDBRequest($domain, 'Select', 'GET', self::$__accessKey);

		if($select != '') {
			$rest->setParameter('SelectExpression', $select); // keep
		}
		if($nexttoken !== null) {
			$rest->setParameter('NextToken', $nexttoken); // keep
		}

		$rest = $rest->getResponse();
#		echo(str_ireplace("<", "&lt;", $rest->rawXML)."<p>"); // uncomment to see XML

		$results = $rest->rawXML;
		
	  if (stristr($results,'<SelectResult/>')) $results=""; // no results round

		$trailmarkup = '<SelectResult>';
		if (stristr($results,$trailmarkup)) {	// strip off XML before data
	    $results = substr($results, stripos($results, $trailmarkup)+strlen($trailmarkup));
		}
		$trailmarkup = '<NextToken>';
		if (stristr($results,$trailmarkup)) {	// strip off XML after data
	    $results = substr($results, 0, stripos($results, $trailmarkup));
		}
		$trailmarkup = '</SelectResult>';
		if (stristr($results,$trailmarkup)) {	// strip off XML after data
	    $results = substr($results, 0, stripos($results, $trailmarkup));
		}
				
		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			SimpleDB::__triggerError('query', $rest->error);
			return false;
		}


		if(isset($rest->body->ResponseMetadata->RequestId)) {
			$this->RequestId = (string)($rest->body->ResponseMetadata->RequestId);
		}
		if(isset($rest->body->ResponseMetadata->BoxUsage)) {
			$this->BoxUsage = (string)($rest->body->ResponseMetadata->BoxUsage);
		}
		if ($rest->body->SelectResult->NextToken) {
			$this->NextToken = (string)$rest->body->SelectResult->NextToken;
		}

		return $results;
	}


	/**
	* Create or update attributes on multiple item
	* MAX 25 items per write (SimpleDB limit)
	*
	* @param string $domain The domain containing the desired item
	* @param array  $items An array of items of (item_name, attributes => array(attribute_name => array(value [, replace])))
	*	If replace is omitted it defaults to false.
	*	Optionally, attributes may just be a single string value, and replace will default to false.
	* @return boolean
	*/
	public function XMLbatchPutAttributes($domain, $xmlitems) {
		SimpleDB::__clearReturns();

		$xml = simplexml_load_string($xmlitems);

		$counter=0;
		$ii = 0;
		foreach ($xml->SelectResult->children() as $child) {
			if ($ii == 0) {
				unset($rest);
				$rest = new SimpleDBRequest($domain, 'BatchPutAttributes', 'POST', self::$__accessKey);
			}
			$item_name = (string)$child->Name; 
			$rest->setParameter('Item.'.$ii.'.ItemName', $item_name);
			$i = 0;
			foreach ($child->children() as $kid) {
				if ($kid->getName()=="Attribute") {
					$attr = (string)$kid->Name;
					$attrval = (string)$kid->Value;
					$rest->setParameter('Item.'.$ii.'.Attribute.'.$i.'.Name', $attr);
					$rest->setParameter('Item.'.$ii.'.Attribute.'.$i.'.Value', $attrval, false);
					$i++;
				}
			}
			$ii++;
			if ($ii==25) {  // send items 0-24
				$rest = $rest->getResponse();
				$this->BoxUsage += (float)($rest->body->ResponseMetadata->BoxUsage);
				$ii=0;		
			}
			$counter++;
		}
				
		if ($ii>0) {  // send items 0-24
			$rest = $rest->getResponse();
			if ($rest->error === false && $rest->code !== 200)
				$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
			if ($rest->error !== false) {
				SimpleDB::__triggerError('batchPutAttributes', $rest->error);
				return false;
			}
		}
		
		echo("Counter: ".$counter);
		
		if(isset($rest->body->ResponseMetadata->BoxUsage)) {
			$this->BoxUsage += (float)($rest->body->ResponseMetadata->BoxUsage);
		}

		return true;
	}

	
	public function displayUsage($boxusage) {
		return number_format(1000000*(float)$boxusage,1)." muH";
	}

	/**
	* Clear public parameters
	*
	*/
	public function __clearReturns() {
		$this->BoxUsage = null;
		$this->RequestId = null;
		$this->NextToken = null;
		$this->ErrorCode = null;
		return true;
	}

	/**
	* Trigger an error message
	*
	* @internal Used by member functions to output errors
	* @param array $error Array containing error information
	* @return string
	*/
	public function __triggerError($functionname, $error)
	{
		if($error['curl'])
		{
			$this->ErrorCode = $error['code'];
			trigger_error(sprintf("SimpleDB::%s(): %s", $functionname, $error['code']), E_USER_WARNING);
		}
		else
		{
			foreach($error['Errors'] as $e)
			{
				$message = sprintf("SimpleDB::%s(): %s: %s\n", $functionname, $e['Code'], $e['Message']);
				$this->ErrorCode =  $e['Code'];
				trigger_error($message, E_USER_WARNING);
			}
		}
	}

	/**
	* Generate the auth string using Hmac-SHA256
	*
	* @internal Used by SimpleDBRequest::getResponse()
	* @param string $string String to sign
	* @return string
	*/
	public static function __getSignature($string) {
		return base64_encode(hash_hmac('sha256', $string, self::$__secretKey, true));
	}
}

final class SimpleDBRequest
{
	private $sdbhost, $verb, $resource = '', $parameters = array();
	public $response;

	/**
	* Constructor
	*
	* @param string $domain Domain name
	* @param string $action SimpleDB action
	* @param string $verb HTTP verb
	* @param string $accesskey AWS Access Key
	* @param string $host The URL of the SimpleDB host
	* @return mixed
	*/
	function __construct($domain, $action, $verb, $accesskey, $host = 'sdb.amazonaws.com') {
		if($domain != '')
		{
			$this->parameters['DomainName'] = $domain; // keep
		}

		$this->parameters['Action'] = $action; // keep
		$this->parameters['Version'] = '2009-04-15'; // keep
		$this->parameters['SignatureVersion'] = '2'; // keep
		$this->parameters['SignatureMethod'] = 'HmacSHA256'; // keep
		$this->parameters['AWSAccessKeyId'] = $accesskey; // keep

		$this->verb = $verb;
		$this->sdbhost = $host;
		$this->response = new STDClass;
		$this->response->error = false;
	}

	/**
	* Set request parameter
	*
	* @param string  $key Key
	* @param string  $value Value
	* @param boolean $replace Whether to replace the key if it already exists (default true)
	* @return void
	*/
	public function setParameter($key, $value, $replace = true) {
		if(!$replace && isset($this->parameters[$key]))
		{
			$temp = (array)($this->parameters[$key]);
			$temp[] = $value;
			$this->parameters[$key] = $temp;
		}
		else
		{
			$this->parameters[$key] = $value;
		}
	}

	/**
	* Get the response
	*
	* @return object | false
	*/
	public function getResponse() {

		$this->parameters['Timestamp'] = gmdate('c'); // keep

		$params = array();
		foreach ($this->parameters as $var => $value)
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$params[] = $var.'='.rawurlencode($v);
				}
			}
			else
			{
				$params[] = $var.'='.rawurlencode($value);
			}
		}

		sort($params, SORT_STRING);

		$query = implode('&', $params);

		$strtosign = $this->verb."\n".$this->sdbhost."\n/\n".$query;
		$query .= '&Signature='.rawurlencode(SimpleDB::__getSignature($strtosign)); // keep
		if (debugResponse) echo("<p>".str_ireplace("&","<br>",$query)."<p>");

		$ssl = (SimpleDB::$useSSL && extension_loaded('openssl'));
		$url = ($ssl ? 'https://' : 'http://').$this->sdbhost.'/?'.$query;

		// Basic setup
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_USERAGENT, 'SimpleDB/php');

		if(SimpleDB::$useSSL)
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, SimpleDB::$verifyHost);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, SimpleDB::$verifyPeer);
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($curl, CURLOPT_WRITEFUNCTION, array(&$this, '__responseWriteCallback'));
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		// Request types
		switch ($this->verb) {
			case 'GET': break;  // keep
			case 'PUT': case 'POST':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->verb);
			break;
			case 'HEAD':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'HEAD');
				curl_setopt($curl, CURLOPT_NOBODY, true);
			break;
			case 'DELETE':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
			break;
			default: break;
		}

		// Execute, grab errors
		if (curl_exec($curl)) {
			$this->response->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		} else {
			$this->response->error = array(
				'curl' => true,
				'code' => curl_errno($curl),
				'message' => curl_error($curl),
				'resource' => $this->resource
			);
			$this->response->rawXML = "";
		}

		@curl_close($curl);

		// Parse body into XML
		if ($this->response->error === false && isset($this->response->body)) {
			$this->response->rawXML = (string)$this->response->body; // save XML response

//echo("RAW<BR>");
//echo(str_ireplace("<", "&lt;", $this->response->rawXML)."<p>"); // uncomment to see XML
			
			// The rawXML could be used for caching.
			$this->response->body = simplexml_load_string($this->response->body);

			// Grab SimpleDB errors
			if (!in_array($this->response->code, array(200, 204))
				&& isset($this->response->body->Errors)) {
				$this->response->error = array('curl' => false, 'Errors' => array());
				foreach($this->response->body->Errors->Error as $e)
				{
					$err = array('Code' => (string)($e->Code),
								 'Message' => (string)($e->Message),
								 'BoxUsage' => (string)($e->BoxUsage)
								 );
					$this->response->error['Errors'][] = $err;
				}
				unset($this->response->body);
			}
		}

		return $this->response;
	}

	/**
	* CURL write callback
	*
	* @param resource &$curl CURL resource
	* @param string &$data Data
	* @return integer
	*/
	private function __responseWriteCallback(&$curl, &$data) {
		$this->response->body .= $data;
		return strlen($data);
	}
}