<?php

namespace App\Controller;

use App\Entity\Eletronics;
use App\Repository\EletronicsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class ElectronicsController extends AbstractController
{
    /**
     * @Route("/electronics", name="electronics")
     */
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $electronicsRepository = $entityManager->getRepository(Eletronics::class);

        $page = $request->query->getInt('page', 1);
        $pageSize = 10;

        $offset = ($page - 1) * $pageSize;

        $electronics = $electronicsRepository->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();

        $totalElectronics = $electronicsRepository->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($totalElectronics / $pageSize);

        return $this->render('components/electronics/index.html.twig', [
            'controller_name' => 'EletronicsController',
            'electronics' => $electronics,
            'page' => $page,
            'totalPages' => $totalPages,
            'message' => $this->get('session')->getFlashBag()->get('success')[0] ?? null
        ]);
    }
    /**
     * @Route("/electronics_filter", name="electronics_filter")
     */
    public function filter(Request $request, EletronicsRepository $electronicsRepository): Response
    {
        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $page = $request->request->get('page', 1);

        $pageSize = 10;
        $offset = ($page - 1) * $pageSize;
        $electronics = $electronicsRepository->filterTable($nome, $preco, $offset, $pageSize);
        $totalElectronics = $electronicsRepository->filterCount($nome, $preco);
        $totalPages = ceil($totalElectronics / $pageSize);

        return $this->render('components/electronics/index.html.twig', [
            'controller_name' => 'EletronicsController',
            'electronics' => $electronics,
            'page' => $page,
            'totalPages' => $totalPages,
            'message' => $this->get('session')->getFlashBag()->get('success')[0] ?? null
        ]);
    }
    /**
     * @Route("/electronics_form", name="electronics_new")
     */
    public function create(): Response
    {
        return $this->render('components/electronics/create.html.twig', [
            'controller_name' => 'ElectronicsController',
        ]);
    }
    /**
     * @Route("*electronics_store", name="electronics_store")
     */
    public function store(Request $request, EletronicsRepository $electronicsRepository): Response
    {
        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $formataPreco = preg_replace('/[^0-9]/', '', $preco);
        $image = $request->files->get('add_image');
        $image instanceof UploadedFile;

        $electronics = new Eletronics();

        $nameImage = uniqid().'.'.$image->guessExtension();

        $image->move(
            $this->getParameter('electronics_images'),
            $nameImage
        );

        $electronics
            ->setNome($nome)
            ->setPreco($formataPreco)
            ->setImagem($nameImage);

        $electronicsRepository->add($electronics, true);

        return $this->redirectToRoute('electronics');
    }
    /**
     * @Route("/electronics_edit/{id}", name="electronics_edit")
     */
    public function edit(
        int $id,
        EletronicsRepository $electronicsRepository,
        Request $request
    ): Response {
        $electronics = $electronicsRepository->find($id);

        $nome = $request->request->get('nome');
        $preco = $request->request->get('preco');
        $formataPreco = preg_replace('/[^0-9]/', '', $preco);
        $image = $request->files->get('add_image');

        if ($image instanceof UploadedFile) {
            $nameImage = uniqid().'.'.$image->guessExtension();
            $image->move(
                $this->getParameter('electronics_images'),
                $nameImage
            );
            $electronics->setImagem($nameImage);
        }

        if ($nome || $preco) {
            $electronics
                ->setNome($nome)
                ->setPreco($formataPreco);

            $electronicsRepository->add($electronics, true);

            $this->addFlash('success', 'Item alterado com sucesso!');

            return $this->redirectToRoute('electronics');
        }

        return $this->render('components/electronics/edit.html.twig', [
            'controller_name' => 'ElectronicsController',
            'electronics' => $electronics
        ]);
    }
    /**
     * @Route("/electronics_delete/{id}", name="electronics_delete")
     */
    public function delete(int $id, EletronicsRepository $electronicsRepository): Response 
    {
        $electronicsRepository->remove($electronicsRepository->find($id), true);

        $this->addFlash('success', 'Item excluído com sucesso!');

        return $this->redirectToRoute('electronics');
    }
}   