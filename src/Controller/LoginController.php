<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        //pegar erro de login
        $error = $authenticationUtils->getLastAuthenticationError();
        //pegar o último email informado
        $lastEmail = $authenticationUtils->getLastUsername();



        return $this->render('login/index.html.twig', [
            'erro' => $erro ?? null,
            'lastEmail' => $lastEmail
        ]);
    }
}
