<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class FoodController extends AbstractController
{
    /**
     * @Route("/food", name="food")
     */
    public function index(): Response
    {
        return $this->render('components/food/index.html.twig', [
            'controller_name' => 'FoodController',
            'foods' => $this->getDoctrine()->getRepository(Food::class)->findAll(),
            'message' => $this->get('session')->getFlashBag()->get('success')[0] ?? null
        ]);
    }
    /**
     * @Route("/food_form", name="food_new")
     */
    public function create(): Response
    {
        return $this->render('components/food/create.html.twig', [
            'controller_name' => 'FoodController',
        ]);
    }
    /**
     * @Route("/food_store", name="food_store")
     */
    public function store(Request $request, FoodRepository $foodRepository): Response
    {

        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $image = $request->files->get('add_image');
        $image instanceof UploadedFile;

        $food = new Food();
        
        $nameImage = uniqid().'.'.$image->guessExtension();

        $image->move(
            $this->getParameter('food_images'),
            $nameImage
        );

        $food
            ->setNome($nome)
            ->setPreco($preco)
            ->setImagem($nameImage);

        $foodRepository->add($food, true);

        return $this->redirectToRoute('food');
    }
    /**
     * @Route("/food/{id}", name="food_edit")
     */
    public function edit(
        int $id,
        FoodRepository $foodRepository,
        Request $request
    ): Response {
        $food = $foodRepository->find($id);
    
        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $image = $request->files->get('add_image');

        if ($image instanceof UploadedFile) {
            $nameImage = uniqid() . '.' . $image->guessExtension();
    
            $image->move(
                $this->getParameter('food_images'),
                $nameImage
            );

            $food->setImagem($nameImage);

        }
    
        if ($nome || $preco) {

            $food
                ->setNome($nome)
                ->setPreco($preco);
    
            $foodRepository->add($food, true);
    
            $this->addFlash('success', 'Alimento atualizado com sucesso!');
    
            return $this->redirectToRoute('food');
        }
    
        return $this->render('components/food/edit.html.twig', [
            'controller_name' => 'FoodController',
            'food' => $food
        ]);
    }
    /**
     * @Route("/food_delete/{id}", name="food_delete")
     */
    public function delete(int $id, FoodRepository $foodRepository): Response
    {
        $foodRepository->remove($foodRepository->find($id), true);

        $this->addFlash('success', 'Alimento deletado com sucesso!');

        return $this->redirectToRoute('food');
    }
}
