<?php

namespace App\Controller\BackOffice;


use App\Entity\Diplomas;
use App\Form\DiplomasFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DiplomasController extends AbstractController
{
    
    public function __construct(
    private readonly EntityManagerInterface $em
    ){}

    #[Route('/user/diplomas', name: 'diplomas.index')]
    public function index(): Response
    {
        $user = $this->getUser();
        $diplomas = $this->em->getRepository(Diplomas::class)->findBy(['user' => $user]);

        return $this->render('backOffice/Diplomas/index.html.twig', [
            'diplomas' => $diplomas,

        ]);
    }
    #[Route('/user/diplomas/new', name: 'diplomas.new')]
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $diploma = new Diplomas();
        $diploma->setUser($user);

        $form = $this->createForm(DiplomasFormType::class, $diploma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($diploma);
            $this->em->flush();
            $this->addFlash('success', 'Diplôme créé avec succès');
            return $this->redirectToRoute('diplomas.index');
        }

        return $this->render('backOffice/Diplomas/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/user/diplomas/{id}/edit', name: 'diplomas.edit')]
    public function edit(Request $request, Diplomas $diploma): Response
    {
        $user = $this->getUser();
        if ($user !== $diploma->getUser()) {
            $this->addFlash('error', 'Cette ressource ne vous appartient pas');
            return $this->redirectToRoute('diplomas.index');
        }

        $form = $this->createForm(DiplomasFormType::class, $diploma);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Diplôme modifié avec succès');
            return $this->redirectToRoute('diplomas.index');
        }
        return $this->render('backOffice/Diplomas/edit.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/user/diplomas/{id}/delete', name: 'diplomas.delete')]
    public function delete(Request $request, Diplomas $diploma): Response
    {
        $user = $this->getUser();
        if ($user !== $diploma->getUser()) {
            $this->addFlash('error', 'Cette ressource ne vous appartient pas');
            return $this->redirectToRoute('diplomas.index');
        }

        if ($this->isCsrfTokenValid('delete' . $diploma->getId(), $request->request->get('_token'))) {
            $this->em->remove($diploma);
            $this->em->flush();
            $this->addFlash('success', 'Diplôme supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }
            return $this->redirectToRoute('diplomas.index');
    }
}
