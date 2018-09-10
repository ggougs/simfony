<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UsersController extends AbstractController
{
    
    
    /**
     * @Route("/users", name="users")
     */
     
    public function index()
    {
        
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'test' => 'envoi le bon',
        ]);
    }
    /**
     * @Route("/users/{id}", name="usersdetails",requirements={"id"="\d+"}))
     
     */
    public function listOneUser(UsersRepository $repo,$id)
    {

         $oneUser = $repo->findOneById($id);
       
        return $this->render('users/userDetail.html.twig',[
            'oneUser' => $oneUser]);
    }
    /**
     * @Route("/users/list", name="userslist")
     */
    public function list (UsersRepository $repo)

    {
 

         $allUsers = $repo->findAll();
    
       
        return $this->render('users/listUsers.html.twig',[
            'allUser' => $allUsers]);
    }
    /**
     * @Route("/users/ajout", name="ajoutUser")
     * @Route("/users/edit/{id}", name="updateUser",requirements={"id"="\d+"})
     */

    public function addUser(Users $user=null,Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder )
    {
        
        if(is_null($user)) 
            $user = new Users();
           
       

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('lastname', TextType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'confirmer Password'),
            ))
            ->add('email', TextType::class)
            ->add('save', SubmitType::class, array('label' => "Insere le l'USER VAS Y "))
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())  {
             
                $user->setDateCreate( new \DateTime() ); 
                    $plainPassword= $user->getPassword(); 
                    $encoded = $encoder->encodePassword($user, $plainPassword);
                    $user->setPassword($encoded);
                    $manager->persist( $user );
                    $manager->flush();
        
                return $this->redirectToRoute('userslist');
            }

        return $this->render('users/ajoutUser.html.twig', array(
            'form' => $form->createView(),
        ));
     


       

    }
    /**
     * @Route("/users/delete/{id}", name="deleteUser",requirements={"id"="\d+"})
   
     */
    public function deleteUser(Users $user, ObjectManager $manager){
            $manager -> remove ($user);
            $manager->flush();
            return $this->redirectToRoute('userslist');

    }
}


