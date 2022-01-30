<?php

namespace App\Document\Infra\ORM\Doctrine\Fixtures;

use App\Document\Domain\Model\Storage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StorageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Azure = new Storage(
            '2ff7fa9e-e0f6-47d3-b408-00fd0d5d6ff4',
            'Azure'
        );

        $manager->persist($Azure);
        $manager->flush();
    }
}
