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
            'categorys'=>$category
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

    #[Route('category/{id}/suppr' , name: 'app_category_remove')]
    public function remove(int $id, CategoryRepository $repository, EntityManagerInterface $manager): Response
    {
        $category = $repository->find($id);
        if ($category != null) {
            $reponse = new Response("la catégorie à l'id:  $id : " . $category->getName() . " a été supprimer");
            $manager->remove($category);
            $manager->flush();
            return $reponse;
        } else {
            $error = new Response('Oops cette catégorie n\'existe pas');
            $error->setStatusCode(404);
            return $error;
        }
    }

    #[Route('category/{id}/modifier' , name: 'app_category_update')]
    public function update(Request $request, int $id, CategoryRepository $repository, EntityManagerInterface $manager): Response
    {
        $category = $repository->find($id);
        $name = $category->getName();

        if ($request->isMethod('GET')) {
            return $this->render('category/update.html.twig', [
                'name' => $name, 'id' => $id
            ]);
        }

        $newName = $request->request->get('name'); //recup le formualaire de la vue

        $category->setName($newName);

        $manager->persist($category); //met à jour la catégorie sur la bdd
        $manager->flush();

        return $this->redirectToRoute('app_category_list'); //renvoie sur la liste des auteurs après avoir modifier le nouveau
    }
}
