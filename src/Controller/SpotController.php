<?php

namespace App\Controller;

use App\Entity\Spot;
use App\Repository\SpotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpotController extends AbstractController
{
    #[Route('/spot', name: 'app_spot')]
    public function index(SpotRepository $spotRepository): Response
    {
        $spots = $spotRepository->findBy([], ['name' => 'ASC']);
        return $this->render('spot/index.html.twig', [
            'spots' => $spots,
        ]);
    }

}
