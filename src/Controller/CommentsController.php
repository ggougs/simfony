<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentsController extends AbstractController
{
    /**
     * @Route("/comments", name="comments")
     */
    public function index()
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
        ]);
    }
    /**
     * @Route("/comments/{id}", name="commentsDetails",requirements={"id"="\d+"}))
     
     */
    public function listComments(CommentsRepository $repo,$id)
    {

         $oneComment = $repo->findby(array("articles"=>$id));
       
        return $this->render('comments/commentsDetail.html.twig',[
            'oneComment' => $oneComment]);
    }

}