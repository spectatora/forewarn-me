<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/7/16
 * Time: 4:20 PM
 */

namespace Application\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Entity\ProviderType as ProviderTypeEntity;

class ProviderType extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {

        $ViKType = $manager->getRepository('Application\Entity\ProviderType')->findOneBy(['name' => 'ВиК']);

        if(!$ViKType) {
            //Type does not exist, update
            $ViKType = new ProviderTypeEntity();
            $ViKType->setName('ВиК');
        }


        $ElectricityType = $manager->getRepository('Application\Entity\ProviderType')->findOneBy(['name' => 'Енерго']);
        if(!$ElectricityType) {
            //Type does not exist, update
            $ElectricityType = new ProviderTypeEntity();
            $ElectricityType->setName('Енерго');
        }

        $manager->persist($ViKType);
        $manager->persist($ElectricityType);
        $manager->flush();

        $this->addReference('vik', $ViKType);
        $this->addReference('electricity', $ElectricityType);

    }
} 