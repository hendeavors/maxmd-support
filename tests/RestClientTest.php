<?php
namespace Endeavors\MaxMD\Support\Tests;

use Orchestra\Testbench\TestCase;
use Endeavors\MaxMD\Support\Tests\Mocks\RestClientMock;

class RestClientTest extends TestCase
{
    public function testDeleteRequest()
    {
        $client = new RestClientMock;
        $result = $client->Delete('/delete');
        $this->assertEquals($client->url() . 'delete', json_decode($result)->url);
    }

    public function testGetRequest()
    {
        $client = new RestClientMock;
        $result = $client->Get('/get');
        $this->assertEquals($client->url() . 'get', json_decode($result)->url);
    }

    public function testPostRequest()
    {
        $client = new RestClientMock;
        $result = $client->Post('/post');
        $this->assertEquals($client->url() . 'post', json_decode($result)->url);
    }
}
