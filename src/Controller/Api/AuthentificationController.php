<?php

namespace App\Controller\Api;

use App\Security\UserAuthenticator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\AuthentificationService;

class AuthentificationController extends AbstractController
{
    private AuthentificationService $authentificationService;

    public function __construct(AuthentificationService $authentificationService)
    {
        $this->authentificationService = $authentificationService;
    }

    #[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function new(Request $request, ValidatorInterface $validator, MailerInterface $mailer): Response
    {
        return $this->authentificationService->new($request, $validator, $mailer);
    }
    #[Route('/verify/{token}', name: 'app_verify_email', methods: ['GET'])]
    public function verifyEmail(string $token): Response
    {
        return $this->authentificationService->verifyEmail($token);
    }
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager): JsonResponse
    {

        return $this->authentificationService->login($request,$passwordHasher,$jwtManager);
    }
    #[Route('/admin/getAllUsers', name: 'api_fetch_users', methods: ['GET'])]
    public function fetchAllUsers(): JsonResponse
    {
        return $this->authentificationService->fetchAllUsers();
    }
}
