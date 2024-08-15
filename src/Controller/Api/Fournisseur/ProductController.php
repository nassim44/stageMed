<?php

namespace App\Controller\Api\Fournisseur;

use App\Services\ProductService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductService $productService;
    private LoggerInterface $logger;

    public function __construct(ProductService $productService,LoggerInterface $logger)
    {
        $this->productService = $productService;
        $this->logger = $logger;
    }
    #[Route('/product/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return  $this->productService->new($request);
    }
    #[Route('/product/ownSerial/new', name: 'app_product_own', methods: ['GET', 'POST'])]
    public function newSerial(Request $request): Response
    {
        return  $this->productService->newOwnSerial($request);
    }
}
