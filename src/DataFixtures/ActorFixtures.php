<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 1; $i <= 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());
            for($j = 1; $j <= 3; $j++) {
                $actor->addProgram($this->getReference('program_' . $this->slugger->slug(ProgramFixtures::PROGRAMS[$faker->unique(true)->numberBetween(0, count(ProgramFixtures::PROGRAMS) - 1)]['title'])));
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
