<?php

namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\Contracts\IRestClient;
use Endeavors\MaxMD\Support\Domains;
use Endeavors\MaxMD\Support\RestClient;

class ProofingRestClient extends RestClient implements IRestClient
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
            static::$instance = new ProofingRestClient(Domains::rest() . "/AutoProofingRESTful/rest/app/");
        }

        return static::instance();
    }

    /**
     * The full base url for all proofing endpoints
     * @return string - the url
     */
    public function url()
    {
        return $this->url;
    }

    public function Get($endpoint, $params = [], $headers = [])
    {
        return $this->request($endpoint, "POST", $params, $headers);
    }
}
