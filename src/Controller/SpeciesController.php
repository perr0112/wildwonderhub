<?php

namespace App\Controller;

use App\Repository\SpeciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpeciesController extends AbstractController
{
    #[Route('/species', name: 'app_species')]
    public function index(speciesRepository $speciesRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        if (null == $search) {
            $search = '';
        }
        $species = $speciesRepository->search($search);
        return $this->render('species/index.html.twig', [
            'species' => $species,
            'search' => $search
        ]);
    }
}
