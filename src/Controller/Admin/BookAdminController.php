<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookAdminController extends AbstractController

{
    #[Route('/admin/livres/new' , name:'app_admin_bookAdmin_create')]
    public function create(EntityManagerInterface $manager, Request $request) : Response
    {
        
        //creation du livre
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($form->getData());
            $manager->flush();

            return $this->redirectToRoute('app_admin_bookAdmin_retrieve');
        }

        $formView = $form->createView();

        return $this->render('admin/bookAdmin/create.html.twig',[
            'formView'=>$formView
        ]);
    }
    
    #[Route('admin/livres', name:'app_admin_bookAdmin_retrieve')]
    public function retrieve(BookRepository $repository): Response
    {
        //recup tout les livres
        $books= $repository->findAll();

        //affichage 
        return $this->render('admin/bookAdmin/retrieve.html.twig',
        ['books' => $books
    ]);
    }

    #[Route('admin/livres/{id}/modifier', name:'app_admin_bookAdmin_update')]
    public function update(int $id, BookRepository $repository, EntityManagerInterface $manager, Request $request) : Response
    {
        $book = $repository->find($id);

        //si le livre existe pas = 404
        if (!$book)
        {
            return new Response("le livre existe pas", 404);
        }

        //si la methode est GET (obtenir)
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($form->getData());
            $manager->flush();

            return $this->redirectToRoute('app_admin_bookAdmin_retrieve');
        }
        $formView = $form->createView();

        return $this->render('admin/bookAdmin/update.html.twig', [
            'formView' => $formView,
        ]);
    
    }

    #[Route('admin/livres/{id}/supprimer', name:'app_admin_bookAdmin_delete')]
    public function delete(int $id, BookRepository $repository, EntityManagerInterface $manager) : Response
    {
        $book = $repository->find($id);
        //si le livre existe pas = 404
        if (!$book)
        {
            return new Response("le livre existe pas", 404);
        }

        $manager->remove($book);

        $manager->flush();

        //redirige vers la liste des livres
        return $this->redirectToRoute('app_admin_bookAdmin_retrieve');
    }
}

