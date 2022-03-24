<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/', name:'app_book_home')]
    public function home(BookRepository $repository): Response{
        $lastFive = $repository->lastFive();

        return $this->render('book/home.html.twig', [
            'lastFive'=>$lastFive
        ]);
    }

    #[Route('/les-moins-chers', name:'app_book_cheap')]
    public function cheap(BookRepository $repository): Response{
        $FiveCheapest = $repository->FiveCheapest();

        return $this->render('book/cheap.html.twig', [
            'FiveCheapest'=>$FiveCheapest
        ]);
    }

    #[Route('/{id}/livres', name:'app_book_author')]
    public function author(int $id , BookRepository $repository): Response{
        $byAuthor = $repository->byAuthor($id);

        return $this->render('book/byAuthor.html.twig', [
            'byAuthor'=>$byAuthor
        ]);
    }

    #[Route('/rechercher/{title}', name:'app_book_titleSearch')]
    public function titleSearch(string $title , BookRepository $repository): Response{
        $titleSearch = $repository->titleSearch($title);

        return $this->render('book/titleSearch.html.twig', [
            'titleSearch'=>$titleSearch , 'search'=>$title 
        ]);
    }

    #[Route('/rechercher', name:'app_book_search')]
    public function search(Request $request , BookRepository $repository): Response{
        
        $titre = $request->query->get('titre');
        $limit = $request->query->get('limit');
        $page = $request->query->get('page');

        if ($titre == null)
        {
            $titre = " ";
        }
        if ($limit == null)
        {
            $limit = 100;
        }
        if ($page == null)
        {
            $page = 1;
        }

        $search = $repository->search((string)$titre, (int)$limit, (int)$page);

        return $this->render('book/search.html.twig', [
            'search'=>$search, 'titre'=>$titre
        ]);
    }
}
