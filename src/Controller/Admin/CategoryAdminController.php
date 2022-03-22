<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryAdminController extends AbstractController
{
    #[Route('/admin/categories/new' , name:'app_admin_categoryAdmin_create')]
    public function create(EntityManagerInterface $manager, Request $request) : Response
    {
        //creation du livre
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        //enregisterment, persistance du livre
        $manager->persist($category);
        $manager->flush();
        //redirige vers la liste des livres
        return $this->redirectToRoute('app_admin_categoryAdmin_retrieve');
        }

        $formView= $form->createView();

        return $this->render('admin/categoryAdmin/create.html.twig',[
            'formView'=>$formView
        ]);
        
    }

    #[Route('admin/categories', name:'app_admin_categoryAdmin_retrieve')]
    public function retrieve(CategoryRepository $repository): Response
    {
        $categories =$repository->findAll();

        return $this->render('admin/categoryAdmin/retrieve.html.twig', [
         'categories'=>$categories
        ]);
    }

    #[Route('admin/categories/{id}/modifier', name:'app_admin_categoryAdmin_update')]
    public function update(int $id,CategoryRepository $repository,EntityManagerInterface $manager, Request $request): Response
    {
        $category=$repository->find($id);

        if (!$category)
        {
            return new Response("Cette categorie n'existe pas", 404);
        }
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $manager->persist($category);
        $manager->flush();
        return $this->redirectToRoute('app_admin_categoryAdmin_retrieve');
        }
        $formView = $form->createView();

        return $this->render('admin/categoryAdmin/update.html.twig', [
            'formView' => $formView,
        ]);
    }

    #[Route('admin/categories/{id}/supprimer', name:'app_admin_categoryAdmin_delete')]
    public function delete(int $id,CategoryRepository $repository,EntityManagerInterface $manager, Request $request): Response
    {
        $category=$repository->find($id);

        if (!$category)
        {
            return new Response("Cette categorie n'existe pas", 404);
        }

        $manager->remove($category);
        $manager->flush();

        return $this->redirectToRoute('app_admin_categoryAdmin_retrieve');
    }
}
