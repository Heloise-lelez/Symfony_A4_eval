<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Form\AddProductForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\NotificationService;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Entity\Notification;
use App\Repository\NotificationRepository;

final class AddProductController extends AbstractController
{
    #[Route('/product', name: 'app_add_product')]
    public function index(Request $request,EntityManagerInterface $entityManager,NotificationService $notification): Response
    {
        $product = new Product();
        $user = $this->getUser();   
        $createForm = $this->createForm(AddProductForm::class, $product);
        $createForm->handleRequest($request);

        $products = $entityManager->getRepository(Product::class)->findAll();
        
        if ($createForm->isSubmitted() && $createForm->isValid()) {
            // $product->setCreatedAt(new \DateTime());
             $notification -> sendNotification(
            $user,
            'add',
            $product,
            $entityManager
        );
            $product->setUser($this->getUser());
            
            $entityManager->persist($product);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('app_add_product');
        }
        $action = $request->get('value');
        $editForms = [];
        foreach ($products as $product) {
            $form = $this->createForm(AddProductForm::class, $product, [
                // 'action' => $this->generateUrl('app_edit_products', ['id' => $product->getId()]),
                'action' => $this->generateUrl('app_edit_products', ['id' => $product->getId()]),
                'method' => 'POST',
            ]);
            $editForms[$product->getId()] = $form->createView();
        }
    

        return $this->render('add_product/index.html.twig', [
            'addProductForm' => $createForm->createView(),
            'editProductForm' => $editForms,
            'product' => $product,
            'products' => $products,
        ]);
    }
     #[Route('/products/action/{id}', name: 'app_edit_products')]
     public function update(Product $product, Request $request, EntityManagerInterface $entityManager,NotificationService $notification): Response
    {

        $action = $request->request->get('action');

        if ($action === 'delete') {
            $entityManager->remove($product);
            $entityManager->flush();
            $user = $this->getUser();
            $notification -> sendNotification(
            $user,
            'delete',
            $product,
            $entityManager
        );

        return $this->redirectToRoute('app_add_product');
        }

        $form = $this->createForm(AddProductForm::class, $product);
        $form->handleRequest($request);
        $user = $this->getUser();
        $notification -> sendNotification(
            $user,
            'edit',
            $product,
            $entityManager
        );
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        

        return $this->redirectToRoute('app_add_product');
    }


}