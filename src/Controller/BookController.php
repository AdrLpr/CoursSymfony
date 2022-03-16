<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book/new')]
    public function new(EntityManagerInterface $manager): Response
    {
        $book = new Book();

        $book->setTitle('Pire livre!! ');
        $book->setPrice(0.2);
        $book->setDescription('à supprimer!!!!');

        $manager->persist($book);
        $manager->flush();

        return new Response("Le livre : " . $book->getTitle() . 'à l\'id : ' . $book->getId() . ' est en ligne');
    }
    #[Route('/livres')]
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
