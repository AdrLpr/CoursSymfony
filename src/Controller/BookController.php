<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book/new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('book/new.html.twig'); //envoie sur la vue book
        }

        $book = new Book();

        $title = $request->request->get('title'); //recup le formualaire de la vue
        $price = (float)$request->request->get('price');
        $desc = $request->request->get('description');

        $book->setTitle($title);
        $book->setPrice($price);
        $book->setDescription($desc);

        $manager->persist($book); //envoie le livre sur la bdd
        $manager->flush();

        return $this->redirectToRoute('app_book_livres'); //renvoie sur la liste des livres après avoir ajouter le nouveau
    }
    #[Route('/livres', name: 'app_book_livres')]
    public function list(BookRepository $repository): Response
    {
        $books = $repository->findAll(); // tableau des livres
        $list = "";

        // foreach ($books as $book) {
        //     $id = $book->getId();
        //     $titre = $book->getTitle();
        //     $ligne = "<p> $titre / id: $id </p>";
        //     $list = "$list $ligne";
        // }

        foreach ($books as $book) {
            $list .= "<p>" . $book->getTitle() . " / id: " . $book->getId() . " </p>";
        }

        return new Response("$list");
    }

    #[Route('livres/{id}')]
    public function one(int $id, BookRepository $repository): Response
    {
        $book = $repository->find($id);
        if ($book != null) {
            return new Response("le livre à l'id $id est : " . $book->getTitle());
        } else {
            $error = new Response('Oops ce livre n\'existe pas');
            $error->setStatusCode(404);
            return $error;
        }
    }

    #[Route('livres/{id}/suppr')]
    public function remove(int $id, BookRepository $repository, EntityManagerInterface $manager): Response
    {
        $book = $repository->find($id);
        if ($book != null) {
            $reponse = new Response("le livre à l'id $id : " . $book->getTitle() . "a été supprimer");
            $manager->remove($book);
            $manager->flush();
            return $reponse;
        } else {
            $error = new Response('Oops ce livre n\'existe pas');
            $error->setStatusCode(404);
            return $error;
        }
    }
}
