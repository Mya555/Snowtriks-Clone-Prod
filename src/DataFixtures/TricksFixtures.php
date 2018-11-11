<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
        foreach ($this->getTricksData() as [$name, $description, $groupe ]){
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
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ],
            ['Ninety-ninety', 'An aerial trick in which a snowboarder twists his body in order to shift or rotate his board about 90° from its normal position beneath him, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'Straight airs' ]
        ];
    }
}
