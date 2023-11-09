<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authen): Response
    {
        // message d'erreur en cas d'info erroner
        $erro=$authen->getLastAuthenticationError();
        // sauvegarde temporaire du nom de l'utilisateur
        $lastusername=$authen->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'page de connexion',
            'last'=>$lastusername,
            'erreur'=>$erro,
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        
        return new Response('ok');
    }
}
