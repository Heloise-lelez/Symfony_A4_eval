<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Message\SendPointsMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final class UsersListController extends AbstractController
{
    #[Route('/users/list', name: 'app_users_list')]
    public function index( EntityManagerInterface $entityManager): Response
    {

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('users_list/index.html.twig', [
            'controller_name' => 'UsersListController',
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}/deactivate', name: 'user_toggle_activate', methods: ['POST'])]
    public function deactivateUser(string $id, EntityManagerInterface $entityManager): Response
    {
    $user = $entityManager->getRepository(User::class)->find($id);
    $user->setActif(!$user->isActif()); 

    $entityManager->persist($user);
    $entityManager->flush();

    return $this->redirectToRoute('app_users_list');
}
    #[Route('/user/givepoints', name: 'user_toggle_givepoints', methods: ['POST'])]
    public function givePointsToUser(MessageBusInterface $bus): Response
    {
    
        $message = new SendPointsMessage(1000); 
        $bus->dispatch($message);

    return $this->redirectToRoute('app_users_list');
} 
}
