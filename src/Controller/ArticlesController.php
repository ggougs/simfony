<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Doctrine\Common\Persistence\ObjectManager;



class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function index()
    {
        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
        ]);
    }
    /**
     * @Route("/articles/{id}", name="articlesdetails",requirements={"id"="\d+"}))
     
     */
    public function listArticle(Comments $comment=null,ArticlesRepository $repo,$id,Request $request, ObjectManager $manager)
    {

         $oneArticle = $repo->findOneById($id);
             
             if(is_null($comment)) 
                 $comment = new Comments();
                
            
     
             $form = $this->createFormBuilder($comment)
                 ->add('content', TextareaType::class)
                 ->add('users', EntityType::class, array(
                    
                     'class' => Users::class,
                     'choice_label' => 'name'
                     ))

                     
                 ->add('save', SubmitType::class, array('label' => "Insere le l'article VAS Y "))
                 ->getForm();
     
                 $form->handleRequest($request);
     
                 if ($form->isSubmitted() && $form->isValid())  {
                    $comment->setArticles($oneArticle); 
                     $comment->setDateCreate( new \DateTime() ); 

                         $manager->persist( $comment );
                         $manager->flush();
             
                     return $this->redirectToRoute('articleslist');
                 }
     
       
        return $this->render('articles/articlesDetail.html.twig',[
            'oneArticle' => $oneArticle, 
            'form' => $form->createView()]);
    }
    /**
     * @Route("/articles/list", name="articleslist")
     */
    public function list (ArticlesRepository $repo)

    {
        $allArticles = $repo->findAll();
    
       
        return $this->render('articles/listArticles.html.twig',[
            'allarticles' => $allArticles]);
    }
    /**
     * @Route("/articles/ajout", name="ajoutArticle")
     * @Route("/articles/edit/{id}", name="updateArticle",requirements={"id"="\d+"})
     */

    public function addArticles(Articles $article=null,Request $request, ObjectManager $manager )
    {
        
        if(is_null($article)) 
            $article = new Articles();
           
       

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('userId', EntityType::class, array(
                // looks for choices from this entity
                'class' => Users::class,
                'choice_label' => 'name'
                ))
            ->add('save', SubmitType::class, array('label' => "Insere le l'article VAS Y "))
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())  {
             
                $article->setDateCreation( new \DateTime() ); 
                    $manager->persist( $article );
                    $manager->flush();
        
                return $this->redirectToRoute('articleslist');
            }

        return $this->render('articles/ajoutArticles.html.twig', array(
            'form' => $form->createView(),
        ));
     


       

    }
    /**
     * @Route("/article/delete/{id}", name="deleteArticle",requirements={"id"="\d+"})
   
     */
    public function deleteArticle(Articles $article, ObjectManager $manager){
        $manager -> remove ($article);
        $manager->flush();
        return $this->redirectToRoute('articleslist');

}
    
}
