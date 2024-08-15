<?php

namespace App\Controller\Api\Fournisseur;

use App\Services\InventoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventoryController extends AbstractController
{
    private InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }
    #[Route('/inventory/{productId}/{warehouseId}', name: 'app_api_fournisseur_inventory_add',methods: ["POST"])]
    public function AddInventory(Request $request,int $productId,int $warehouseId): Response
    {
        return $this->inventoryService->new($request,$productId,$warehouseId);
    }

}
