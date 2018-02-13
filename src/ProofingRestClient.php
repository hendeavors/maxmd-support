<?php

namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\Contracts\IRestClient;

class ProofingRestClient implements IRestClient
{
    private static $instance = null;

    private $url;

    private function __construct($url = null)
    {
        $this->url = $url;
    }

    final private static function instance()
    {
        return static::$instance;
    }

    final public static function getInstance()
    {
        if( null === static::instance() ) {
            static::$instance = new ProofingRestClient("https://directapi.max.md:8445/AutoProofingRESTful/rest/app/");
        }

        return static::instance();
    }

    public function Post($endpoint, $params = [], $headers = array())
    {
        return $this->request($endpoint, "POST", $params, $headers);
    }

    public function Get($endpoint, $params = [], $headers = array())
    {
        return $this->request($endpoint, "POST", $params, $headers);
    }

    protected function request($endpoint, $method = "GET", $params = array(), $headers = array())
    {
        $url = $this->url . $endpoint;
        $_h = curl_init();
        curl_setopt($_h, CURLOPT_URL, $url );
        curl_setopt($_h, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($_h, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($_h, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($_h, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
        curl_setopt($_h, CURLINFO_HEADER_OUT, true);
        if(!((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
            curl_setopt($_h, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($_h, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if("POST" === $method) {
            curl_setopt($_h, CURLOPT_POST, count($params));
            curl_setopt($_h, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $resp = curl_exec($_h);
        curl_close($_h);
        return $resp;
    }
}
