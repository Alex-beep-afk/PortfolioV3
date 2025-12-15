<?php

namespace App\Controller\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/user/share')]
class ShareController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/regenerate', name: 'share.regenerate', methods: ['POST'])]
    public function regenerateToken(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour générer un nouveau lien de partage');
            return $this->redirectToRoute('login');
        }
        
        // Régénère un nouveau token (invalide automatiquement l'ancien)
        $user->autoGenerateShareToken();

        $this->em->flush();
        
        $this->addFlash('success', 'Nouveau lien de partage généré. L\'ancien lien n\'est plus valide.');
        
        return $this->redirectToRoute('user.dashboard');
    }
}