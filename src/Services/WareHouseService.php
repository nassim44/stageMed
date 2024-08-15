<?php

namespace App\Services;

use App\Entity\Inventory;
use App\Entity\WareHouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class WareHouseService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function new(Request $request): Response
    {
        $data = $request->getContent();
        $dataJson = json_decode($request->getContent(), true);
        $newWareHouse = $this->serializer->deserialize($data, WareHouse::class, 'json');
        $decodedImage = base64_decode($dataJson['image']);
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $imageName = uniqid() . '.jpg';
        file_put_contents($imagePath . $imageName, $decodedImage);
        try {
            $newWareHouse->setImage($imageName);
            $this->entityManager->getRepository(WareHouse::class)->save($newWareHouse);
            $json = $this->serializer->serialize($newWareHouse, 'json', ['groups' => "warehouse"]);
            return new Response($json);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getAllWareHouses(): JsonResponse {
        $products = $this->entityManager->getRepository(WareHouse::class)->findAll();
        $jsonInventories = $this->serializer->serialize($products, 'json', ['groups' => 'warehouse']);
        return new JsonResponse($jsonInventories, JsonResponse::HTTP_OK, [], true);
    }
}