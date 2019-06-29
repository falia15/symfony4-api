<?php

namespace App\Repository;

use App\Entity\GameUser;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameUser[]    findAll()
 * @method GameUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameUser::class);
    }

    public function userJoinGame(User $user, Game $game) : GameUser
    {
        $gameUser = new GameUser();
        $gameUser->setUser($user);
        $gameUser->setGame($game);
        $gameUser->setScore(0); // a user has 0 when it joins a game
        return $gameUser;
    }
}
