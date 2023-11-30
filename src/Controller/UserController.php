<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\PieceJointe;
use App\Entity\Commentaires;
use App\Repository\UserRepository;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PieceJointeRepository;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/user'), IsGranted("ROLE_USER")] 
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function tableauUser(ArticlesRepository $recuper): Response
    {
     $don=$recuper->findAll(); 
       
    return $this->render('user/index.html.twig',['article'=>$don]);
    }
    
    #[Route('/commentaire/{id_article}', name: 'app_commentaire')]
    public function commentaire($id_article, ArticlesRepository $article_depos, CommentairesRepository $commentairesRepo): Response
    {
        $articles=$article_depos->find($id_article);
        // vu que on a l'id de l'article et que nous savons que le commentaire a besoins de l'id de l'article, on va simplement faire une requete sur la table commentaire
        $commentaires = $commentairesRepo->findBy(['article' => $article_depos->find($id_article)]); // ici on recupere les commentaires lie a l'article dont l'id a ete passer en parametre
        
            return $this->render('user/commentaire.html.twig',[
            
            'commentaires' => $commentaires, // la on passe les commentaires de cet article a la vue. une fois dans la vue, on va boucle dessus pour les afficher
            'article'=>$articles
            ]); //incomprise
        }

       #[Route('/comment_define/{id_articles}', name: 'app_comment_define')]
    public function comment($id_articles, Request $request, CommentairesRepository $comment_depos, PieceJointeRepository $pieceJ, ArticlesRepository $art ): Response
    {
       
        $val=$request->request->all();
        // recuperond l'id de l'article
        $article=$art->find($id_articles);
        // dd($article);
       $file=$request->files->get('piece');
       $file_name=md5(uniqid()).".".$file->guessExtension();
       $file->move('images/articles',$file_name);// permet depalcer le fichier dans notre projet
       $piece=new PieceJointe();
       $piece->setNomFichier($file_name);
       $piece->setActif(true);
       $piece->setCreatedAt(new \DateTimeImmutable());
       $piece->setUpdateAt(new \DateTimeImmutable());
       $pieceJ->save($piece,true);
        
        $comment=new Commentaires();
        $comment->setAuteur($this->getUser()); // on recupere l'auteur du commentaire
        $comment->setArticle($article);

        // tu as oublier d'ajouter la pieceJointe avec son commentaire. vu que cest un manyToMany, il faut ajouter un commentaire avec la pieces ajouter
        $comment->addPieceJointe($piece); // ici on ajoute la piece jointe au commentaire en question
        
        $comment->setContenu($val['comment']);
        $comment->getPieceJointe($piece);
        $comment->setActif(true);
        $comment->setCreated(new \DateTimeImmutable());
        $comment->setUpdateAt(new \DateTimeImmutable());

        $comment_depos->save($comment,true);
        
        return $this->redirectToRoute('app_commentaire',['id_article' => $id_articles]);

    }

}