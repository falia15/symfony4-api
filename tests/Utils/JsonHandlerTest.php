<?php

namespace App\Tests\Utils;

use App\Utils\JsonHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class JsonHandlerTest extends TestCase 
{
    private $jsonHandler;

    protected function setUp()
    {
        $jsonHandler = new JsonHandler($this->getSearializerMock());
        $this->jsonHandler = $jsonHandler;
    }

    public function testResponseJsonType() 
    {
        $jsonResponse = $this->jsonHandler->responseJson(['error' => 'this is an error']);
        $this->assertInstanceOf(Response::class, $jsonResponse);
    }

    public function testResponseJsonStatusCode()
    {
        $jsonResponseError = $this->jsonHandler->responseJson(['error' => 'do not throw trash on the floor'], 400);
        $jsonResponseSuccess = $this->jsonHandler->responseJson(['sucees' => 'it works !']);

        $this->assertEquals(400, $jsonResponseError->getStatusCode());
        $this->assertEquals(200, $jsonResponseSuccess->getStatusCode());
    }

    protected function getSearializerMock()
    {
        $serialiserMock = $this
        ->getMockBuilder(SerializerInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

        return $serialiserMock; 
    }

}