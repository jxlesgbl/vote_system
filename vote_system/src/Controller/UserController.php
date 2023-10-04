<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        // Remplir les données de l'utilisateur à partir du formulaire

        // Générez le token unique
        $token = $user->generateToken();

        // On associe le token aux données de l'utilisateur
        $user->setToken($token);

        $entityManager->persist($user);
        $entityManager->flush();


        // Redirigez l'utilisateur vers une page de confirmation ou une autre page appropriée
        $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant voter.');

        // Redirigez l'utilisateur vers la page de confirmation ou une autre page
        return $this->redirectToRoute('vote');
    }
}
