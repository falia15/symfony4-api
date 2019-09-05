<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Utils\JsonHandler;

/**
 * This class handler all json response for the Game Entity
 */
class AnimeHandler extends JsonHandler {

    /**
     * Format to json an array of Anime entity 
     * @param $animes, array of Anime or Anime
     */
    public function animeResponse($animes, $status = 200) : string
    {
        return $this->serializer->serialize($animes, 'json',['groups' => ['anime_default']]);
    }


}