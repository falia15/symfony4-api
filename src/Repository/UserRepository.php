<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->encoder = $encoder;
    }

    public function createUser(array $body) : User
    {
        $user = new User();
        $username =                $body['username'];
        $password =                $body['password'];
        $email =                   $body['email'];

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);

        return $user;
    }

    public function updateUserpassword(User $user, string $password = null) : User
    {
        // if password is not given, means it's use to hash the current password of user entity
        if($password == null){
            $password = $user->getPassword();
        }

        $encodedPassword = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);

        return $user;
    }

}
