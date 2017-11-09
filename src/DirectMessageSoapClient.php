<?php

namespace Endeavors\MaxMD\Support;

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
            static::$instance = new DirectMessageSoapClient("https://evalapi.max.md:8445/message/services/DirectMessageService?wsdl", array('trace' => 1));
        }

        return static::instance();
    }    
}
