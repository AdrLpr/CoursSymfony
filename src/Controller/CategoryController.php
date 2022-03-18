<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route("/category" , name:'app_category_list')]
    public function list(CategoryRepository $repository): Response
    {
        $category=$repository->findAll(); 

        return $this->render('category/list.html.twig', [
            'category'=>$category
        ]);
    }
    
    #[Route('/category/new', name: 'app_category_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('category/new.html.twig'); //envoie sur la vue category
        }

        $category = new Category();

        $name = $request->request->get('name'); //recup le formulaire de la vue

        $category->setName($name);

        $manager->persist($category); //envoie la category sur la bdd
        $manager->flush();

        return $this->redirectToRoute('app_category_list'); //renvoie sur la liste des category après avoir ajouter le nouveau
    }
}
