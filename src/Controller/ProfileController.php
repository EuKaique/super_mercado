<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $msg = $this
            ->get('session')
            ->getFlashBag()
            ->get('success');

        return $this->render('components/profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'message' => $msg[0] ?? null
        ]);
    }

    /**
     * @Route("/profile_edit", name="profile_edit")
     */
    public function edit(
        UserRepository $userRepository, 
        Request $request): Response
    {
        $user = $this->getUser();

        $phone = $request->request->get('phone') ?? $user->getPhone();
        $image = $request->files->get('update_photo');

        // dd($image);

        if ($phone || $image instanceof UploadedFile) {
            $nameImage = uniqid().'.'.$image->guessExtension();

            $image->move(
                $this->getParameter('profile_images'),
                $nameImage
            );

            $user->setPhone($phone);
            $user->setProfileImage($nameImage);

            $userRepository->add($user, true);

            $this->addFlash('success', 'Usuário atualizado com sucesso!');

            return $this->redirectToRoute('profile');

        }
        
    }
}