<?php

namespace App\Services;

use App\Entity\Inventory;
use App\Entity\SerialNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class SerialNumberService
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function new(Request $request,int $inventoryId): Response
    {
        $data = $request->getContent();
        $inventory = $this->entityManager->getRepository(Inventory::class)->find($inventoryId);
        $newSerialNumber = $this->serializer->deserialize($data, SerialNumber::class, 'json');
        $inventory->addSerialNumber($newSerialNumber);
        try {
            $this->entityManager->getRepository(SerialNumber::class)->save($newSerialNumber);
            $json = $this->serializer->serialize($newSerialNumber, 'json', ['groups' => "serial"]);
            return new Response($json);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}