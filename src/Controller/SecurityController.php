<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Form\SignInType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/inscription', name: 'app_inscription')]
    public function signIn(UserRepository $repository ,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager ,Request $request)
    {
        $form = $this->createForm(SignInType::class);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $plainpassword= $user->getPassword();
            
            $hassedpassword = $passwordHasher->hashPassword(
                $user,
                $plainpassword
            );

            $user->setPassword($hassedpassword);

            $repository->add($user);

            return $this->redirectToRoute('app_login');
        }
        $formView = $form->createView();
        return $this->render('security/signIn.html.twig', [
            'formView' => $formView,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/mon-profil', name:'app_profil')]
    public function profil(UserRepository $repository,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager ,Request $request ) : Response
    {    

        $form = $this->createForm(ProfilType::class, $this->getUser() );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $plainpassword= $user->getPassword();
            
            $hassedpassword = $passwordHasher->hashPassword(
                $user,
                $plainpassword
            );

            $user->setPassword($hassedpassword);

            $repository->add($user);

            return $this->redirectToRoute('app_login');
        }

        $formView = $form->createView();
        return $this->render('security/profile.html.twig', [
            'formView' => $formView
        ]);
    }
}
