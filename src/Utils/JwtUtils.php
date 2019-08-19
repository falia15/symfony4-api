<?php

namespace App\Utils;

use \Firebase\JWT\JWT;
use App\Entity\User;

class JwtUtils
{

    public function genereToken(User $user) : string
    {
        $data = array(
            "id" => $user->getId(),
            "username" => $user->getUsername(),
            "time" => (new \DateTime())->format('Y-m-d H:i:s'),
        );
        return JWT::encode($data, $_ENV['JWTKEY']);
    }

    public function decodeToken(string $token) : array
    {
        try {
            $result = JWT::decode($token, $_ENV['JWTKEY'], array('HS256'));
        } catch (\Exception $e) {
            return array('error' => 'Invalid token');
        }

        // bind as an array (it an std class by default)
        return (array) $result;
    }


}