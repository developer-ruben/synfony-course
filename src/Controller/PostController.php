<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post', name:'post.')]
class PostController extends AbstractController
{
    private $em;
    private $fileUploader;

    public function __construct(EntityManagerInterface $em, FileUploader $fileUploader)
    {
        $this->em = $em;
        $this->fileUploader= $fileUploader;
    }

    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

        #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, PostRepository $postRepository, $id): Response
    {
        $post = $postRepository->find($id);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        
            $file = $form->get('image')->getData();

            if($file){
                if(file_exists($this->fileUploader->getFilePath($post->getImage()))){
                    unlink($this->fileUploader->getFilePath($post->getImage()));
                }
                
                $fileName = $this->fileUploader->uploadFile($file);
                
                $post->setImage($fileName);
            }


            $this->em->flush();

            $this->addFlash('success', 'Your post has been updated');

            return $this->redirectToRoute('post.index');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        
            $file = $form->get('image')->getData();

            if($file){
                $fileName = $this->fileUploader->uploadFile($file);
                $post->setImage($fileName);
            }

            $this->em->persist($post);
            $this->em->flush();

            $this->addFlash('success', 'Your post has been created');

            return $this->redirectToRoute('post.index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(PostRepository $postRepository, $id): Response
    {
        $post = $postRepository->find($id);
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(PostRepository $postRepository, $id): Response
    {
        $post = $postRepository->find($id);


        $this->em->remove($post);
        $this->fileUploader->removeFile($post->getImage());
        $this->em->flush();

        $this->addFlash('success', 'Your post has been removed');

        return $this->redirectToRoute('post.index');
    }


}
