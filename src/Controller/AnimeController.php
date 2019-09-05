<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\AnimeRepository;
use App\Utils\JsonHandler;
use App\Utils\AnimeHandler;
use App\Utils\JwtUtils;

/**
* @Route("/api")
*/
class AnimeController extends AbstractController
{

    public function __construct(JwtUtils $jwt, AnimeRepository $animeRepository, JsonHandler $jsonHandler, AnimeHandler $animeHandler)
    {
        $this->jwt = $jwt;
        $this->animeRepository = $animeRepository;
        $this->jsonHandler = $jsonHandler;
        $this->animeHandler = $animeHandler;
    }

    /**
     * @Route("/anime.index", name="anime.index", methods={"GET", "OPTIONS"})
     */
    public function index(Request $request)
    {
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
        }

        $level = $request->query->get('level');
        $animes = $this->animeRepository->getAllByLevel($level);

        $data = $this->animeHandler->animeResponse($animes);
        return $this->jsonHandler->responseJson($data);

    }
}
