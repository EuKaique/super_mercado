<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Super mercado',
        ]);
    }
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        return $this->render('home/dashboard.html.twig', [
            'title' => 'Super mercado',
        ]);
    }
}