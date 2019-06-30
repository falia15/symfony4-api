<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Utils\JsonHandler;

class GameHandler extends JsonHandler {

    /**
     * Format to json an array of Game entity 
     */
    public function gamesResponse(array $games) : Response
    {
        $result = [];
        foreach($games as $game){
            $gameArray = [];
            $userArray = [];

            $userArray['id'] = $game->getUserCreator()->getId();
            $userArray['name'] = $game->getUserCreator()->getUsername();

            $gameArray['id'] = $game->getId();
            $gameArray['level'] = $game->getLevel();
            $gameArray['answer'] = $game->getAnswer();
            $gameArray['timestamp'] = $game->getTimestamp()->format('d/m/Y H:i');
            $gameArray['scoreToWin'] = $game->getScoreToWin();
            $gameArray['creator'] = $userArray;

            $result[] = $gameArray;
        }

        return $this->responseJson($result);
    }

}