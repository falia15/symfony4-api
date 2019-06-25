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
        $body = json_decode($request->getContent());

        // Checking confirm password
        if($body->password != $body->password_confirmation){
            return $this->json(['error' => "Password and confirmation password does not match"], 400);
        }

        // Create User and test entity validation
        $user = $this->userRepository->createUser($body);
        $errors = $this->validator->validate($user);
        // update hashpassword to the user
        $user = $this->userRepository->updateUserpassword($user);
        
        // return error if find any
        if(count($errors) > 0){
            $data = $this->jsonHandler->responseValidator($errors);
            return $this->json($data);
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
        $body = json_decode($request->getContent());

        // Get User
        $user = $this->userRepository->findOneBy(['username' => $body->username]);
        if(!$user){
            return $this->json(['error' => "Could not find your account"], 400);
        }

        // Check password
        if($userUtils->checkPassword($user, $body->password) == false){
            return $this->json(['error' => "Your password is incorrect"], 400);
        }

        $token = $jwt->genereToken($user);

        return $this->json(['token' => $token], 200);
    }

    /**
     * @Route("/password", name="password.edit", methods={"POST"})
     */
    public function editPassword(Request $request, UserUtils $userUtils, JwtUtils $jwt) : Response
    {
        $body = json_decode($request->getContent());
        
        // Decode token
        $token = $jwt->decodeToken($request->headers->get('authorization'));
        if(isset($token['error'])){
            return $this->json(['error' => $token['error']], 400);
        }

        // get current user
        $user = $this->userRepository->find($token['user_id']);
        
        // Verif current password
        if($userUtils->checkPassword($user, $body->password) == false){
            return $this->json(['error' => "Your former password is incorrect"], 400);
        }

        // Verif new password confirm
        if($body->new_password != $body->new_password_confirm){
            return $this->json(['error' => "Password and confirm password does not match"], 400);
        }

        // Update password, first update the password without hash so the string length is checked with validator
        $user->setPassword($body->new_password);
        $errors = $this->validator->validate($user);

        if(count($errors) > 0){
            return $this->jsonHandler->responseJson($errors, 400);
        }

        // Update password with hasing
        $updatedUser = $this->userRepository->updateUserpassword($user, $body->new_password);
        
        // save new user to database
        $this->entityManager->persist($updatedUser);
        $this->entityManager->flush();

        return $this->json(['message' => "Password updated"], 200);
    }
}
