<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\User;
use App\Form\AnswerType;
use App\Form\DeleteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/answer', name: 'app_answer')]
    public function index(): Response
    {
        return $this->render('answer/index.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/answer/{id}/edit', name: 'app_answer_edit', requirements: ['id' => '\d+'])]
    public function edit(Answer $answer, Request $request, EntityManagerInterface $entityManager): Response {

        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        } else if ($answer->getAuthor() !== $user && !($user->isAdmin()) && (!$user->isEmployee())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour modifier cette réponse.');
            return $this->redirectToRoute('app_forum');
        }

        // formulaire d'édition d'une réponse

        $formEdit = $this->createForm(AnswerType::class, $answer, [
            'attr' => ['class' => 'answer-form']
        ])
            ->add('submit', SubmitType::class, ['label' => 'Modifier la réponse', 'attr' => ['class' => 'btn button-primary full-width mt-50']]);

        $formEdit->handleRequest($request);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $answer->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));

            $entityManager->persist($answer);
            $entityManager->flush();

            $this->addFlash('success', 'La réponse a bien été modifiée.');
            return $this->redirectToRoute('app_question_show', ['id' => $answer->getQuestion()->getId()]);
        }

        // formulaire de suppression d'une réponse

        $formDelete = $this->createForm(DeleteType::class, null, [
            'attr' => ['class' => 'delete-form'],
        ]);

        $formDelete->handleRequest($request);

        if ($formDelete->isSubmitted()) {
            if ($formDelete->get('delete')->isClicked()) {
                // suppression de la réponse
                $entityManager->remove($answer);
                $entityManager->flush();

                $this->addFlash('success', 'Cette réponse a bien été supprimée!');

                return $this->redirectToRoute('app_question_show', ['id' => $answer->getQuestion()->getId()]);
            } else {
                return $this->redirectToRoute('app_answer_edit', ['id' => $answer->getId()]);
            }
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'formEdit' => $formEdit,
            'formDelete' => $formDelete
        ]);
    }

}
