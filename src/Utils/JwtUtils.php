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


}