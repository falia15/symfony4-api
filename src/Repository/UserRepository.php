<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    public function createUser(Request $request) : User
    {
        $user = new User();
        $username =                $request->request->get("username");
        $password =                $request->request->get("password");
        $email =                   $request->request->get('email');

        $encodedPassword = $this->encoder->encodePassword($user, $password);
        // TODO add password check
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($encodedPassword);

        return $user;
    }

}
