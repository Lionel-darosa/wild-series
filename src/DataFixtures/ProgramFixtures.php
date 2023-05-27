<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    private const PROGRAMS = [
        ['title' => 'Brooklyn nine-nine', 'poster' => 'brooklyn_99.jpg', 'synopsis' => 'stories that take place into a Brooklyn police station', 'category' => 'Comedie'],
        ['title' => 'The last of us', 'poster' => 'the_last_of_us.jpg', 'synopsis' => 'the crossing of the united states, following an epidemic, of a father who has lost his daughter and an orphan girl who may hold the cure', 'category' => 'Horreur'],
        ['title' => 'What we do in the shadows', 'poster' => 'what_we_do_in_the_shadows.jpg', 'synopsis' => 'the documentary of a vampire roommates in new jersey', 'category' => 'Comedie'],
        ['title' => 'the office', 'poster' => 'the_office.jpg', 'synopsis' => 'The series focuses on the daily lives of office workers at a paper sales company, Dunder Mifflin, in Scranton, Pennsylvania', 'category' => 'Comedie'],
        ['title' => 'Ash VS the evil dead', 'poster' => 'ash_vs_the_evil_dead.jpg', 'synopsis' => 'After living in hiding for 30 years, Ash is forced to return to duty and face his demons. Literally and figuratively. But this time, he\'s not alone in fighting the forces of evil.', 'category' => 'Horreur'],
    ];
    
    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $programData){
            $program = new Program();
            $program->setTitle($programData['title']);
            $program->setPoster($programData['poster']);
            $program->setSynopsis($programData['synopsis']);
            $program->setCategory($this->getReference('category_' . $programData['category']));
            $manager->persist($program);
            $this->addReference('program_' . $key+=1, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        
        return [
            CategoryFixtures::class,
        ];
    }
}
