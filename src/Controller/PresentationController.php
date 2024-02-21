<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PresentationController extends AbstractController
{
    #[Route('/presentation', name: 'app_presentation')]
    public function index(QuestionRepository $questionRepository): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $areInformationsFilled = !in_array(null, [$user->getPc(), $user->getAddress(), $user->getCity(), $user->getPhone()]);
            if (!$areInformationsFilled) {
                $this->addFlash('warning', 'Vos informations semblent incomplètes, complétez les en accédant à votre profil.');
            }
        }

        // récupération des 3 derniers posts de l'entité Question
        $lastQuestions = $questionRepository->findBy([], ['createdAt' => 'DESC'], 3);
        //dd($lastQuestions);

        return $this->render('presentation/index.html.twig', [
            'questions' => $lastQuestions,
        ]);
    }
}
