<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category', name:'category.')]
class CategoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

          #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
    
            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Your category has been updated');

            return $this->redirectToRoute('category.index');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

        #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
    

            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Your category has been created');

            return $this->redirectToRoute('category.index');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

        #[Route('/delete/{id}', name: 'delete')]
    public function delete(CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);

        $this->em->remove($category);
        $this->em->flush();

        $this->addFlash('success', 'Your category has been removed');

        return $this->redirectToRoute('category.index');
    }
}
