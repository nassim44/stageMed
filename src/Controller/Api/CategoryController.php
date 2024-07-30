<?php

namespace App\Controller\Api;

use App\Services\CategoryService;
use App\Services\ProductService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CategoryController extends AbstractController
{
    private ProductService $productService;
    private CategoryService $categoryService;
    private LoggerInterface $logger;
    public function __construct(ProductService $productService,CategoryService $categoryService,LoggerInterface $logger)
    {
        $this->categoryService = $categoryService;
        $this->logger = $logger;
    }

    #[Route('/categories', name: 'app_api_category',methods: ['GET'])]
    public function getAllCategories(AuthorizationCheckerInterface $authChecker): Response{
        return $this->categoryService->getAllCategories();
    }
}
