<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Users;
use App\Entity\Articles;
use App\Entity\Comments;

class User extends Fixture
{
    public function load(ObjectManager $manager)
    {   
        
       
        for ($i=0 ; $i < 5 ; $i++) {
            $users = new Users();
            $users->setLastName("Gregory")
                  ->setName("Garabedian")
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
                    
                $manager->persist($articles);
                    for ($k = 0 ; $k < 1 ; $k++) {
                        $comments = new Comments();
                        $comments ->setContent("super article")
                                  ->setDateCreate(new \DateTime() )
                                  ->setUsers($users)
                                  ->setArticles($articles);
                                  $manager->persist($comments);
            }
            



        }
        $manager->persist($users);
        
    }
    $manager->flush();
}
}
