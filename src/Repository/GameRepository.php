<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
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
        $game->setScoreToWin($body['score_to_win']);

        return $game;
    }

    public function getGameByStatus(int $status) : array
    {
        $connection = $this->getEntityManager()->getConnection();
        
        $sql = "SELECT 
        game.id, user_creator_id, username as creator, status, level, answer, timestamp, score_to_win, count(game_user.user_id) as total_player
        FROM game
        JOIN game_user ON game_user.game_id = game.id
        JOIN user ON user.id = game.user_creator_id
        WHERE game.status = :status
        group by game.id
        ORDER BY timestamp DESC";
        
        $statement = $connection->prepare($sql);
        $statement->execute(['status' => $status]);
       
        // returns an array of arrays (i.e. a raw data set)
        return $statement->fetchAll();
    }


}
