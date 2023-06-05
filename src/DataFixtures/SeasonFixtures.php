<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($programNumber = 1; $programNumber <= count(ProgramFixtures::PROGRAMS); $programNumber++) {
            for($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                $season = new Season();
                $season->setNumber($seasonNumber);
                $season->setProgram($this->getReference('program_' . $programNumber));
                $season->setYear($faker->year());
                $season->setDesciption($faker->paragraphs(3, true));
                $manager->persist($season);
                $this->addReference('season'. $seasonNumber . '_serie' . $programNumber, $season);
            }
            
        }
        
        //$this->addReference('season1_The last of us', $season);
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
