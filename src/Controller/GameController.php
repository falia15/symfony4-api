<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\GameUserRepository;
use App\Repository\UserRepository;

use App\Utils\JsonHandler;
use App\Utils\JwtUtils;
use App\Utils\GameHandler;

/**
* @Route("/api")
*/

class GameController extends AbstractController
{

    public function __construct(
        GameRepository $gameRepository, 
        ValidatorInterface $validator, 
        JsonHandler $jsonHandler, 
        GameUserRepository $gameUserRepository,
        UserRepository $userRepository,
        JwtUtils $jwt,
        EntityManagerInterface $entityManager,
        GameHandler $gameHandler)
    {
        $this->gameRepository = $gameRepository;
        $this->validator = $validator;
        $this->jsonHandler = $jsonHandler;
        $this->gameUserRepository = $gameUserRepository;
        $this->userRepository = $userRepository;
        $this->jwt = $jwt;
        $this->entityManager = $entityManager;
        $this->gameHandler = $gameHandler;
    }

    /**
     * Store a new game in the database
     * @Route("/game.store", name="game.store", methods={"POST"})
     */
    public function store(Request $request) : Response
    {
        $body = json_decode($request->getContent(), true);
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->json(['error' => $token['error']], 400);
        }
        // get auth user
        $user = $this->userRepository->find($token['user_id']);

        // create game and test entity validation
        $game = $this->gameRepository->createGame($body, $user);
        $errors = $this->validator->validate($game);
         
         // return error if find any
        if(count($errors) > 0){
             $data = $this->jsonHandler->responseValidator($errors);
             return $this->json($data, 400);
        }

         // add user creator to the game
         $gameUser = $this->gameUserRepository->userJoinGame($user, $game);
 
         // save game to database
         $this->entityManager->persist($game);
         $this->entityManager->persist($gameUser);
         $this->entityManager->flush();

        // return new game
        return $this->gameHandler->gamesResponse($game);
    }

    /**
     * @Route("/game.store.join", name="game.store.join", methods={"POST"})
     */
    public function storeUserGame(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->json(['error' => $token['error']], 400);
        }
        // get auth user and game
        $body = json_decode($request->getContent(), true);
        $user = $this->userRepository->find($token['user_id']);
        $game = $this->gameRepository->find($body['game_id']);
        
        // save game to database
        $gameUser = $this->gameUserRepository->userJoinGame($user, $game);
        $this->entityManager->persist($gameUser);
        $this->entityManager->flush();

        return $this->jsonHandler->responseJson(['gameId' => $body['game_id']]);

    }

    /**
     * @Route("/game.index.status", name="game.index.status", methods={"GET", "OPTIONS"})
     */
    public function indexGameStatus(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->json(['error' => $token['error']], 400);
        }

        $status = $request->query->get('id');
        $gameAvailable = $this->gameRepository->getGameByStatus($status);
        
        return $this->gameHandler->gamesResponse($gameAvailable);
    }

    /**
     * @Route("/game.index.running", name="game.index.running", methods={"GET", "OPTIONS"})
     */
    public function indexGameRunning(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->json(['error' => $token['error']], 400);
        }

        $user = $this->userRepository->find($token['user_id']);
        $game = $this->gameUserRepository->getRunningGame($user);

        return $this->jsonHandler->responseJson($game);
    }   


}
