<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeAppliancesController extends AbstractController
{
    /**
     * @Route("/homeappliances", name="homeappliances")
     */
    public function index(): Response
    {
        return $this->render('components/homeappliances/index.html.twig', [
            'controller_name' => 'HomeAppliancesController',
        ]);
    }
}