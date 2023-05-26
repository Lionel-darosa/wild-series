<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private const EPISODES = [
        ['title' => 'When You\'re Lost in the Darkness', 'synopsis' => 'Twenty years after a fungal outbreak ravages the planet, survivors Joel and Tess are tasked with a mission that could change everything.', 'season' => 'season1_The last of us'],
        ['title' => 'Infected', 'synopsis' => 'After escaping the QZ, Joel and Tess clash over Ellie\'s fate while navigating the ruins of a long-abandoned Boston.', 'season' => 'season1_The last of us'],
        ['title' => 'Long, Long Time', 'synopsis' => 'When an unknown person approaches his compound, survivalist Bill forges an unlikely connection. Later, Joel and Ellie seek Bill\'s guidance.', 'season' => 'season1_The last of us']
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $key => $episodeData) {
            $episode = new Episode();
            $episode->setTitle($episodeData['title']);
            $episode->setNumber($key+=1);
            $episode->setSynopsis($episodeData['synopsis']);
            $episode->setSeason($this->getReference('season1_The last of us'));
            $manager->persist($episode);

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
