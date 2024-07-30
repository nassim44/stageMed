<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/fornissors', name: 'app_user_fornissors', methods: ['GET'])]
    public function getAllFornissors(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findByRole(),
        ]);
    }
    #[Route('/finduserbyemail/{email}', name: 'app_user_find', methods: ['GET'])]
    public function findUserByEmail(string $email,SerializerInterface $serializer): Response
    {
        return $this->userService->findUserByEmail($email);
    }

    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $this->userService->delete($request,$user,$csrfTokenManager);
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/admin/acceptAccount/{id}', name: 'app_fournisseur_accept', methods: ['POST'])]
    public function acceptFournisseur(int $id): Response
    {
         $this->userService->AcceptFournisseur($id);
    }
    #[Route('/admin/rejecteAccount/{id}', name: 'app_fournisseur_rejecter', methods: ['POST'])]
    public function rejectFournisseur(int $id): Response
    {
        $this->userService->RejectFournisseur($id);
    }
}
