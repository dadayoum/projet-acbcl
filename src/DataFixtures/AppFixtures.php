<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    protected Generator $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 90; $i++) {
            $user = new User();
            $user->setEmail($faker->safeEmail);
            $user->setRoles(rand(0, 1) ? ['ROLE_ADMIN'] : ['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin@123'));
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setBirthday($faker->dateTime($max = 'now'));
            $user->setAddress($faker->streetAddress);
            $user->setZipCode($faker->postcode);
            $user->setCity($faker->city);
            $user->setPhoneNumber($faker->phoneNumber);
            $user->setIsVerified(rand(0, 1));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
