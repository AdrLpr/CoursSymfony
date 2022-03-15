<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatriceController
{
    #[Route("/add/{x}/{y}", name:"app_calculatrice_addition", methods:['GET'])]
    
    public function addition(int $x, int $y): Response
    {
        $result = $x+$y;
        $response = new Response("$x + $y = $result");
        return $response;
    }

    #[Route("/sous/{x}/{y}", name:"app_calculatrice_sousctration", methods:['GET'])]
    public function sousctration(int $x, int $y): Response
    {
        $result = $x-$y;
        $response = new Response("$x - $y = $result");
        return $response;
    }

    #[Route("/mult/{x}/{y}", name:"app_calculatrice_mutliplication", methods:['GET'])]
    public function multiplication(int $x, int $y): Response
    {
        $result = $x*$y;
        $response = new Response("$x x $y = $result");
        return $response;
    }
    
    #[Route("/divi/{x}/{y}", name:"app_calculatrice_diviser", methods:['GET'])]
    public function diviser(Request $request, int $x, int $y): Response
    {
        $precision = $request->query->get('precision');
        $resultat = $x / $y;
        if ($precision == null)
        {
        $response = new Response("$x / $y = $resultat");
        }else
        {
        $resultat2 = round($resultat, (int)$precision);
        $response = new Response("$x / $y = $resultat2");
        }
        return ($response);
    }

    #[Route("/calcul/{x}/{y}", name:"app_calculatrice_cacul", methods:['GET'])]
    public function calcul(int $x, int $y, Request $request): Response
    {

        $operation = $request->headers->get('operation');
        switch ($operation)
        {
            case 'add':  
                $result = $x+$y;
                $op='+';
                break;

            case 'sous':
            
                $result = $x-$y;
                $op = '-';
                break;
            

            case 'multi':
            
                $result = $x*$y;
                $op = 'x';
                break;
            

            case 'sivi':
            
                $result = $x/$y;
                $op = '/';
                break;

            default:
            $error = new Response("erreur");
            $error->setStatusCode(400);
            return $error;
        }

        $response = new Response("$x $op $y = $result");
        return $response;
    }
}
