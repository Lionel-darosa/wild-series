<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($programNumber = 0; $programNumber < count(ProgramFixtures::PROGRAMS); $programNumber++) {
            for($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                for($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence(4));
                    $episode->setNumber($episodeNumber);
                    $episode->setSynopsis($faker->paragraph());
                    $episode->setSlug($this->slugger->slug($episode->getTitle()));
                    $episode->setDuration($faker->randomNumber(2, false));
                    $episode->setSeason($this->getReference('season'. $seasonNumber . '_serie' . $this->slugger->slug(ProgramFixtures::PROGRAMS[$programNumber]['title'])));
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
