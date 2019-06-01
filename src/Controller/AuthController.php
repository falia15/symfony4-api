<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\User;
use App\Repository\UserRepository;

use App\Utils\JsonHandler;
use App\Utils\UserUtils;
use App\Utils\JwtUtils;

/**
* @Route("/api")
*/
class AuthController extends AbstractController
{

    private $entityManager;
    private $validator;
    private $serializer;
    private $jsonHandler;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, UserRepository $userRepository, JsonHandler $jsonHandler)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->jsonHandler = $jsonHandler;
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request) : Response
    {
        // Checking confirm password
        if($request->request->get("password") != $request->request->get("password_confirmation")){
            return $this->json(['error' => "Password and confirm password does not match"], 400);
        }

        // Create User and test entity validation
        $user = $this->userRepository->createUser($request);
        $errors = $this->validator->validate($user);
        
        // return error if find any
        if(count($errors) > 0){
            return $this->jsonHandler->responseJson($errors, 400);
        }

        // save user to database
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        return $this->jsonHandler->responseJson($user);
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserUtils $userUtils, JwtUtils $jwt) : Response
    {
        // Get User
        $user = $this->userRepository->findOneBy(['username' => $request->headers->get('username')]);
        if(!$user){
            return $this->json(['error' => "Could not find your account"], 400);
        }

        // Check password
        if($userUtils->checkPassword($user, $request->headers->get('password')) == false){
            return $this->json(['error' => "Your password is incorrect"], 400);
        }

        $token = $jwt->genereToken($user);

        return $this->json(['token' => $token], 200);
    }
}
