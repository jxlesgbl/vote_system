<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
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
    #[Route('/', name: 'index_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            // Enregistrer le sondage en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une page de succès ou ailleurs
            return $this->redirectToRoute('sondages_actifs');
        }

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
