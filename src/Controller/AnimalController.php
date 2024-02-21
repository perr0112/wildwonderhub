<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal', name: 'app_animal')]
    public function index(animalRepository $animalRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        if (null == $search) {
            $search = '';
        }
        $animals = $animalRepository->search($search);

        return $this->render('animal/index.html.twig', [
            'animals' => $animals,
            'search' => $search
        ]);
    }
}
