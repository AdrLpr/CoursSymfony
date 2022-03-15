<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BonjourController
{
    #[Route("bonjour/{nom}", name:'app_bonjour_bonjour', methods:['GET'])]
    public function bonjour(string $nom): Response
    {
        $response = new Response("Bonjour $nom comment vas-tu ?");
        return $response;
    }
}
