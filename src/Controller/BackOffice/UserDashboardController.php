<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserDashboardController extends AbstractController
{
    #[Route('/user/dashboard', name: 'user.dashboard')]
    public function index(): Response
    {
        
        return $this->render('backOffice/userDashboard.html.twig', [
        ]);
    }
}
