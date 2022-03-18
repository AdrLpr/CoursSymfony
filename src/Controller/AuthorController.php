<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/auteurs', name: 'app_author_list')]
    public function list(AuthorRepository $repository): Response
    {
        $authors = $repository->findAll(); // tableau des auteurs

        return $this->render('author/list.html.twig', [
            'authors'=>$authors
        ]);
    }
    
    #[Route('/auteurs/new', name: 'app_author_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('author/new.html.twig'); //envoie sur la vue author
        }

        $author = new Author();

        $name = $request->request->get('name'); //recup le formulaire de la vue
        $img = $request->request->get('image');
        $desc = $request->request->get('description');

        $author->setName($name);
        $author->setImage($img);
        $author->setDescription($desc);

        $manager->persist($author); //envoie l'auteur' sur la bdd
        $manager->flush();

        return $this->redirectToRoute('app_author_list'); //renvoie sur la liste des auteurs après avoir ajouter le nouveau
    }

    #[Route('auteurs/{id}/suppr' , name: 'app_author_remove')]
    public function remove(int $id, AuthorRepository $repository, EntityManagerInterface $manager): Response
    {
        $author = $repository->find($id);
        if ($author != null) {
            $reponse = new Response("l'auteur à l'id:  $id : " . $author->getName() . " a été supprimer");
            $manager->remove($author);
            $manager->flush();
            return $reponse;
        } else {
            $error = new Response('Oops cet auteur n\'existe pas');
            $error->setStatusCode(404);
            return $error;
        }
    }

    #[Route('auteurs/{id}/modifier' , name: 'app_author_update')]
    public function update(Request $request, int $id, AuthorRepository $repository, EntityManagerInterface $manager): Response
    {
        $author = $repository->find($id);
        $name = $author->getName();
        $img = $author->getImage();
        $desc = $author->getDescription();

        if ($request->isMethod('GET')) {
            return $this->render('author/update.html.twig', [
                'name' => $name, 'img' => $img, 'desc' => $desc, 'id' => $id
            ]);
        }

        $newName = $request->request->get('name'); //recup le formualaire de la vue
        $newImg = $request->request->get('image');
        $newDesc = $request->request->get('description');

        $author->setName($newName);
        $author->setImage($newImg);
        $author->setDescription($newDesc);

        $manager->persist($author); //met à jour l'auteur sur la bdd
        $manager->flush();

        return $this->redirectToRoute('app_author_list'); //renvoie sur la liste des auteurs après avoir modifier le nouveau
    }
}
