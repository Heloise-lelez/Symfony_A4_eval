<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Form\AddProductForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class AddProductController extends AbstractController
{
    #[Route('/add/product', name: 'app_add_product')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $product = new Product();   
        $form = $this->createForm(AddProductForm::class, $product);
        $form->handleRequest($request);

        $products = $entityManager->getRepository(Product::class)->findAll();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTime());
            $product->setUser($this->getUser());
            
            $entityManager->persist($product);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('app_product');
        }
        return $this->render('add_product/index.html.twig', [
            'addProductForm' => $form->createView(),
            'product' => $product,
            'products' => $products,
        ]);
    }
     #[Route('/get/products/{id}', name: 'app_get_products')]
    public function editProduct(string $id, EntityManagerInterface $entityManager): Response
    {
    $product = $entityManager->getRepository(Product::class)->find($id);

    $form = $this->createForm(EditProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTime());
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('app_user_profil');
        }

    $entityManager->persist($user);
    $entityManager->flush();

    return $this->redirectToRoute('app_users_list');
}
    }
