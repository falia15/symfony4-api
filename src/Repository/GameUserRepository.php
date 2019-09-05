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
        $gameUser->setScore(0); // a user has 0 when they join a game
        return $gameUser;
    }

    
    public function getRunningGame(User $user) : array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = "SELECT game.id, username as creator, level, answer, score_to_win, timestamp, status, count(game_user.user_id) as total_player
        FROM game_user
        JOIN game ON game.id = game_user.game_id
        JOIN user ON user.id = game.user_creator_id
        WHERE game.status != 3 AND game.id IN (
            SELECT game_id 
            FROM game_user
            WHERE user_id = :userId
        )
        GROUP BY id
        ORDER BY game.timestamp DESC";

        $statement = $connection->prepare($sql);
        $statement->execute(['userId' => $user->getId()]);

        $result = $statement->fetch();

        // prevent from sending a boolean if they aren't any result, send an empty array
        if($result === false){
            return [];
        }
        return $result;
    }

    public function getUserInGame(int $gameId) : array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = "SELECT user.id, username 
        FROM game_user
        JOIN user ON user.id = game_user.user_id
        WHERE game_id = :gameId";

        $statement = $connection->prepare($sql);
        $statement->execute(['gameId' => $gameId]);

        // returns an array of arrays (i.e. a raw data set)
        return $statement->fetchAll();
    }

    public function getUserGame(int $userId, int $gameId) : GameUser
    {
        $gameUser = $this->findOneBy([
            'user' => $userId,
            'game' => $gameId,
        ]);

        return $gameUser;
    }
}
