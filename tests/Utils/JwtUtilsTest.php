<?php

namespace App\Utils\JwtUtilsTest;

use App\Utils\JwtUtils;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

class JwtUtilsTest extends TestCase
{
    private $jwtUtils;

    protected function setUp()
    {
        $this->jwtUtils = new JwtUtils();
    }

    public function testGenereToken()
    {

        $user = new User();
        $user->setUsername('Bob');
        $user->setPassword('12345');
        $user->setEmail('bob@gmail.com');
        $token = $this->jwtUtils->genereToken($user);

        $tokens = explode(".", $token);

        $this->assertEquals(3, count($tokens));
    }

    public function testDecodeTokenType()
    {
        //https://jwt.io/
        $token = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo1LCJ1c2VybmFtZSI6ImtldmluNCIsInRpbWUiOiIyMDE5LTA2LTI2IDE0OjE3OjE1In0.ShjZlpeIEfq46_GMXOmsFYOPbue4Sv9Cy91wBFQOgAo";
        $decodedToken = $this->jwtUtils->decodeToken($token);
        $this->assertEquals(5, $decodedToken['user_id']);
    }

}