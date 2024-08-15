<?php

namespace App\Controller\Api\Fournisseur;

use App\Services\InventoryService;
use App\Services\WareHouseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WareHouseController extends AbstractController
{
    private WareHouseService $wareHouseService;

    public function __construct(WareHouseService $wareHouseService)
    {
        $this->wareHouseService = $wareHouseService;
    }
    #[Route('/warehouse', name: 'app_api_fournisseur_warehouse',methods: ["POST"])]
    public function AddInventory(Request $request): Response
    {
        return $this->wareHouseService->new($request);
    }
    #[Route('/getWarehouses', name: 'app_get_warehouses',methods: ['GET'])]
    public function index(): Response
    {
        return $this->wareHouseService->getAllWareHouses();
    }
}
