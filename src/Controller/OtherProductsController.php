<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OtherProductsController extends AbstractController
{
    /**
     * @Route("/otherproducts", name="otherproducts")
     */
    public function index(): Response
    {
        return $this->render('components/other/index.html.twig', [
            'controller_name' => 'OtherProductsController',
        ]);
    }
}