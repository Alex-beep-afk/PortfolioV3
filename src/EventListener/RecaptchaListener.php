<?php

namespace App\EventListener;

use App\Services\RecaptchaService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RecaptchaListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly RecaptchaService $recaptchaService,
        private readonly string $recaptchaSiteKey
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        if ($request->isMethod('POST') && $request->getPathInfo() === '/login') {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');
            
            if (!empty($username) && !empty($password)) {
                if (empty($recaptchaResponse) || !$this->recaptchaService->verify($recaptchaResponse, $request->getClientIp())) {
                    // Stocker l'erreur dans la session pour l'afficher dans le template
                    $request->getSession()->getFlashBag()->add('error', 'Veuillez valider le reCAPTCHA');
                    
                    // Rediriger vers la page de login pour afficher l'erreur
                    $response = new \Symfony\Component\HttpFoundation\RedirectResponse($request->getUri());
                    $event->setResponse($response);
                    $event->stopPropagation();
                    return;
                }
            }
        }
    }
}