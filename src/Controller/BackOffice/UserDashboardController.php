<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
final class UserDashboardController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em,
    private UserPasswordHasherInterface $passwordHasher)
    {
    }

    #[Route('/dashboard', name: 'user.dashboard')]
    public function index(): Response
    {
        
        return $this->render('backOffice/userDashboard.html.twig', [
        ]);
    }

    #[Route('/edit/{id}', name: 'user.edit')]
    public function edit(Request $request , User $user): Response
    {
            $currentUser = $this->getUser();

            if (!$currentUser || $currentUser->getId() !== $user->getId()) {
                $this->addFlash('error', 'Vous ne pouvez pas modifier ce profil');
                return $this->redirectToRoute('login');
            }
            
            $form = $this->createForm(UserType::class, $user, ['register' => false]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Gérer le mot de passe si fourni
                $plainPassword = $form->get('password')->getData();
            if (!empty($plainPassword)) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
            }
            
            $this->em->flush();
            $this->addFlash('success', 'Profil modifié avec succès');
            return $this->redirectToRoute('user.dashboard');
        }
        
        return $this->render('backOffice/User/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/portfolio/show/{id}', name: 'portfolio.show')]
    public function portfolioShow(User $user):Response
    {
       $user = $this->getUser();

       if ($user !== $user->getId() || !$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('login');
       }
       
        return $this->render('frontOffice/portfolio.html.twig', [
            'user' => $user
        ]);
    }
}
