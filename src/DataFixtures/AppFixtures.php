<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/SqlDataScripts/Dev');
        $finder->name('*.sql');
        $finder->files();
        $finder->sortByName();

        foreach( $finder as $file ){
            print "Importing: {$file->getBasename()} " . PHP_EOL;

            $sql = $file->getContents();

            $manager->getConnection()->exec($sql);

            $manager->flush();
        }
    }
}
