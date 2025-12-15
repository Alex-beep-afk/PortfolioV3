<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController{

    public function __construct(
    private EntityManagerInterface $em,
    )
    {
    }
    
    #[Route('/', name: 'home')]
    public function index():Response
    {
        return $this->render('frontOffice/home.html.twig');
    }

    #[Route('/portfolio/share/{shareToken}', name: 'portfolio.share')]
    public function portfolioShare(string $shareToken):Response
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['shareToken' => $shareToken]);

        if (!$user) {
            $this->addFlash('error', 'Ce portfolio n\'existe pas ou a été supprimé');
            return $this->redirectToRoute('home');
        }

        return $this->render('frontOffice/portfolio.html.twig', [
            'user' => $user
        ]);
    }
    
}