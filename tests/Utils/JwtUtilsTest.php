<?php

namespace App\Utils\JwtUtilsTest;

use App\Utils\JwtUtils;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

class JwtUtilsTest extends TestCase
{

    public function testGenereToken()
    {
        $jwtUtils = new JwtUtils();

        $user = new User();
        $user->setUsername('Bob');
        $user->setPassword('12345');
        $user->setEmail('bob@gmail.com');
        $token = $jwtUtils->genereToken($user);

        $tokens = explode(".", $token);

        $this->assertEquals(count($tokens), 3);
    }

}