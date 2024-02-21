<?php

namespace App\Controller;

use App\Repository\FamilyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FamilyController extends AbstractController
{
    #[Route('/family', name: 'app_family')]
    public function index(familyRepository $familyRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        if (null == $search) {
            $search = '';
        }
        $families = $familyRepository->search($search);

        return $this->render('family/index.html.twig', [
            'families' => $families,
            'search' => $search
        ]);
    }
}
