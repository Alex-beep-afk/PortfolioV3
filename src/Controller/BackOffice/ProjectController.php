<?php

namespace App\Controller\BackOffice;

use App\Entity\Project;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user/project')]
class ProjectController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ){}
    
    #[Route('/', name: 'project.index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        $projects = $this->em->getRepository(Project::class)->findBy(['user' => $user]);

        return $this->render('backoffice/project/index.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/new', name: 'project.new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $project = new Project();

        $project->setUser($this->getUser());

        $form = $this->createForm(ProjectFormType::class, $project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($project);
            $this->em->flush();
            $this->addFlash('success', 'Projet enregistré');
            return $this->redirectToRoute('project.index');
        }
        return $this->render('backOffice/Project/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'project.show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('backOffice/Project/show.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/{id}/edit', name: 'project.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectFormType::class, $project);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Projet modifié');
            return $this->redirectToRoute('project.index');
        }

        return $this->render('backOffice/Project/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/delete', name: 'project.delete', methods: ['POST'])]
    public function delete(Request $request, Project $project): Response
    {
        $this->em->remove($project);
        $this->em->flush();
        $this->addFlash('success', 'Projet supprimé');
        return $this->redirectToRoute('project.index');
    }
}