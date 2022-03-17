<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{

    #[Route('/creer-livre')]
    public function createBook(EntityManagerInterface $manager): Response
    {
        $book = new Book();
        $book->setTitle('Mon second livre');
        $book->setPrice(9.60);
        $book->setDescription('Description du livre');

        /*On demande au manager de prendre en compte le nouveau livre
        il n'engengistre pas directement dans la base
        */
        $manager->persist($book);

        //manager met à jour la base de donnée
        $manager->flush();

        return new Response('le nouveau livre a été crée :' . $book->getTitle());
    }

    #[Route("/hello/{nom}"/* {paramètre dynamique} */, name: 'app_hello_hello'/* app + nom du comtroleur + nom de la fonction */, methods: ['GET'] /*les methodes autorisés */)]
    public function hello(Request $request, string $nom): Response
    {

        //Création d'un objet response correspodant au fichier text de la réponse
        $response = new Response("Hello $nom :)");


        //Changement du status code de la réponse
        $response->setStatusCode(200);

        //Affiche la méthode HTTP que le client utilise
        $request->getMethod(); //GET

        // pour ecrire sur une page html
        return $this->render('hello/hello.html.twig', [
            'nom' => $nom
        ]);
    }
}
