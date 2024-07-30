<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\ProductService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    private ProductService $productService;
    private LoggerInterface $logger;

    public function __construct(ProductService $productService,LoggerInterface $logger)
    {
        $this->productService = $productService;
        $this->logger = $logger;
    }
    #[Route('/product', name: 'app_api_product',methods: ['GET'])]
    public function index(): Response
    {
        return $this->productService->getAllProducts();
    }
    #[Route('/product/{id}', name: 'app_product_getproduct',methods: ['GET'])]
    public function getProductById(int $id): Response
    {
        return $this->productService->getProductByID($id);
    }
}
