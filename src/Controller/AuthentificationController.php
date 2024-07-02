<?php

namespace App\Controller;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

class AuthentificationController extends AbstractController
{

    #[Route('/authentification', name: 'app_authentification')]
    public function index(): Response
    {
        return $this->render('authentification/index.html.twig', [
            'controller_name' => 'AuthentificationController',
        ]);
    }
    #[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher,): Response
    {

        $data = $request->getContent();

        $newUser = new User();
        $newUser = $serializer->deserialize($data, User::class, 'json');
        $errors = $validator->validate($newUser);
        $dataPwd = json_decode($request->getContent(), true);
        $hashedPassword = $passwordHasher->hashPassword($newUser, $dataPwd['Password']);
        $newUser->setPassword($hashedPassword);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }
        try {
            $userRepository->save($newUser);
            $json = $serializer->serialize($newUser, 'json', ['groups' => "users"]);
            return new Response($json);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        /** @var \App\Entity\User|PasswordAuthenticatedUserInterface $user */
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
