<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorAdminController extends AbstractController
{
    #[Route('admin/auteurs/new', name:'app_admin_authorAdmin_create')]
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($form->getData());
            $manager->flush();
            return $this->redirectToRoute('app_admin_authorAdmin_retrieve');
        }
        
        $formView= $form->createView();

        return $this->render('admin/authorAdmin/create.html.twig',[
            'formView'=>$formView
        ]);
    }

    #[Route('admin/auteurs', name:'app_admin_authorAdmin_retrieve')]
    public function retrieve(AuthorRepository $repository): Response
    {
        $authors =$repository->findAll();

        return $this->render('admin/authorAdmin/retrieve.html.twig', [
         'authors'=>$authors
        ]);
    }

    #[Route('admin/auteurs/{id}/modifier', name:'app_admin_authorAdmin_update')]
    public function update(int $id,AuthorRepository $repository,EntityManagerInterface $manager, Request $request): Response
    {
        $author=$repository->find($id);

        if (!$author)
        {
            return new Response("Cet auteur n'existe pas", 404);
        }

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($form->getData());
            $manager->flush();

            return $this->redirectToRoute('app_admin_authorAdmin_retrieve');
        }
        $formView = $form->createView();

        return $this->render('admin/authorAdmin/update.html.twig', [
            'formView' => $formView,
        ]);
    }
    
    #[Route('admin/auteurs/{id}/supprimer', name:'app_admin_authorAdmin_delete')]
    public function delete(int $id,AuthorRepository $repository,EntityManagerInterface $manager, Request $request): Response
    {
        $author=$repository->find($id);

        if (!$author)
        {
            return new Response("Cet auteur n'existe pas", 404);
        }

        $manager->remove($author);
        $manager->flush();

        return $this->redirectToRoute('app_admin_authorAdmin_retrieve');
    }
}
