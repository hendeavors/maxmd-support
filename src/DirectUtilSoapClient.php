<?php

namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\Domains;

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
            static::$instance = new DirectUtilSoapClient(Domains::soap() . "/registration/services/DirectUtilsService?wsdl", array('trace' => 1));
        }

        return static::instance();
    }
}
