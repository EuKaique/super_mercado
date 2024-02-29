<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    public function login(): Response
    {
        $data['titulo'] = 'Login';

        return $this->render('auth/login.html.twig', $data);
    }
}