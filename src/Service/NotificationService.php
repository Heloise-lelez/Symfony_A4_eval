<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
  public function sendNotification($user, $type, Product $product, EntityManagerInterface $entityManager): void
  {
    if (!$user instanceof User) {
      throw new \InvalidArgumentException('Expected an instance of User');
    }

    $date = (new \DateTime())->format('Y-m-d H:i');
    $productName = $product->getName();

    switch ($type) {
        case 'edit':
            $action = 'a été modifié';
            break;
        case 'purchase':
            $action = 'a été acheté';
            break;
        case 'delete':
            $action = 'a été supprimé';
            break;
        case 'add':
            $action = 'a été ajouté';
            break;
        default:
            $action = 'a eu une mise à jour';
            break;
    }

        $label = [
    'name' => $productName,
    'message' => $action,
    'date' => $date
    ];

    $notification = new Notification();
    $notification->setLabel($label);
    $notification->setUser($user);
    // $notification->setCreatedAt(new \DateTime());

    $entityManager->persist($notification);
    $entityManager->flush();
  }
}