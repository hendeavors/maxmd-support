<?php

namespace Endeavors\MaxMD\Support;

/**
 * This seems to work with the maxmd response, no guarantees on
 * This working with other mtom soap servers
 */
class SoapClient extends \SoapClient
{
    public function __doRequest($request, $location, $action, $version, $one_way = 0) 
    {
		$response = parent::__doRequest($request, $location, $action, $version, $one_way);
		
		// if resposnse content type is mtom strip away everything but the xml.
		if (strpos($response, "Content-Type: application/xop+xml") !== false) {
			
			$endheaderposition = strpos($response,">")+1;
			
			$response = substr($response,$endheaderposition, strlen($response));
			
			$startuuidposition = strpos($response, "--");
			
			$response = substr($response,0, $startuuidposition);
		}
		
		$response = $this->sanitize($response);
		
		return $response;
	}
	
	private function sanitize($str, $valid = '')
	{
		$validChars = '12345678901234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz@.</>:-\"?= ' . $valid;
		
		$fname = $str;
		
		$clean = '';
		
		for ($i=0;$i<mb_strlen($fname);++$i) {
			
			$c = mb_substr($fname, $i, 1);
			
			if(mb_strpos($validChars, $c)===false) {
				$clean.='';	
			}
			else {
				$clean.=$c;
			}
		}
		
		if($clean=='') {	
			$clean = '';	
		}
		
		return $clean;
	}
}

