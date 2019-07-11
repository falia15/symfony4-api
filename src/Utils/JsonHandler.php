<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * This class handler all default Json response
 */
class JsonHandler {

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Default serialiser
     */
    public function responseJson($data, int $status = 200) : Response
    {
        $data = $this->serializer->serialize($data, 'json');
        return $this->headerData($data, $status);
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

    /**
     * header content of responses
     */
    protected function headerData($response, int $status)
    {
        $response = new Response($response);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, DELETE, PUT');
        $response->headers->set('Access-Control-Allow-Headers', "content-type, access-control-allow-origin, access-control-allow-credentials, access-control-allow-headers, access-control-allow-methods, Authorization");
        $response->setStatusCode($status);

        return $response;
    }

}