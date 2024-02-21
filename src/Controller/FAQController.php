<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FAQController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(QuestionRepository $questionRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $res = $questionRepository->getAllQuestionsOrderByDateDesc();

        // recherche d'une question

        $searchData = $request->query->get('search');
        if ($searchData) {
            $res = $questionRepository->search($searchData);
        }

        // pagination
        $pagination = $paginator->paginate(
            //$questionRepository->getAllQuestionsOrderByDateDesc(),
            $res,
            $request->query->getInt('page', 1)
        );

        //dd($pagination);

        return $this->render('faq/index.html.twig', [
            'user' => $user,
            'pagination' => $pagination,
            'search' => $searchData,
        ]);
    }

}
