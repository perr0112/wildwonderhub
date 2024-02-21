<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/question/{id}/like', name: 'app_question_like', requirements: ['id' => '\d+'])]
    public function like(Question $question, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        if ($question->isLikedByUser($user)) {
            $question->removeLike($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre j\'aime a bien été supprimé de ce post!');
        } else {
            $question->addLike($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez aimé ce post!');
        }
        return $this->redirectToRoute('app_question_show', ['id' => $question->getId()]);
    }
}
