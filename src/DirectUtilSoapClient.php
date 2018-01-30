<?php

// https://evalapi.max.md:8445/registration/services/DirectUtilsService?wsdl

namespace Endeavors\MaxMD\Support;

final class DirectUtilSoapClient extends SoapClient
{
    private static $instance = null;

    final private static function instance()
    {
        return static::$instance;
    }

    public static function getInstance()
    {
        if(null == static::instance()) {
            static::$instance = new DirectUtilSoapClient("https://evalapi.max.md:8445/registration/services/DirectUtilsService?wsdl", array('trace' => 1));
        }

        return static::instance();
    }
}
