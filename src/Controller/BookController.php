<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]

    public function index(Request $request,BookRepository $bookRepository , EntityManagerInterface $em): Response
    {
        $books = $bookRepository->findAll();
        
        
 




            $nb = $bookRepository->nb_books();
 
        return $this->render('book/index.html.twig', [
            'books'=> $books ,
            'books2'=> $bookRepository->booksListByAuthors(),
            'books3'=> $bookRepository->findBooksBeforeYearWithAuthorHavingMoreThanTenBooks(),
            'nb'=> $nb,
            'books4'=> $bookRepository->findBooksPublishedBetweenDates(),
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
