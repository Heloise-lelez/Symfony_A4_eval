<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\EditProfilForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

final class UserProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_user_profil')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {


        $user = $this->getUser();
        $form = $this->createForm(EditProfilForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('app_user_profil');
        }

        return $this->render('user_profil/index.html.twig', [
            'controller_name' => 'UserProfilController',
            'user' => $user,
            'editProfilForm' => $form->createView(),
        ]);
    }
}
