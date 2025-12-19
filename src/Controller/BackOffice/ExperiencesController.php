<?php

namespace App\Controller\BackOffice;


use App\Entity\Experience;
use App\Form\ExperienceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/user/experiences')]

final class ExperiencesController extends AbstractController
{
    
    public function __construct(
    private readonly EntityManagerInterface $em
    ){}

    #[Route('/', name: 'experiences.index' , methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $experiences = $this->em->getRepository(Experience::class)->findBy(['user' => $user]);

        return $this->render('backOffice/Experience/index.html.twig', [
            'experiences' => $experiences,

        ]);
    }
    #[Route('/new', name: 'experiences.new' , methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $experience = new Experience();
        $experience->setUser($user);

        $form = $this->createForm(ExperienceFormType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($experience);
            $this->em->flush();
            $this->addFlash('success', 'Experience créée avec succès');
            return $this->redirectToRoute('experiences.index');
        }

        return $this->render('backOffice/Experience/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'experiences.edit' , methods: ['GET', 'POST'])]
    public function edit(Request $request, Experience $experience): Response
    {
        $user = $this->getUser();
        if ($user !== $experience->getUser()) {
            $this->addFlash('error', 'Cette ressource ne vous appartient pas');
            return $this->redirectToRoute('experiences.index');
        }

        $form = $this->createForm(ExperienceFormType::class, $experience);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Experience modifiée avec succès');
            return $this->redirectToRoute('experiences.index');
        }
        return $this->render('backOffice/Experience/edit.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/{id}/delete', name: 'experiences.delete' , methods: ['POST'])]
    public function delete(Request $request, Experience $experience): Response
    {
        $user = $this->getUser();

        if ($user !== $experience->getUser()) {
            $this->addFlash('error', 'Cette ressource ne vous appartient pas');
            return $this->redirectToRoute('experiences.index');
        }

        if ($this->isCsrfTokenValid('delete' . $experience->getId(), $request->request->get('_token'))) {
            $this->em->remove($experience);
            $this->em->flush();
            $this->addFlash('success', 'Experience supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }
            return $this->redirectToRoute('experiences.index');
    }
}
