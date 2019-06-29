<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function createGame(array $body, User $user) : Game
    {
        $game = new Game();
        $game->setUserCreator($user);
        $game->setStatus(1); // 1 is the first status "waiting for player"
        $game->setTimestamp(new \DateTime());
        $game->setLevel($body['level']);
        $game->setAnswer($body['answer']);

        return $game;
    }


}
