<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
    class Authnet_info extends CI_Model {
		
		public $loginname = "2gQuL7t4J2Y";
		public $transactionkey = "6FSL79g69Dh4rDuM";
		public $host = "api.authorize.net";
		public $path = "/xml/v1/request.api";
		
        public function __construct() {
            parent::__construct();
        }
		
		function send_request_via_fsockopen($host,$path,$content)
		{
			$posturl = "ssl://" . $host;
			$header = "Host: $host\r\n";
			$header .= "User-Agent: PHP Script\r\n";
			$header .= "Content-Type: text/xml\r\n";
			$header .= "Content-Length: ".strlen($content)."\r\n";
			$header .= "Connection: close\r\n\r\n";
			$fp = fsockopen($posturl, 443, $errno, $errstr, 30);
			if (!$fp)
			{
				$response = false;
			}
			else
			{
				error_reporting(E_ERROR);
				fputs($fp, "POST $path  HTTP/1.1\r\n");
				fputs($fp, $header.$content);
				fwrite($fp, $out);
				$response = "";
				while (!feof($fp))
				{
					$response = $response . fgets($fp, 128);
				}
				fclose($fp);
				error_reporting(E_ALL ^ E_NOTICE);
			}
			return $response;
		}

		//function to send xml request via curl
		function send_request_via_curl($host,$path,$content)
		{
			$posturl = "https://" . $host . $path;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $posturl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			return $response;
		}


		//function to parse Authorize.net response
		function parse_return($content)
		{
			$refId = $this->substring_between($content,'<refId>','</refId>');
			$resultCode = $this->substring_between($content,'<resultCode>','</resultCode>');
			$code = $this->substring_between($content,'<code>','</code>');
			$text = $this->substring_between($content,'<text>','</text>');
			$status = $this->substring_between($content,'<status>','</status>');
			$subscriptionId = $this->substring_between($content,'<subscriptionId>','</subscriptionId>');
			return array ($status, $refId, $resultCode, $code, $text, $subscriptionId);
		}

		//helper function for parsing response
		function substring_between($haystack,$start,$end) 
		{
			if (strpos($haystack,$start) === false || strpos($haystack,$end) === false) 
			{
				return false;
			} 
			else 
			{
				$start_position = strpos($haystack,$start)+strlen($start);
				$end_position = strpos($haystack,$end);
				return substr($haystack,$start_position,$end_position-$start_position);
			}
		}
		
		function getAuthStatus($subscriptionId) {
			//build xml to post
			$content =
					"<?xml version=\"1.0\" encoding=\"utf-8\"?>".
					"<ARBGetSubscriptionStatusRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
						"<merchantAuthentication>".
							"<name>" . $this->loginname . "</name>".
							"<transactionKey>" . $this->transactionkey . "</transactionKey>".
						"</merchantAuthentication>" .
						"<subscriptionId>" . $subscriptionId . "</subscriptionId>".
					"</ARBGetSubscriptionStatusRequest>";
									
					
			//send the xml via curl
			$response = $this->send_request_via_curl($this->host,$this->path,$content);
			//if curl is unavilable you can try using fsockopen
			/*
			$response = send_request_via_fsockopen($host,$path,$content);
			*/

			//if the connection and send worked $response holds the return from Authorize.net
			if ($response)
			{
				/*
				a number of xml functions exist to parse xml results, but they may or may not be available on your system
				please explore using SimpleXML in php 5 or xml parsing functions using the expat library
				in php 4
				parse_return is a function that shows how you can parse though the xml return if these other options are not avilable to you
				*/
				list ($status, $resultCode, $code, $text, $subscriptionId) = $this->parse_return($response);

				$info['resultCode']= $resultCode;
				$info['code']= $code;
				$info['status']= $status;
				$info['resultText']= $text;
				$info['subscriptionId']= $subscriptionId;

		
			}
			else
			{
				$info['failed'] = "Transaction Failed";
			}	
	
			return $info;
		}
		

		
		
	}