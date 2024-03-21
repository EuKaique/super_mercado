<?php

namespace App\Controller;

use App\Entity\Homeappliances;
use App\Repository\HomeappliancesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class HomeAppliancesController extends AbstractController
{
    /**
     * @Route("/homeappliances", name="homeappliances")
     */
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $homeappliancesRepository = $entityManager->getRepository(Homeappliances::class);
    
        $page = $request->query->getInt('page', 1);
        $pageSize = 10;
    
        $offset = ($page - 1) * $pageSize;
    
        $homeappliances = $homeappliancesRepository->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();
    
        $totalHomeappliances = $homeappliancesRepository->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
    
        $totalPages = ceil($totalHomeappliances / $pageSize);
    
        return $this->render('components/homeappliances/index.html.twig', [
            'controller_name' => 'HomeAppliancesController',
            'homeAppliances' => $homeappliances,
            'page' => $page,
            'totalPages' => $totalPages,
            'message' => $this->get('session')->getFlashBag()->get('success')[0] ?? null
        ]);
    }
        /**
     * @Route("/homeappliances_filter", name="homeappliances_filter")
     */
    public function filter(Request $request, HomeappliancesRepository $homeappliancesRepository): Response
    {
        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $page = $request->request->get('page', 1);

        $pageSize = 10;
        $offset = ($page - 1) * $pageSize;

        $homeAppliances = $homeappliancesRepository->filterTable($nome, $preco, $offset, $pageSize);

        $totalHomeappliances = $homeappliancesRepository->filterCount($nome, $preco);
        $totalPages = ceil($totalHomeappliances / $pageSize);

        return $this->render('components/food/index.html.twig', [  
            'controller_name' => 'FoodController',
            'homeAppliances' => $homeAppliances,
            'page' => $page,
            'totalPages' => $totalPages,
            'message' => $this->get('session')->getFlashBag()->get('success')[0] ?? null
        ]);
    }
    /**
     * @Route("/homeappliances_form", name="homeappliances_new")
     */
    public function create(): Response
    {
        return $this->render('components/homeappliances/create.html.twig', [
            'controller_name' => 'HomeAppliancesController',
        ]);
    }
    /**
     * @Route("/homeappliances_store", name="homeappliances_store")
     */
    public function store(Request $request, HomeappliancesRepository $homeappliancesRepository): Response
    {

        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $formataPreco = preg_replace("/[^0-9]/", "", $preco);
        $image = $request->files->get('add_image');
        $image instanceof UploadedFile;

        $homeappliances = new Homeappliances();
        
        $nameImage = uniqid().'.'.$image->guessExtension();

        $image->move(
            $this->getParameter('homeappliances_images'),
            $nameImage
        );

        $homeappliances
            ->setNome($nome)
            ->setPreco($formataPreco)
            ->setImagem($nameImage);

        $homeappliancesRepository->add($homeappliances, true);

        return $this->redirectToRoute('homeappliances');
    }
    /**
     * @Route("/homeappliances/{id}", name="homeAppliance_edit")
     */
    public function edit(
        int $id,
        HomeappliancesRepository $homeappliancesRepository,
        Request $request
    ): Response {
        $homeAppliance = $homeappliancesRepository->find($id);
    
        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $formataPreco = preg_replace("/[^0-9]/", "", $preco);
        $image = $request->files->get('add_image');

        if ($image instanceof UploadedFile) {
            $nameImage = uniqid() . '.' . $image->guessExtension();
    
            $image->move(
                $this->getParameter('homeappliances_images'),
                $nameImage
            );

            $homeAppliance->setImagem($nameImage);

        }
    
        if ($nome || $preco) {

            $homeAppliance
                ->setNome($nome)
                ->setPreco($formataPreco);
    
            $homeappliancesRepository->add($homeAppliance, true);
    
            $this->addFlash('success', 'Eletro doméstico atualizado com sucesso!');
    
            return $this->redirectToRoute('homeappliances');
        }
    
        return $this->render('components/homeappliances/edit.html.twig', [
            'controller_name' => 'FoodController',
            'homeAppliance' => $homeAppliance
        ]);
    }
    /**
     * @Route("/homeappliances_delete/{id}", name="homeAppliance_delete")
     */
    public function delete(int $id, HomeappliancesRepository $homeappliancesRepository): Response
    {
        $homeappliancesRepository->remove($homeappliancesRepository->find($id), true);

        $this->addFlash('success', 'Eletro doméstico deletado com sucesso!');

        return $this->redirectToRoute('homeappliances');
    }
}