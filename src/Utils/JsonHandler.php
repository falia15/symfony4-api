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

    /**
     * Format as an array a validation entity error, in order for the error to be send with a "error" key 
     */
    public function responseValidator($data) : array
    {
        $jsonData = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonData);

        $arraySerialize = json_decode($jsonData, true); // set it true so its an array
        
        if(array_key_exists('detail', $arraySerialize)){
            return ['error' => $arraySerialize['detail']];
        }
        return ['error' => 'Error while performing the request'];
    }

}