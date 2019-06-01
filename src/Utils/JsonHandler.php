<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;


class JsonHandler {

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function responseJson($data, int $status = 200) : Response
    {
        $jsonData = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($status);

        return $response;
    }


}