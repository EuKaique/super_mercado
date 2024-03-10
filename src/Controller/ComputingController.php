<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComputingController extends AbstractController
{
    /**
     * @Route("/computing", name="computing")
     */
    public function index(): Response
    {
        return $this->render('components/computing/index.html.twig', [
            'controller_name' => 'ComputingController',
        ]);
    }
}