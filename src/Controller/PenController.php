<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PenController extends AbstractController
{
    #[Route('/pen', name: 'app_pen')]
    public function index(): Response
    {
        return $this->render('pen/index.html.twig', [
            'controller_name' => 'PenController',
        ]);
    }
}
