<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

final class DetailProductController extends AbstractController
{
    #[Route('/detail/product/{id}', name: 'app_detail_product')]
    public function index(string $id, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $product = $entityManager->getRepository(Product::class)->find($id);


        return $this->render('detail_product/index.html.twig', [
            'controller_name' => 'DetailProductController',
            'product' => $product,
            'user' => $user,
        ]);
    }

     #[Route('/detail/product/{id}/buy', name: 'app_detail_buy_product', methods: ['POST'])]
    public function buyProduct(int $id, EntityManagerInterface $entityManager): Response
    {
    $product = $entityManager->getRepository(Product::class)->find($id);

    $user = $this->getUser();

    $user->setPoints($user->getPoints() - $product->getPrice()); 
    $entityManager->flush();

    return $this->redirectToRoute('app_detail_product', ['id' => $id]);
}
}
