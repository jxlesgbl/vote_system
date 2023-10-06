<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    #[Route('/vote/{sondageId}', name: 'vote')]
    public function vote(Request $request, EntityManagerInterface $em, User $user, $sondageId): Response
    {
        $sondage = $em->getRepository(Sondage::class)->find($sondageId);

        if (!$sondage) {
            throw $this->createNotFoundException('Sondage non trouvé.');
        }

        // Vérifiez si l'utilisateur a déjà voté dans ce sondage.
        $utilisateur = $this->getUser();
        $voted = $sondage->isVotant($utilisateur);

        if ($voted) {
            // Redirigez l'utilisateur ou affichez un message d'erreur pour l'informer qu'il a déjà voté.
            return $this->redirectToRoute('sondages_actifs');
        }

        // Récupérez le token fourni par l'utilisateur depuis le formulaire.
        $tokenFromUser = $request->request->get('token');

        // Vérifiez si le token correspond à celui du sondage.
        if ($tokenFromUser) {
            // Le token de l'utilisateur ne correspond pas au token du sondage.
            // Vous pouvez afficher un message d'erreur ou rediriger l'utilisateur.
            return $this->redirectToRoute('sondages_actifs', [
                'error' => 'Le token est incorrect. Veuillez réessayer.'
            ]);
        }

        // Récupérez les réponses sélectionnées par l'utilisateur depuis le formulaire.
        $reponsesIds[] = $request->request->get('reponses');

        // Traitez le vote ici, enregistrez les réponses dans la base de données.
        foreach ($reponsesIds as $reponseId) {
            $reponse = $em->getRepository(Reponse::class)->find($reponseId);

            if ($reponse) {
                // Associez la réponse au sondage.
                $sondage->addReponse($reponse);

                // Enregistrez le vote dans la base de données.
                $em->persist($sondage);
            }
        }

        // Marquez l'utilisateur comme ayant voté dans ce sondage.
        $sondage->addVotant($utilisateur);

        // Enregistrez les modifications dans la base de données.
        $em->flush();
        return $this->redirectToRoute('sondages_actifs');
    }
}
