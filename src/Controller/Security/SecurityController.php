<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Services\RecaptchaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly RecaptchaService $recaptchaService,
        private readonly string $recaptchaSiteKey,
        private readonly EntityManagerInterface $em , 
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        
        $error = $authenticationUtils->GetLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $response = $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'recaptcha_site_key' => $this->recaptchaSiteKey,
        ]);

        // Si erreur d'authentification, utiliser le code 422 pour Turbo
        if($request->isMethod('POST') && $error){
            $response->setStatusCode(422);
        }

        return $response;
        
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request $request
        ):Response{
        
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $recaptchaError = null;

        if($form->isSubmitted() && $form->isValid()) {
            // Ne vérifier le reCAPTCHA que si le formulaire est valide
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            
            if(!$recaptchaResponse || !$this->recaptchaService->verify($recaptchaResponse, $request->getClientIp())){
               $this->addFlash('error', 'Veuillez valider le captcha.');
            } else {

                // formulaire valide et recaptcha valide
                $password = $form->get('password')->getData();
                $user->setPassword($this->passwordHasher->hashPassword($user, $password));
                $this->em->persist($user);
                $this->em->flush();
                
                // Connecter automatiquement l'utilisateur
                $token = new UsernamePasswordToken(
                    $user,
                    'main',
                    $user->getRoles()
                );
                $this->container->get('security.token_storage')->setToken($token);
                $request->getSession()->set('_security_main', serialize($token));


                $this->addFlash('success', 'Votre compte a été créé avec succès. Bienvenue sur votre dashboard !');
                return $this->redirectToRoute('user.dashboard');
            }
        }

        $response = $this->render('security/register.html.twig', [
            'form' => $form,
            'recaptcha_site_key' => $this->recaptchaSiteKey,
            'recaptcha_error' => $recaptchaError,
        ]);

        if($form->isSubmitted() && (!$form->isValid() || $recaptchaError)){
            $response->setStatusCode(422);
        }

        return $response;
        
    }
}
