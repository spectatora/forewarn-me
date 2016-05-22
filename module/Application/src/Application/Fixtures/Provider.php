<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/7/16
 * Time: 5:20 PM
 */

namespace Application\Fixtures;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Application\Entity\Provider as ProviderEntity;

class Provider extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ViKProvider = $manager->getRepository('Application\Entity\Provider')->findOneBy(['providerKey' => 'ViKVT']);

        if(!$ViKProvider) {
            //Provider does not exist, update
            $ViKProvider = new ProviderEntity();
            $ViKProvider->setName('ВиК Йовковци');
            $ViKProvider->setArea('Велико Търново');
            $ViKProvider->setType($this->getReference('vik'));
            $ViKProvider->setProviderKey('ViKVT');
        }


        $manager->persist($ViKProvider);


        $energoVTProvider = $manager->getRepository('Application\Entity\Provider')->findOneBy(['providerKey' => 'energoVT']);

        if(!$energoVTProvider) {
            //Provider does not exist, update
            $energoVTProvider = new ProviderEntity();
            $energoVTProvider->setName('ЕнергоПро Велико Търново');
            $energoVTProvider->setArea('Велико Търново');
            $energoVTProvider->setType($this->getReference('electricity'));
            $energoVTProvider->setProviderKey('energoVT');
        }


        $manager->persist($energoVTProvider);

        $manager->flush();

        $this->addReference('ViKVT', $ViKProvider);
        $this->addReference('energoVT', $energoVTProvider);

    }

    public function getDependencies()
    {
        return array('Application\Fixtures\ProviderType');
    }
}