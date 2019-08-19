<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Utils\JsonHandler;

/**
 * This class handler all json response for the Game Entity
 */
class GameHandler extends JsonHandler {

    /**
     * Format to json an array of Game entity 
     * @param $games, array of Game or Game
     */
    public function gamesResponse($games, $status = 200) : Response
    {
        $jsonData = $this->serializer->serialize($games, 'json',['groups' => ['game_default']]);
        return $this->headerData($jsonData, $status);
    }

    public function gameWithPlayer($game, $status = 200) : Response
    {
        $jsonData = $this->serializer->serialize($game, 'json',['groups' => ['game_with_user']]);
        return $this->headerData($jsonData, $status);
    }

}