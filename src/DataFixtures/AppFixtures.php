<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i=1; $i <= 5; $i++) {
            $user = new User();
            $user->setName("Utilisateur n°$i")
                 ->setSurname("Utilisateur n°$i")
                 ->setEmail("utilisateur$i@gmail.com")
                 ->setPassword("michel")
                 ->setToken(random_bytes(255))
                 ->setCreatedAt(new \DateTime());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
