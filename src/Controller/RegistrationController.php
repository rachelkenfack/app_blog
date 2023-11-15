<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\Role;

class RegistrationController extends AbstractController
{
    #[Route('/registration/{role}', name: 'app_registration')]
    public function index(  $role, Request $request, UserRepository $user_depos, UserPasswordHasherInterface $pass): Response
    {
        $datas=$request->request->all();
        $user=new User();
        $user->setNom($datas['nom']);
        $user->setPrenom($datas['prenom']);
        $user->setRoles([$role,'ROLE_USER']);
        $user->setPassword($pass->hashPassword($user,$datas['pass']));
        $user->setEmail($datas['email']);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setActif(true);
        $user->setBiographie($datas['bio']);
        $user->setGenre($datas['genre']);
        $user->setDateNaissance(new \DateTime($datas['date_nais']));
        $user_depos->save($user,true);
        return $this->redirectToRoute('app_login');
    }
    
    #[Route('/formul', name: 'app_formul')]
    public function index1(): Response
    {
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'Formulaire d\'inscription',
        ]);
    }
}
