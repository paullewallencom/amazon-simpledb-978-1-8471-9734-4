Sample programs for using Dan Myers' SimpleDB PHP class expanded by Rich Helms.

This is a set of examples of working with the Amazon SimpleDB. 
It is written on top of Don Myers' Amazon SimpleDB PHP class and Donovan Schonknecht's Amazon S3 PHP class.

For details on the samples see 	
Amazon SimpleDB Developer Guide
by Prabhakar Chaganti, Rich Helms 
from Packt Publishing Ltd.
http://www.packtpub.com/amazon-simpledb-database-developer-guide/

Sample programs Copyright (c) 2010, Rich Helms.  All rights reserved.
Rich Helms rich@webmasterinresidence.ca

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

- Redistributions of source code must retain the above copyright notice,
  this list of conditions and the following disclaimer.
- Redistributions in binary form must reproduce the above copyright
  notice, this list of conditions and the following disclaimer in the
  documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
POSSIBILITY OF SUCH DAMAGE.

Amazon S3 and SimpleDB are trademarks of Amazon.com, Inc. or its affiliates.

The PHP SimpleDB Sample programs use:

Amazon SimpleDB PHP Class
http://sourceforge.net/projects/php-sdb/ by heronblademastr - Don Myers
Amazon S3 PHP class
http://undesigned.org.za/2007/10/22/amazon-s3-php-class by Donovan Schonknecht
Flash Audio Player
http://wpaudioplayer.com/standalone by Martin Laine

If the GeSHi - Generic Syntax Highlighter package is installed the source will be formatted when displayed
To get GeSHI go to http://qbnz.com/highlighter/
Download and copy to a folder named geshi in the phpsample folder 

SimpleDB functions

  * createDomain       - create a domain
  * deleteDomain       - delete a domain
  * listDomains        - list domains
  * domainMetadata     - return metadata on a domain
  * select             - select items (records) from a domain (table)
  * getAttributes      - get atrributes for an item
  * putAttributes      - put attributes for an item
  * batchPutAttributes - put attributes for multipls items
  * deleteAttributes   - delete attributes associated with an item

Data normalization functions

  * encodeBoolean      - encode boolean (true/false)
  * decodeBoolean      - decode boolean (true/false)
  * encodeBase64       - encode binary data to base 64
  * decodeBase64       - decode binary data from base 64
  * encodeNum          - encode number
  * decodeNum          - decode number
  * encodeDateTime     - encode date/time
  * decodeDateTime     - decode date/time

General functions

  * dumpResults        - Format dump results from a select

To install, copy to the host. Run index.php



