<?php

namespace App\Controller\Api;

use App\Services\InventoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventoryController extends AbstractController
{
    private InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }
    #[Route('/inventoryPay/{idProduct}/{quantity}', name: 'inventory_pay',methods: ['POST'])]
    public function editInventoryafterPayment(int $idProduct, int $quantity): Response
    {
        return $this->inventoryService->findInvnentory($idProduct,$quantity);
    }
}
