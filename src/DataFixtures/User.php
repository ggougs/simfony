<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Users;

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
            $manager->persist($users);

        }

        $manager->flush();
    }
   
}
