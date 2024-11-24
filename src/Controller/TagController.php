<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Tag;
use App\Form\CategoryType;
use App\Form\TagType;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tag', name:'tag.')]
class TagController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'index')]
    public function index(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

          #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, TagRepository $tagRepository, $id): Response
    {
        $tag = $tagRepository->find($id);

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
    
            $this->em->persist($tag);
            $this->em->flush();

            $this->addFlash('success', 'Your tag has been updated');

            return $this->redirectToRoute('tag.index');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
    

            $this->em->persist($tag);
            $this->em->flush();

            $this->addFlash('success', 'Your tag has been created');

            return $this->redirectToRoute('tag.index');
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

        #[Route('/delete/{id}', name: 'delete')]
    public function delete(CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);

        $posts = $category->getPosts();
        foreach ($posts as $post) {
            $post->setCategory(null);
        }

        $this->em->remove($category);
        $this->em->flush();

        $this->addFlash('success', 'Your category has been removed');

        return $this->redirectToRoute('category.index');
    }
}
