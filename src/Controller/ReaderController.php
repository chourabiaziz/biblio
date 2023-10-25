<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\ReaderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReaderController extends AbstractController
{
    #[Route('/reader', name: 'app_reader')]
    public function index( ReaderRepository $authorRepository, ManagerRegistry $registry): Response
    {
        
        return $this->render('reader/index.html.twig', [
            'readers' => $authorRepository->findAll(),
        ]);
    }


    #[Route('/reader/new', name: 'app_reader_new')]
    public function new(Request $request , ManagerRegistry $mr): Response
    {
        $author = new Reader();
        $em = $mr->getManager();
        $form = $this->createForm(ReaderType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_reader');

        }

        return $this->render('reader/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reader/edit/{id}', name: 'app_reader_edit')]
    public function edit(Request $request , ManagerRegistry $mr , ReaderRepository $authorRepository ): Response
    {
        $author = $authorRepository->find($request->get('id'));
         $em = $mr->getManager();
        $form = $this->createForm(ReaderType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_reader');

        }

        return $this->render('reader/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/reader/delete/{id}', name: 'app_author_delete')]
    public function delete(Request $request , ManagerRegistry $mr , ReaderRepository $authorRepository ): Response
    {
        $author = $authorRepository->find($request->get('id'));
         $em = $mr->getManager();
             $em->remove($author);
            $em->flush();
         return $this->redirectToRoute('app_reader');

        
    }
}
