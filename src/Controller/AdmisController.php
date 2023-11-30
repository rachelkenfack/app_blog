<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use App\Entity\PieceJointe;
use App\Repository\ArticlesRepository;
use App\Repository\PieceJointeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\CommentType;
use App\Repository\CommentairesRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\Form\CallbackTransformer;

 #[Route('/admin'), IsGranted("ROLE_ADMIN")]  //IsGranted pour gerer les autorisaton
class AdmisController extends AbstractController
{
    #[Route('/', name: 'app_Admin')]
    public function index(): Response
    {

        return $this->render('admis/index.html.twig', [
            'controller_name' => 'AdmisController',
        ]);
    }
    #[Route('/ajoute', name: 'app_ajoute')]
    public function form(ArticlesRepository $artRepos): Response
    {
        $data=$artRepos->findAll();
        return $this->render('admis/ajoute.html.twig', [
            'donne' => $data,
        ]);
    }
    #[Route('/save', name: 'app_save')]
    public function ajoute(Request $request, ArticlesRepository $art, PieceJointeRepository $pieceJ): Response
    {
        $file=$request->files->get('piece');
        $file_name=md5(uniqid()).".".$file->guessExtension();
        $file->move('images/articles',$file_name);// permet depalcer le fichier dans notre projet
        $piece=new PieceJointe();
        $piece->setNomFichier($file_name);
        $piece->setActif(true);
        $piece->setCreatedAt(new \DateTimeImmutable());
        $piece->setUpdateAt(new \DateTimeImmutable());
        $pieceJ->save($piece,true);

        $val=$request->request->all();
        $article=new Articles();
        $article->addPieceJointe($piece);
        $article->setTitre($val['titre']);
        $article->setContenu($val['contenu']);
        $article->setCreatedAt(new \DateTimeImmutable());
        $article->setUpdatedAt(new \DateTimeImmutable());
        $article->setActif(true);
        $article->setUser($this->getUser());// recuperation de l'obejt en cours 'user'
        $art->save($article,true);
        $this->addFlash('notif', 'succes');

        
        return $this->redirectToRoute('app_Raffichage');
    }
    #[Route('/tableau', name: 'app_Raffichage')]
    public function tableau(ArticlesRepository $recuper): Response
    {
     $don=$recuper->findAll(); 
       
    return $this->render('admis/affichage.html.twig',['article'=>$don]);
    }

    #[Route('/updated/{id}', name: 'app_updated')]
    public function updated($id, ArticlesRepository $artRepos): Response
    {
        $arti_recup=$artRepos->find($id);
        
    return $this->render('admis/update.html.twig',['article_recuperer'=>$arti_recup]);
    }

    #[Route('/content/{id}', name: 'app_content')]
    public function formContent($id,Request $request, ArticlesRepository $art, EntityManagerInterface $inter, PieceJointeRepository $pieceRepos ): Response
    {
        $val=$request->request->all();
        $file=$request->files->get('piece');

            $article=$art->find($id);

            $article->setTitre($val['titre']);
            $article->setContenu($val['contenu']);
            $article->setUpdatedAt(new \DateTimeImmutable());
            $article->setActif(true);
           
          
            
            if($file !==null){
                $file_name=md5(uniqid()).".".$file->guessExtension();
                $file->move('images/articles',$file_name);
                // parcouront les piecejointe vue qu'il en exixte plusieurs(collection)
                foreach($article->getPieceJointe() as $pj){
                    $pj->setNomFichier($file_name);
                }
            }
            $inter->persist($article);
            $inter->flush();
            return $this->redirectToRoute('app_Raffichage');
        }
        
        #[Route('/deleted/{id}', name: 'app_deleted')]
        public function deleted($id, ArticlesRepository $artRepos, EntityManagerInterface $enti): Response
        {
            $article=$artRepos->find($id);
            $article->setActif(false);
            $enti->remove($article);
            $enti->flush();
        return $this->redirectToRoute('app_Raffichage');
        }    

        #[Route('/liste', name: 'app_liste')]
        public function liste(): Response
        {
            
        return $this->redirectToRoute('app_Raffichage');
        }    
       
}
