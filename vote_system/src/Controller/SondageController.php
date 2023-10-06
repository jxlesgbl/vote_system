<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Sondage;
use App\Form\SondageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SondageController extends AbstractController
{
    #[Route('/sondages-actif', name: 'sondages_actifs')]
    public function sondagesActifs(EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Sondage::class);
        $sondagesActifs = $repository->findBy(['actif' => true]);

        return $this->render('sondage/sondages_actifs.html.twig', [
            'sondages' => $sondagesActifs,
        ]);
    }

    #[Route('/sondage/{sondageId}', name: 'sondage')]
    public function showSondage(Request $request, EntityManagerInterface $em, $sondageId): Response
    {
        $sondage = $em->getRepository(Sondage::class)->find($sondageId);

        if (!$sondage) {
            throw $this->createNotFoundException('Sondage non trouvé.');
        }

        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser Symfony's Security component).
        $user = $this->getUser();

        // Vérifiez si l'utilisateur a déjà participé à ce sondage.
        if ($sondage->isVotant($user)) {
            return $this->redirectToRoute('sondages_actifs', [
                'error' => 'Vous avez déjà participé à ce sondage.'
            ]);
        }

        return $this->render('sondage/sondage.html.twig', [
            'sondage' => $sondage,
        ]);
    }

    #[Route('/sondage-new', name: 'create_sondage')]
    public function newSondage(Request $request, EntityManagerInterface $entityManager)
    {
        $sondage = new Sondage();
        // $question = $sondage->getQuestion();
        // $sondage->setQuestion($question);

        // if($question){
        //     $answers = $question->getAnswers();
        // }
        // Créer un formulaire pour la création du sondage (vous devrez créer un FormType pour cela)
        $form = $this->createForm(SondageType::class, $sondage);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le sondage en base de données
            $entityManager->persist($sondage);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une page de succès ou ailleurs
            return $this->redirectToRoute('sondages_actifs');
        }

        // Afficher le formulaire dans le template Twig
        return $this->render('sondage/sondage_new.html.twig', [
            'form' => $form->createView(),
            'sondage' => $sondage
        ]);
    }
}
