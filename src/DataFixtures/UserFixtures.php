<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;




class UserFixtures extends Fixture
{


    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$username, $password, $email]){
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);

            $manager->persist($user);

        }
        $manager->flush();



    }

    private function getUserData(): array
    {
        return[
            ['Jane Doe', 'kitten', 'jane_doe@gmail.com'],
            ['Jone Doe', 'kitten', 'jone_doe@gmail.com'],
        ];
    }
}