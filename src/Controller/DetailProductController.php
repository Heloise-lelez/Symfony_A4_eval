<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\NotificationService;

final class DetailProductController extends AbstractController
{
  #[Route('/detail/product/{id}', name: 'app_detail_product')]
public function index(string $id, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    $product = $entityManager->getRepository(Product::class)->find($id);
    $admins = $entityManager->getRepository(User::class)->findBy(['roles' => 'ROLE_ADMIN']);

    // Extraction sécurisée des infos utiles pour debug
    $adminsArray = array_map(function (User $admin) {
        return [
            'id' => $admin->getId(),
            'email' => $admin->getEmail(),
            'name' => $admin->getName(), // ou getUsername() si tu n'as pas de champ name
        ];
    }, $admins);

    return $this->render('detail_product/index.html.twig', [
        'product' => $product,
        'user' => $user,
        'admins' => $adminsArray,
    ]);
}


     #[Route('/detail/product/{id}/buy', name: 'app_detail_buy_product', methods: ['POST'])]
    public function buyProduct(int $id, EntityManagerInterface $entityManager, NotificationService $notification): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        $user = $this->getUser();
        

        $user->setPoints($user->getPoints() - $product->getPrice()); 

        $notification -> sendNotification(
            $user,
            'purchase',
            $product,
            $entityManager
        );
        $entityManager->flush();

    return $this->redirectToRoute('app_detail_product', ['id' => $id]);
}
}
