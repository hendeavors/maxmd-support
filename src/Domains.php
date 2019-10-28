<?php

namespace Endeavors\MaxMD\Support;

final class Domains
{
    const DEV_SOAP_DOMAIN = 'https://evalapi.max.md:8445';
    const DEV_REST_DOMAIN = 'https://evalapi.max.md:8445';
    const LIVE_SOAP_DOMAIN = 'https://api.directmdemail.com';
    const LIVE_REST_DOMAIN = 'https://directapi.max.md:8445';

    /**
     * Are we developing?
     * @var bool
     */
    private static $inDevelopment = false;

    /**
     * Should not construct
     */
    private function __construct()
    {
    }

    /**
     * Get the soap domain
     * @return string
     */
    public static function soap(): string
    {
        if(static::$inDevelopment) {
            return self::DEV_SOAP_DOMAIN;
        }

        return self::LIVE_SOAP_DOMAIN;
    }

    /**
     * Get the rest domain
     * @return string
     */
    public static function rest(): string
    {
        if(static::$inDevelopment) {
            return self::DEV_REST_DOMAIN;
        }

        return self::LIVE_REST_DOMAIN;
    }

    /**
     * Get the domain for the provider directory (it's different than the others on dev)
     * @return string
     */
    public static function providerDirectory(): string
    {
        $directory = static::rest();

        if(static::$inDevelopment) {
            return str_replace(':8445', ':8446', $directory);
        }

        return $directory;
    }

    /**
     * Set development mode flag
     * @param bool $mode
     */
    public static function setDevelopmentMode(bool $mode)
    {
        static::$inDevelopment = $mode;
    }
}
