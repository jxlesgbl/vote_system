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
    #[Route('/', name: 'index_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        // Remplir les données de l'utilisateur à partir du formulaire

        $form = $this->createForm(UserType::class, $user);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Générez le token unique
            $token = $user->generateToken();

            // On associe le token aux données de l'utilisateur
            $user->setToken($token);

            // Enregistrer le sondage en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une page de succès ou ailleurs
            return $this->redirectToRoute('sondages_actifs');
        }

        return $this->render('user/form_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
