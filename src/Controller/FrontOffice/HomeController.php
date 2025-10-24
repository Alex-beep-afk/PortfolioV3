<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

final class HomeController extends AbstractController{
    #[Route('/', name: 'home')]
    public function index():Response
    {
        return $this->render('frontOffice/home.html.twig');
    }
}