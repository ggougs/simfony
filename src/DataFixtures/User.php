<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Users;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Categories;

class User extends Fixture
{
    public function load(ObjectManager $manager)
    {   
        
       
        for ($i=0 ; $i < 5 ; $i++) {
            $users = new Users();
            $users->setLastName("Gregory")
                  ->setName("Garabedian")
                  ->setuserName("admin")
                  ->setEmail("gg@gmail.Com")
                  ->setPassword('ggougs')
                  ->setDateCreate(new \DateTime() )
                  ->setDateLastLogin(new \DateTime() );
          
            for ($j = 0 ; $j < 1 ; $j++) {
                $articles = new Articles();
                $articles->setTitle("il fait beau")
                      ->setContent("le ciel est bleu")
                      ->setDateCreation(new \DateTime() )
                     -> setUserId($users);
                     
                $this->setReference('art-article', $articles);
                $manager->persist($articles);
                    for ($k = 0 ; $k < 1 ; $k++) {
                        $categories = new Categories();
                        $comments = new Comments();
                        $categories ->setName("Actualité")
                                    ->addArticle($this->getReference('art-article'));
                        $comments ->setContent("super article")
                                  ->setDateCreate(new \DateTime() )
                                  ->setUsers($users)
                                  ->setArticles($articles);
                                   $manager->persist($comments);
                                   $manager->persist($categories);
                      

                       
                                
            }
            



        }
        $manager->persist($users);
        
    }
    $manager->flush();
}
}
