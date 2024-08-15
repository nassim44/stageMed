<?php

namespace App\Services;

use App\Entity\Inventory;
use App\Entity\Product;
use App\Entity\Rate;
use App\Entity\User;
use App\Entity\WareHouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class InventoryService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function new(Request $request,int $productId,int $wareHouseId): Response
    {
        $data = $request->getContent();
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $wareHouse = $this->entityManager->getRepository(WareHouse::class)->find($wareHouseId);
        $newInventory = $this->serializer->deserialize($data, Inventory::class, 'json');
        $product->addInventory($newInventory);
        $wareHouse->addInventory($newInventory);
        try {
            $newInventory->setDisponibilte("disponible");
            $newInventory->setQuantity($product->getQuantite());
            $newInventory->setProduct($product);
            $newInventory->setWareHouse($wareHouse);
            $this->entityManager->getRepository(Inventory::class)->save($newInventory);
            $this->entityManager->getRepository(Product::class)->save($product);
            $this->entityManager->getRepository(WareHouse::class)->save($wareHouse);
            $json = $this->serializer->serialize($newInventory, 'json', ['groups' => "inventory"]);
            return new Response($json);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findInvnentory(int $productId,int $quantity): Response
    {
        $inventory = $this->entityManager->getRepository(Inventory::class)->findOneBy(['product' => $productId]);
        $inventory->setQuantity($inventory->getQuantity() - $quantity);
        if($inventory->getQuantity() === 0) {
            $inventory->setDisponibilte('Used');
        }
        $this->entityManager->getRepository(Inventory::class)->save($inventory);
        $json = $this->serializer->serialize($inventory, 'json', ['groups' => "inventory"]);
        return new Response($json);

    }

}