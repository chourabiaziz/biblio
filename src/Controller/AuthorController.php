<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(Request $request , AuthorRepository $authorRepository, ManagerRegistry $registry): Response
    {
        $id = $request->get('search');
        $author = $authorRepository->findById($id);

        $min = $request->get('min');;
        $max = $request->get('max');
        


        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->listAuthorByEmail(),
            'author'=> $author,
            'authors2'=>$authorRepository->findAuthorsByBookCountRange($min,$max),
            'delete'=>$authorRepository->delete()
        ]);
    }


    #[Route('/author/new', name: 'app_author_new')]
    public function new(Request $request , ManagerRegistry $mr): Response
    {
        $author = new Author();
        $em = $mr->getManager();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_author');

        }

        return $this->render('author/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    public function edit(Request $request , ManagerRegistry $mr , AuthorRepository $authorRepository ): Response
    {
        $author = $authorRepository->find($request->get('id'));
         $em = $mr->getManager();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_author');

        }

        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function delete(Request $request , ManagerRegistry $mr , AuthorRepository $authorRepository ): Response
    {
        $author = $authorRepository->find($request->get('id'));
         $em = $mr->getManager();
             $em->remove($author);
            $em->flush();
         return $this->redirectToRoute('app_author');

        
    }
}
