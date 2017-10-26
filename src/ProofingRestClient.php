<?php

namespace Endeavors\MaxMD\Support;

class ProofingRestClient
{
    private static $instance = null;

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
}