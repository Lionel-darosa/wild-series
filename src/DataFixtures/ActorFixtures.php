<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 1; $i <= 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());
            for($j = 1; $j <= 3; $j++) {
                $actor->addProgram($this->getReference('program_' . $faker->unique(true)->numberBetween(1, count(ProgramFixtures::PROGRAMS))));
            }
            $manager->persist($actor);
        }
        
        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
