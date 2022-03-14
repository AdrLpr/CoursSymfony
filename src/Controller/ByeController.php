<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ByeController
{
        #[Route("/bye")]
        public function bye(): Response
        {
            return new Response('Bye :(');
        }
    
}
