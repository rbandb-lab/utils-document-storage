<?php

namespace App\Document\Infra\ORM\Doctrine\Fixtures;

use App\Document\Domain\Model\CfgDocumentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CfgDocumentTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $pdfType = new CfgDocumentType(
            '2d1206d8-dd2f-48e8-91ea-a359427c7816',
            'standard_pdf',
            ['pdf'],
            20512
        );
        $manager->persist($pdfType);

        $pdfType = new CfgDocumentType(
            'f788436f-4f8a-4a3c-834d-5b0bf47c2887',
            'standard_excel',
            ['xls', 'xlsx'],
            20512
        );
        $manager->persist($pdfType);

        $pdfType = new CfgDocumentType(
            '2570dd54-308c-445b-9520-bc914e113a24',
            'standard_word',
            ['doc'],
            20512
        );
        $manager->persist($pdfType);

        $pdfType = new CfgDocumentType(
            'eb5e50bb-d037-4bab-b9b1-715f76b75206',
            'standard_jpeg',
            ['jpg', 'jpeg'],
            20512
        );
        $manager->persist($pdfType);

        $pdfType = new CfgDocumentType(
            '89070a40-a59a-47ac-a43a-a27b52e797a3',
            'standard_png',
            ['png'],
            20512
        );
        $manager->persist($pdfType);

        $pdfType = new CfgDocumentType(
            '104b6fe3-abed-4516-b4e2-73fc2396ca68',
            'standard_gif',
            ['gif'],
            20512
        );
        $manager->persist($pdfType);

        $manager->flush();
    }
}
