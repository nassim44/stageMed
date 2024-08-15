<?php

namespace App\Controller\Api;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/Like/{productId}/{userId}', name: 'app_api_Like',methods: ['POST'])]
    public function Like($productId,$userId): Response
    {
        return $this->userService->likedProducts($userId,$productId);
    }
    #[Route('/unLike/{productId}/{userId}', name: 'app_api_unlike',methods: ['POST'])]
    public function Unlike($productId,$userId): Response
    {
        return $this->userService->RemovelikedProducts($userId,$productId);
    }
}
