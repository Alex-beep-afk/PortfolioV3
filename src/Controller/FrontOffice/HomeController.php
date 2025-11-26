<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController{

    public function __construct(
    private EntityManagerInterface $entityManager
    )
    {
    }
    
    #[Route('/', name: 'home')]
    public function index():Response
    {
        return $this->render('frontOffice/home.html.twig');
    }
    #[Route('/portfolio/show/{id}', name: 'portfolio.show')]
    public function portfolioShow(User $user):Response
    {
       
        return $this->render('frontOffice/portfolio.html.twig', [
            'user' => $user
        ]);
    }
}