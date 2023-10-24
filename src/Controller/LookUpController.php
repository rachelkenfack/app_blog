<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lookup'), IsGranted('ROLE_USER')]
class LookUpController extends AbstractController
{
    #[Route('/', name: 'app_lookup')]
    public function index(): Response
    {
        if($this->getUser()->getRoles()[0] === 'ROLE_USER'){
            return $this->redirectToRoute('app_user');
        }
        if($this->getUser()->getRoles()[0] === 'ROLE_ADMIN'){
            return $this->redirectToRoute('app_Admin');
        }
        
        return $this->render('look_up/index.html.twig', [
            'controller_name' => 'LookUpController',
        ]);
    }
}
