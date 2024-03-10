<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ElectronicsController extends AbstractController
{
    /**
     * @Route("/electronics", name="electronics")
     */
    public function index(): Response
    {
        return $this->render('components/electronics/index.html.twig', [
            'controller_name' => 'EletronicsController',
        ]);
    }
}