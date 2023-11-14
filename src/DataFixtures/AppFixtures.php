<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder){

        $this->passwordEncoder=$passwordEncoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create('fr_FR');
        for ($i = 0; $i < 2; $i++) {
            $user= new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder->hashPassword($user,'flanord'));
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
        }

        $faker=Factory::create('fr_FR');
        for ($i = 0; $i < 2; $i++) {
            $user= new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder->hashPassword($user,'flanord'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $faker=Factory::create('fr_FR');
        for ($i = 0; $i < 2; $i++) {
            $user= new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder->hashPassword($user,'flanord'));
            $user->setRoles(['']);
            $manager->persist($user);

        }

        $manager->flush();
    }
}
