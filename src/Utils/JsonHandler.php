<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;


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
    public function defaultSerialize($data) : string
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * Format as an array a validation entity error, in order for the error to be send with a "error" key 
     */
    public function validatorSerialize($data) : string
    {
        $jsonData = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonData);

        $arraySerialize = json_decode($jsonData, true); // set it true so its an array
        
        if(array_key_exists('detail', $arraySerialize)){
            return $this->defaultSerialize(['error' => $arraySerialize['detail']]);
        }
        return $this->defaultSerialize(['error' => 'Error while performing the request']);
    }

    /**
     * header content of responses
     */
    public function responseJson($response, int $status = 200) : Response
    {
        $response = new Response($response);
        $response->headers->set('Accept', 'application/json');
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        $response->setStatusCode($status);

        return $response;
    }

}