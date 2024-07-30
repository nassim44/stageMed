<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;


class AuthentificationService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private UserPasswordHasherInterface $passwordHasher;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordHasherInterface $passwordHasher, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->passwordHasher = $passwordHasher;
        $this->logger = $logger;
    }
    public function new(Request $request, ValidatorInterface $validator, MailerInterface $mailer): Response
    {
        $data = $request->getContent();
        $newUser = new User();
        $newUser = $this->serializer->deserialize($data, User::class, 'json');
        $errors = $validator->validate($newUser);

        $dataPwd = json_decode($request->getContent(), true);

            $decodedImage = base64_decode($dataPwd['image']);

            $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $imageName = uniqid() . '.jpg';


            file_put_contents($imagePath . $imageName, $decodedImage);

        $newUser->setProfileImage($imageName);

        $hashedPassword = $this->passwordHasher->hashPassword($newUser, $dataPwd['Password']);
        $newUser->setPassword($hashedPassword);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        try {
            $verificationToken = md5(uniqid());
            $newUser->setVerificationToken($verificationToken);
            $newUser->setStatus(false);
            $this->entityManager->getRepository(User::class)->save($newUser);

            $email = (new Email())
                ->from('Nassim.BenAli@esprit.tn')
                ->to($newUser->getEmail())
                ->subject('Please verify your email address')
                ->html('<p>Click <a href="http://127.0.0.1:8000/verify/' . $verificationToken . '">here</a> to verify your email address.</p>');

            $mailer->send($email);

            $json = $this->serializer->serialize($newUser, 'json', ['groups' => 'users']);
            return new Response($json);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function verifyEmail(string $token): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['VerificationToken' => $token]);
        if (!$user) {
            return new Response('Invalid verification token', Response::HTTP_BAD_REQUEST);
        }
       if($user->getRoles() === ["ROLE_FOURNISSEUR"]) {
           $user->setStatus(false);
           $user->setVerificationToken("done");
           $this->entityManager->getRepository(User::class)->save($user);
           return new Response('Email verified successfully. Your account gonna be verified by the Adminstrator .', Response::HTTP_OK);
       }else {
           $user->setStatus(true);
           $user->setVerificationToken("done");
           $this->entityManager->getRepository(User::class)->save($user);
           return new Response('Email verified successfully. You can now log in.', Response::HTTP_OK);
       }
    }
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        /** @var \App\Entity\User|PasswordAuthenticatedUserInterface $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        if ($user->getVerificationToken() !== "done" && $user->getVerificationToken() !== "REJECTED" && $user->getVerificationToken() !== "ACCEPTED") {
            return new JsonResponse(['error' => 'Activate Your Account'], JsonResponse::HTTP_CONFLICT);
        } else if ($user->getVerificationToken() === "REJECTED") {
            return new JsonResponse(['error' => 'Your Account Not Approuved By  The Administrator'], JsonResponse::HTTP_CONFLICT);
        }
        if ($user->isStatus() === false) {
            return new JsonResponse(['error' => 'Not verified By the Administrator'], JsonResponse::HTTP_CONFLICT);
        }
        if($user->getRoles() === ['ROLE_ADMIN']) {
            $token = $jwtManager->create($user);
            $data = [
                'message' => 'Admin',
                'token' => $token,
            ];
            return new JsonResponse($data);
        }
        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
    public function fetchAllUsers(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $jsonUsers = $this->serializer->serialize($users, 'json', ['groups' => 'users']);

        return new JsonResponse($jsonUsers, JsonResponse::HTTP_OK, [], true);
    }
}
