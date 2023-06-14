<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USERS = [
        ['email' => 'lionel.darosa@hotmail.fr', 'role' => ['ROLE_CONTRIBUTOR'], 'password' => 'password'],
        ['email' => 'lioneldarosa@gmail.fr', 'role' => ['ROLE_ADMIN'], 'password' => 'password'],
    ];
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $user) {
            $contributor = new User();
            $contributor->setEmail($user['email']);
            $contributor->setRoles($user['role']);
            $contributor->setPassword($this->passwordHasher->hashPassword($contributor, $user['password']));
            $manager->persist($contributor);
            $this->addReference('user_' . $contributor->getEmail(), $contributor);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
