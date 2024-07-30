<?php

namespace App\Services;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryService
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer,LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function getAllCategories(): Response {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $jsonCategories = $this->serializer->serialize($categories,'json', ['groups' => 'category']);
        return new JsonResponse($jsonCategories, Response::HTTP_OK, [], true);

    }
}