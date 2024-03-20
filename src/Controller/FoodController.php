<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class FoodController extends AbstractController
{
    /**
     * @Route("/food", name="food")
     */
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $foodsRepository = $entityManager->getRepository(Food::class);
    
        $page = $request->query->getInt('page', 1);
        $pageSize = 10;
    
        $offset = ($page - 1) * $pageSize;
    
        $foods = $foodsRepository->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();
    
        $totalFoods = $foodsRepository->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
    
        $totalPages = ceil($totalFoods / $pageSize);
    
        return $this->render('components/food/index.html.twig', [
            'controller_name' => 'FoodController',
            'foods' => $foods,
            'page' => $page,
            'totalPages' => $totalPages,
            'message' => $this->get('session')->getFlashBag()->get('success')[0] ?? null
        ]);
    }
    /**
     * @Route("/food_filter", name="food_filter")
     */
    public function filter(Request $request, FoodRepository $foodRepository): Response
    {
        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $page = $request->request->get('page', 1);

        $pageSize = 10;
        $offset = ($page - 1) * $pageSize;

        $foods = $foodRepository->filterTable($nome, $preco, $offset, $pageSize);

        $totalFoods = $foodRepository->filterCount($nome, $preco);
        $totalPages = ceil($totalFoods / $pageSize);

        return $this->render('components/food/index.html.twig', [  
            'controller_name' => 'FoodController',
            'foods' => $foods,
            'page' => $page,
            'totalPages' => $totalPages,
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
