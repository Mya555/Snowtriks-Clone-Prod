<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
        foreach ($this->getTricksData() as [$name, $description, $user, $groupe ]){
            $trick = new Tricks();
            $trick->setName($name);
            $trick->setDescription($description);
            $trick->setGroupe($groupe);

            $manager->persist($trick);

        }

        $manager->flush();
    }
    private function getTricksData(): array {
        return [
            ['name', 'description', 'user', 'groupe' ]
        ];
    }
}
