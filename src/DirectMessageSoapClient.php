<?php

namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\Domains;

final class DirectMessageSoapClient extends SoapClient
{
    private static $instance = null;

    private final static function instance()
    {
        return static::$instance;
    }

    public static function getInstance()
    {
        if(null == static::instance()) {
            static::$instance = new DirectMessageSoapClient(Domains::soap() . "/message/services/DirectMessageService?wsdl", array(
                'trace' => 1,
                'cache_wsdl' => WSDL_CACHE_DISK,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
            ));
        }

        return static::instance();
    }
}
