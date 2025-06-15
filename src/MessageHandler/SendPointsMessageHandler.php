<?php

namespace App\MessageHandler;

use App\Message\SendPointsMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

#[AsMessageHandler]
final class SendPointsMessageHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(SendPointsMessage $message): void
    {
        $activeUsers = $this->entityManager->getRepository(User::class)->findBy(['actif' => true]);

        foreach ($activeUsers as $user) {
            $user->setPoints($user->getPoints() + $message->getPoints());
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
