<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ProductService extends AbstractController
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
    public function getAllProducts(): JsonResponse {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $jsonProducts = $this->serializer->serialize($products, 'json', ['groups' => 'products']);
        return new JsonResponse($jsonProducts, JsonResponse::HTTP_OK, [], true);
    }
    public function getProductByID(int $id): JsonResponse {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $jsonProduct = $this->serializer->serialize($product, 'json', ['groups' => 'products']);
        return new JsonResponse($jsonProduct, JsonResponse::HTTP_OK, [], true);
    }
    public function new(Request $request): Response
    {
        $data = $request->getContent();
        $dataJson = json_decode($request->getContent(), true);
        $shippingRegionsQueryParam = $request->query->get('shippingRegions', '');

        // Convert query parameter string to array if it's a string
        $shippingRegions = is_string($shippingRegionsQueryParam) ? explode(',', $shippingRegionsQueryParam) : [];

        // Log the extracted shipping regions
        $this->logger->info('Shipping Regions from query:', $shippingRegions);

        $decodedImage = base64_decode($dataJson['image']);
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $imageName = uniqid() . '.jpg';
        file_put_contents($imagePath . $imageName, $decodedImage);


        $newProduct = $this->serializer->deserialize($data, Product::class, 'json');
        $newProduct->setShippingRegions($shippingRegions);
        $category = $this->entityManager->getRepository(Category::class)->find($dataJson['category']);
        $user = $this->entityManager->getRepository(User::class)->find($dataJson['productCreator']);
        $category->addProduct($newProduct);
        $user->addProduct($newProduct);

        try {
            $newProduct->setProductImage($imageName);
            $this->entityManager->getRepository(Product::class)->save($newProduct);
            $this->entityManager->getRepository(Category::class)->save($category);
            $this->entityManager->getRepository(User::class)->save($user);

            $json = $this->serializer->serialize($newProduct, 'json', ['groups' => "products"]);
            return new Response($json);
        } catch (\Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}