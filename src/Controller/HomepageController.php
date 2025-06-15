<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {

          $product = new Product();
        $products = $entityManager->getRepository(Product::class)->findAll();
       
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'products' => $products
        ]);
    }
}
