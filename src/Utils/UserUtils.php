<?php

namespace App\Utils;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserUtils 
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Verif if an encoded password is the same password of a non encoded password given as a parameter 
     */
    public function checkPassword(User $user, string $password) : bool
    {
        if($this->encoder->isPasswordValid($user, $password)){
            return true;
        }
        return false;
    }


}