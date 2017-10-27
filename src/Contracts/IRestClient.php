<?php

namespace Endeavors\MaxMD\Support\Contracts;

interface IRestClient
{
    /**
     * Make a post to the specified resource
     */
    function Post($endpoint, $params, $headers = array());

    /**
     * Make a get to the specified resource
     */
    function Get($endpoint, $params, $headers = array());
}