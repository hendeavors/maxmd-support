<?php

namespace Endeavors\MaxMD\Support\Tests;

use Endeavors\MaxMD\Support\Client;

class DirectMessageClientTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testResponseFromGetMessagesCall()
    {
        $request = [
            "auth" => [
                "username" => "freddie@healthendeavors.direct.eval.md",
                "password" => "smith"
            ],
            "folderName" => "Inbox"
        ];
        
        $this->response = Client::DirectMessage()->GetMessages($request);

        $this->assertTrue(is_object($this->response));
    }

    public function testCreatingFolderCall()
    {
        $request = [
            "auth" => [
                "username" => "freddie@healthendeavors.direct.eval.md",
                "password" => "smith"
            ],
            "folderName" => "Inbox.NewFolder"
        ];
        
        $this->response = Client::DirectMessage()->CreateFolder($request);

        $this->assertTrue(is_object($this->response));
    }
}