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
            static::$instance = new RegistrationSoapClient("https://evalapi.max.md:8445/registration/services/PatientRegistrationService?wsdl", array('trace' => 1));
        }

        return static::instance();
    }
}
