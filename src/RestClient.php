<?php

namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\Contracts\IRestClient;

abstract class RestClient implements IRestClient
{
    abstract public function url();

    public function Post($endpoint, $params = [], $headers = [])
    {
        return $this->request($endpoint, "POST", $params, $headers);
    }

    public function Get($endpoint, $params = [], $headers = [])
    {
        return $this->request($endpoint, "GET", $params, $headers);
    }

    public function Delete($endpoint, $params = [], $headers = [])
    {
        return $this->request($endpoint, "DELETE", $params, $headers);
    }

    protected function request($endpoint, $method = "GET", $params = [], $headers = [])
    {
        $url = $this->url() . $endpoint;
        $_h = curl_init();
        curl_setopt($_h, CURLOPT_URL, $url);
        curl_setopt($_h, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($_h, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($_h, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($_h, CURLOPT_DNS_CACHE_TIMEOUT, 2);
        curl_setopt($_h, CURLINFO_HEADER_OUT, true);
        if (!((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
            curl_setopt($_h, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($_h, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if ("POST" === $method) {
            curl_setopt($_h, CURLOPT_POST, count($params));
            curl_setopt($_h, CURLOPT_POSTFIELDS, json_encode($params));
        } elseif ("DELETE" === $method) {
            curl_setopt($_h, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($_h, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $resp = curl_exec($_h);
        curl_close($_h);
        return $resp;
    }
}
