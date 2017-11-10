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
        $xml_response = null;
        // Catch XML response
        preg_match('/<soap[\s\S]*nvelope>/', $response, $xml_response);
        if (!is_array($xml_response) || !count($xml_response)) {
            throw new \Exception('No XML has been found.');
        }
        $xml_response = reset($xml_response);
        // Look if xop then replace by base64_encode(binary)
        $xop_elements = null;
        preg_match_all('/<xop[\s\S]*?\/>/', $response, $xop_elements);
        $xop_elements = reset($xop_elements);
        if (is_array($xop_elements) && count($xop_elements)) {
            foreach ($xop_elements as $xop_element) {
                // Get CID
                $cid = null;
                preg_match('/cid:([0-9a-zA-Z-]+)@/', $xop_element, $cid);
                $cid = $cid[1];
                // Get Binary
                $binary = null;
                preg_match('/Content-ID:[\s\S].+?'.$cid.'[\s\S].+?>([\s\S]*?)--uuid/', $response, $binary);
                $binary = trim($binary[1]);
                $binary = base64_encode($binary);
                // Replace xop:Include tag by base64_encode(binary)
                // Note: SoapClient will automatically base64_decode(binary)
                $xml_response = preg_replace('/<xop:Include[\s\S]*cid:'.$cid.'@[\s\S]*?\/>/', $binary, $xml_response);
            }
        }
        return $xml_response;
	}
}

