<?php

namespace App\Services;

use App\Entity\Product;
use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }
    public function findUserByEmail(string $email): Response
    {
        $userInfo = $this->entityManager->getRepository(User::class)->loadUserByIdentifier($email);
        $json = $this->serializer->serialize($userInfo, 'json', ['groups' => "users"]);
        return new Response($json);
    }

    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    public function delete(Request $request, User $user, CsrfTokenManagerInterface $csrfTokenManager): void
    {
        $token = new CsrfToken('delete' . $user->getId(), $request->request->get('_token'));

        if ($csrfTokenManager->isTokenValid($token)) {
            $this->entityManager->getRepository(User::class)->remove($user);
        }
    }
    public function AcceptFournisseur(int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if (!$user) {
            return new Response('Invalid User', Response::HTTP_BAD_REQUEST);
        }
        $user->setStatus(true);
        $user->setVerificationToken('ACCEPTED');
        $this->entityManager->getRepository(User::class)->save($user);
        return new Response('Fournisseur Accepte', Response::HTTP_OK);

    }
    public function RejectFournisseur(int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if (!$user) {
            return new Response('Invalid User', Response::HTTP_BAD_REQUEST);
        }
        $user->setVerificationToken('REJECTED');
        $this->entityManager->getRepository(User::class)->save($user);
        return new Response('Fournisseur rejecte', Response::HTTP_OK);
    }
    public function likedProducts(int $userId,int $productId): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $productId]);
        try {
            $user->addLikedProduct($product);
            $product->addLikedUser($user);
            $this->entityManager->getRepository(User::class)->save($user);
            $this->entityManager->getRepository(Product::class)->save($product);
            return new Response('success', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function RemovelikedProducts(int $userId,int $productId): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $productId]);
        try {
            $user->removeLikedProduct($product);
            $product->removeLikedUser($user);
            $this->entityManager->getRepository(User::class)->save($user);
            $this->entityManager->getRepository(Product::class)->save($product);
            return new Response('success', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}