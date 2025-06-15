<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;

final class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(NotificationRepository $notificationRepository): Response
  {
    $user = $this->getUser();

    $notifications = $notificationRepository->findAll();

    return $this->render('notification/index.html.twig', [
        'controller_name' => 'NotificationController',
        'notifications' => $notifications,
    ]);
  }
}