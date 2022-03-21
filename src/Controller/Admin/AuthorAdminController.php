<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Author;
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
        if($request->isMethod('GET'))
        {
            return $this->render('admin/authorAdmin/create.html.twig');
        }

        $author = (new Author())
        ->setName($request->request->get('name'))
        ->setDescription($request->request->get('description'))
        ->setPictures($request->request->get('pictures'));

        $manager->persist($author);
        $manager->flush();

        return $this->redirectToRoute('app_admin_authorAdmin_retrieve');
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

        if ($request->isMethod('GET'))
        {
            return $this->render('admin/authorAdmin/update.html.twig',[
                'author'=>$author
            ]);
        }

        $author
        ->setName($request->request->get('name'))
        ->setDescription($request->request->get('description'))
        ->setPictures($request->request->get('pictures'));

        $manager->persist($author);
        $manager->flush();

        return $this->redirectToRoute('app_admin_authorAdmin_retrieve');
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
