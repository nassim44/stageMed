<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Rate;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class RateService
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }
    public function new(Request $request,int $productId,int $userId): Response
    {
        $data = $request->getContent();
        $newRate = $this->serializer->deserialize($data, Rate::class, 'json');
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $product->addRate($newRate);
        $user->addRate($newRate);

        try {
            $this->entityManager->getRepository(Rate::class)->save($newRate);
            $this->entityManager->getRepository(Product::class)->save($product);
            $this->entityManager->getRepository(User::class)->save($user);

            $json = $this->serializer->serialize($newRate, 'json', ['groups' => "rates"]);
            return new Response($json);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findProductReview(int $productId): Response
    {

        $rates = $this->entityManager->getRepository(Rate::class)->findBy(["product" => $productId]);
        $json = $this->serializer->serialize($rates, 'json', ['groups' => "rates"]);
        return new Response($json);

    }
}