<?php

namespace App\Utils;

use \Firebase\JWT\JWT;
use App\Entity\User;

class JwtUtils
{

    public function genereToken(User $user) : string
    {
        $data = array(
            "user_id" => $user->getId(),
            "username" => $user->getUsername(),
            "time" => (new \DateTime())->format('Y-m-d H:i:s'),
        );
        return JWT::encode($data, $_ENV['JWTKEY']);
    }

    public function decodeToken(string $token) : array
    {
        $validToken = $this->parseBearer($token);

        if($validToken == null){
            return array('error' => 'No bearer token');
        }

        try {
            $result = JWT::decode($validToken, $_ENV['JWTKEY'], array('HS256'));
        } catch (\Exception $e) {
            return array('error' => 'Invalid token');
        }

        // bind as an array (it an std class by default)
        return (array) $result;
    }

    private function parseBearer(string $token) : ?string
    {
        $bearerIndex = strpos($token, 'Bearer');

        if(strpos($token, 'Bearer') === false){
            return null;
        }

        // token without the word Bearer (6 is bearer word length) and extra space
        return trim(substr($token, 6));
    }

}