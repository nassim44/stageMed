<?php

namespace App\Controller\Api;

use App\Services\ProductService;
use App\Services\RateService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RateController extends AbstractController
{
    private RateService $rateService;
    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }
    #[Route('/rate/{productId}/{userId}', name: 'app_api_rate_add',methods: ['POST'])]
    public function addReview(Request $request,$productId,$userId): Response
    {
        return $this->rateService->new($request,$productId,$userId);
    }
    #[Route('/getproductrate/{productId}', name: 'app_api_rate_getRates',methods: ['GET'])]
    public function getProductReview($productId): Response
    {
        return $this->rateService->findProductReview($productId);
    }
}
