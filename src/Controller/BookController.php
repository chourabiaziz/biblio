<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]

    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('book/index.html.twig', [
            'books'=> $books
        ]);
    }


    #[Route('/book/new', name: 'app_book_new')]
    public function new(Request $request , ManagerRegistry $mr): Response
    {
        $author = new Book();
        $em = $mr->getManager();
        $form = $this->createForm(BookType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_book');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/book/edit/{id}', name: 'app_book_edit')]
    public function edit(Request $request , ManagerRegistry $mr , BookRepository $authorRepository ): Response
    {
        $author = $authorRepository->find($request->get('id'));
         $em = $mr->getManager();
        $form = $this->createForm(BookType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/book/delete/{id}', name: 'app_book_delete')]
    public function delete(Request $request , ManagerRegistry $mr , BookRepository $authorRepository ): Response
    {
        $author = $authorRepository->find($request->get('id'));
         $em = $mr->getManager();
             $em->remove($author);
            $em->flush();
         return $this->redirectToRoute('app_book');

        
    }
}
