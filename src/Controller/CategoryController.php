<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\SearchCategory;
use App\Form\SearchCategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_category_retrieve')]
    public function retrieve(Request $request, CategoryRepository $repository): Response
    {

            $categories=[];
            $form = $this->createForm(SearchCategoryType::class, new SearchCategory());

            $form->handleRequest($request);

            $searchCategory = $form->getData();

            $categories = $repository->findAllFilteredBy($searchCategory);

            return $this->render('category/searchCategory.html.twig', [
                'formView' => $form->createView(),
                'categories' => $categories,
            ]);

    }
}
