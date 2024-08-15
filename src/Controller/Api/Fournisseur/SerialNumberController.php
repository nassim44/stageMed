<?php

namespace App\Controller\Api\Fournisseur;

use App\Services\SerialNumberService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerialNumberController extends AbstractController
{
    private SerialNumberService $serialNumberService;

    public function __construct(SerialNumberService $serialNumberService)
    {
        $this->serialNumberService = $serialNumberService;
    }
    #[Route('/serialnumber/{inventoryId}', name: 'app_api_fournisseur_serial',methods: ["POST"])]
    public function AddSerialNumber(Request $request,int $inventoryId): Response
    {
        return $this->serialNumberService->new($request,$inventoryId);
    }
}
