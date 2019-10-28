<?php
namespace Endeavors\MaxMD\Support\Tests\Mocks;

use Endeavors\MaxMD\Support\RestClient;

class RestClientMock extends RestClient
{
    /**
     * Use a fake endpoint so we can test the rest client works.
     * @return string
     */
    public function url()
    {
      return 'https://postman-echo.com/';
    }
}
