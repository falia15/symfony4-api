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
            $data = $this->jsonHandler->defaultSerialize(['error' => $token['error']], 400);
            return $this->jsonHandler->responseJson($data);
        }
        // get auth user
        $user = $this->userRepository->find($token['id']);

        // check if user is not already in a game
        $isUserInGame = $this->gameRepository->isUserInGame($user);
        if($isUserInGame === true){
            $data = $this->jsonHandler->defaultSerialize(['error' => 'You are already in a game']);
            $this->jsonHandler->responseJson($data);
        }

        // create game and test entity validation
        $game = $this->gameRepository->createGame($body, $user);
        $errors = $this->validator->validate($game);
         
         // return error if find any
        if(count($errors) > 0){
             $data = $this->jsonHandler->responseValidator($errors);
             $dataSerialize = $this->jsonHandler->defaultSerialize($data, 400);
             return $this->jsonHandler->responseJson($data);
        }
        
        // add user creator to the game
        $gameUser = $this->gameUserRepository->userJoinGame($user, $game);
 
        // save game to database
        $this->entityManager->persist($game);
        $this->entityManager->persist($gameUser);
        $this->entityManager->flush();

        // return new game
        $data = $this->gameHandler->gamesResponse($game);
        return $this->jsonHandler->responseJson($data);
    }

    /**
     * @Route("/game.store.join", name="game.store.join", methods={"POST"})
     */
    public function storeUserGame(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
        }
        // get auth user and game
        $body = json_decode($request->getContent(), true);
        $user = $this->userRepository->find($token['id']);
        $game = $this->gameRepository->find($body['game_id']);
        
        // save game to database
        $gameUser = $this->gameUserRepository->userJoinGame($user, $game);
        $this->entityManager->persist($gameUser);
        $this->entityManager->flush();

        $data = $this->jsonHandler->defaultSerialize(['gameId' => $body['game_id']]);
        return $this->jsonHandler->responseJson($data);

    }

    /**
     * @Route("/game.index.status", name="game.index.status", methods={"GET", "OPTIONS"})
     */
    public function indexGameStatus(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
        }

        $status = $request->query->get('id');
        $gameAvailable = $this->gameRepository->getGameByStatus($status);
        
        $data = $this->gameHandler->gamesResponse($gameAvailable);
        return $this->jsonHandler->responseJson($data);
    }

    /**
     * @Route("/game.index.running", name="game.index.running", methods={"GET", "OPTIONS"})
     */
    public function indexGameRunning(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
        }

        $user = $this->userRepository->find($token['id']);
        $game = $this->gameUserRepository->getRunningGame($user);

        $data = $this->gameHandler->gamesResponse($game);
        return $this->jsonHandler->responseJson($data);
    }   

    /**
     * @Route("/game.show", name="game.show", methods={"GET", "OPTIONS"})
     */
    public function getGame(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
        }

        $gameId = $request->query->get('id');
        $game = $this->gameRepository->find($gameId);

        $data = $this->gameHandler->gamesResponse($game);
        return $this->jsonHandler->responseJson($data);
    }

    /**
     * @Route("/game.user.show", name="game.user.show", methods={"GET", "OPTIONS"})
     */
    public function getUserByGame(Request $request) : Response
    {
        // Decode token
        $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
        if(isset($token['error'])){
            return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
        }

        $gameId = $request->query->get('id');
        $users = $this->gameUserRepository->getUserInGame($gameId);

        $data = $this->jsonHandler->defaultSerialize($users);
        return $this->jsonHandler->responseJson($data);
    }

    /**
     * @Route("/game.status", name="game.status", methods={"POST"})
     */
    public function changeGameStatus(Request $request) : Response
    {
       // Decode token
       $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
       if(isset($token['error'])){
           return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
       }

       $body = json_decode($request->getContent(), true);

       $game = $this->gameRepository->find($body["id"]);
       $game->setStatus($body['status']);
       $this->entityManager->flush();

       return $this->jsonHandler->responseJson(json_encode(['status' => $body['status']]));
    }

    /**
     * @Route("/game.user.leave", name="game.user.leave", methods={"POST"})
     */
    public function userLeaveGame(Request $request) : Response
    {
         // Decode token
       $token = $this->jwt->decodeToken($request->headers->get('Authorization'));
       if(isset($token['error'])){
           return $this->jsonHandler->responseJson(json_encode(['error' => $token['error']]), 400);
       }

       $body = json_decode($request->getContent(), true);
       $user = $this->userRepository->find($token['id']);

       $gameUser = $this->gameUserRepository->getUserGame($user->getId(), $body['game_id']);

       $this->entityManager->remove($gameUser);
       $this->entityManager->flush();

       return $this->jsonHandler->responseJson(json_encode(['game_id' => $body['game_id']]));
    }


}
