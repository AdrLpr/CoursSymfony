<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    #[Route("/hello/{nom}"/* {paramètre dynamique} */ , name : 'app_hello_hello'/* app + nom du comtroleur + nom de la fonction */, methods:['GET'] /*les methodes autorisés */)]
    public function hello(Request $request, string $nom): Response {

        //Création d'un objet response correspodant au fichier text de la réponse
        $response = new Response("Hello $nom :)");


        //Changement du status code de la réponse
        $response->setStatusCode(200);

        //Affiche la méthode HTTP que le client utilise
        $request->getMethod(); //GET


        return $response;
    }
}