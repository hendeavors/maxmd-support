<?php

namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\Domains;

final class PatientRegistrationSoapClient extends SoapClient
{
    private static $instance = null;

    final private static function instance()
    {
        return static::$instance;
    }

    public static function getInstance()
    {
        if(null == static::instance()) {
            static::$instance = new PatientRegistrationSoapClient(Domains::soap() . "/registration/services/PatientRegistrationService?wsdl", array('trace' => 1));
        }

        return static::instance();
    }
}
