<?php

namespace App\Controller\BackOffice;

use App\Entity\Techno;
use App\Form\TechnoFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/techno')]
final class TechnoController extends AbstractController{

    public function __construct(private EntityManagerInterface $em){
        
    }

    #[Route('/', name: 'techno.index', methods: ['GET'])]
    public function index(): Response{

        $technos = $this->em->getRepository(Techno::class)->findAll();

        return $this->render('backOffice/Techno/index.html.twig',[
            'technos' => $technos
        ]);
    }

    #[Route('/new', name: 'techno.new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response{
        
        $techno = new Techno();
        $form = $this->createForm(TechnoFormType::class, $techno);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($techno);
            $this->em->flush();
            $this->addFlash('success', 'Techno créée avec succès');
            return $this->redirectToRoute('techno.index');
        }

        return $this->render('backOffice/Techno/new.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/edit/{id}', name: 'techno.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Techno $techno): Response{

        $form = $this->createForm(TechnoFormType::class, $techno);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Techno modifiée avec succès');
            return $this->redirectToRoute('techno.index');
        }
        return $this->render('backOffice/Techno/edit.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('delete/{id}', name: 'techno.delete', methods: ['POST'])]
    public function delete(Techno $techno, Request $request): Response{

        if ($this->isCsrfTokenValid('delete' . $techno->getId(), $request->request->get('_token'))) {
            $this->em->remove($techno);
            $this->em->flush();
            $this->addFlash('success', 'Techno supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }
        
        return $this->redirectToRoute('techno.index');
    }
}