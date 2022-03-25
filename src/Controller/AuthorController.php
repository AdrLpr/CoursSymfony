<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\SearchAuthor;
use App\Form\SearchAuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/authors', name: 'app_author_retrieve')]
    public function retrieve(Request $request, AuthorRepository $repository): Response
    {

            $authors=[];
            $form = $this->createForm(SearchAuthorType::class, new SearchAuthor());

            $form->handleRequest($request);

            $searchAuthor= $form->getData();

            $authors = $repository->findAllFilteredBy($searchAuthor);

            return $this->render('author/searchAuthor.html.twig', [
                'formView' => $form->createView(),
                'authors' => $authors,
            ]);

    }
}

