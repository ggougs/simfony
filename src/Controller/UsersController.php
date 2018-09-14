<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



/**
 * @Route("/{_locale}")
*     requirements={
*         "_locale": '%app.locales%',
*     }
 */
class UsersController extends AbstractController
{
    
    
    /**
     * @Route("/user/users", name="users")
    */
    public function index()
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'test' => 'envoi le bon',
        ]);
    }

    /**
     * @Route("/user/users/{id}", name="usersdetails",requirements={"id"="\d+"}))
    */
    public function listOneUser(UsersRepository $repo,$id)
    {
        $oneUser = $repo->findOneById($id);
       
        return $this->render('users/userDetail.html.twig',[
            'oneUser' => $oneUser]);
    }

    /**
     * @Route("/user/users/list", name="userslist")
    */
    public function list (UsersRepository $repo)
    {
        $allUsers = $repo->findAll();
        return $this->render('users/listUsers.html.twig',[
            'allUser' => $allUsers]);
    }

    /**
     * @Route("/admin/users/ajout", name="ajoutUser")
     * @Route("/user/users/edit/{id}", name="updateUser",requirements={"id"="\d+"})
     */
    public function addUser(Users $user=null,Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader,TranslatorInterface $translator )
    {
        if(is_null($user)) {
        
            $user = new Users();

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('role', ChoiceType::class, array(
                'choices'  => array(
                 'Utilisateur' => 'ROLE_USER',
                    'Auteur' => 'ROLE_AUTHOR',
                    'Admin' => 'ROLE_ADMIN',
                ),
                'mapped' => false
            ))
            ->add('avatar', FileType::class, array('label' => 'Avatar (png file)','data_class' => null))
            ->add('lastname', TextType::class)
            ->add('userName', TextType::class)
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
            } else {
                $form = $this->createFormBuilder($user)
                ->add('name', TextType::class)
                ->add('role', ChoiceType::class, array(
                    'choices'  => array(
                     'Utilisateur' => 'ROLE_USER',
                        'Auteur' => 'ROLE_AUTHOR',
                        'Admin' => 'ROLE_ADMIN',
                    ),
                    'mapped' => false
                ))
                ->add('avatar', FileType::class, array('label' => 'Avatar (png file)','data_class' => null))
                ->add('lastname', TextType::class)
                ->add('userName', TextType::class)
                
                ->add('email', TextType::class)
                ->add('save', SubmitType::class, array('label' => "Insere le l'USER VAS Y "))
                ->getForm();

                $form->handleRequest($request);
              

                if ($form->isSubmitted() && $form->isValid())  {
               

                    $file = $form['avatar']->getData();
                    $fileName = $fileUploader->upload($file);
            
                    $user->setAvatar($fileName);
                
    
            
                    
                    
                        // Retrieve the value from the extra field non-mapped !
                        $user->setRoles($request->request->all()['form']['role']);
                   
                    $user->setDateCreate( new \DateTime() ); 
                    
                        $manager->persist( $user );
                        $manager->flush();
            
                    return $this->redirectToRoute('userslist');
                }
    
            return $this->render('users/ajoutUser.html.twig', array(
                'form' => $form->createView(),
            ));



            }
            $form->handleRequest($request);
  
            

            if ($form->isSubmitted() && $form->isValid())  {
               

                $file = $form['avatar']->getData();
                $fileName = $fileUploader->upload($file);
        
                $user->setAvatar($fileName);
            

        
                
                
                    // Retrieve the value from the extra field non-mapped !
                    $user->setRoles($request->request->all()['form']['role']);
               
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
     * @Route("/admin/users/delete/{id}", name="deleteUser",requirements={"id"="\d+"})
     */
    public function deleteUser(Users $user, ObjectManager $manager){
            $manager -> remove ($user);
            $manager->flush();
            return $this->redirectToRoute('userslist');
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}


