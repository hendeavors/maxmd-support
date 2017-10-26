<?php

namespace Endeavors\MaxMD\Support;

class ProofingRestClient
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
            static::$instance = new ProofingRestClient("https://evalapi.max.md:8445/AutoProofingRESTful/rest/app/");
        }

        return static::instance();
    }

    public function Post($endpoint, $params, $headers = array())
    {
        return $this->request($endpoint, "POST", $params, $headers);
    }

    public function Get($endpoint, $params, $headers = array())
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

        if(!((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
            curl_setopt($_h, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($_h, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if("POST" === $method) {
            curl_setopt($_h, CURLOPT_POST, 1);
        }
        if(count($params)) {
            curl_setopt($_h, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $resp = curl_exec($_h);
        curl_close($_h);
        return $resp;
    }
}