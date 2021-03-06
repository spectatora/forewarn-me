<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/5/16
 * Time: 6:21 PM
 */

namespace Crawler\Service\Strategy;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Crawler\Service\StrategyInterface;

abstract class AbstractStrategy implements ServiceLocatorAwareInterface, StrategyInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * @var \Crawler\Model\Warnings
     */
    protected $warningsModel;

    /**
     * @var \Application\Entity\Provider
     */
    protected $provider;

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceManager;
    }

    /**
     * @return \Crawler\Model\Warnings
     */
    public function getWarningsModel()
    {
        if (!isset($this->warningsModel))
        {
            /** @var \Crawler\Model\Warnings $warningsModel */
            $warningsModel = $this->serviceManager->get('Doctrine\ORM\EntityManager')->getRepository('Crawler\Entity\Warning');
            $this->warningsModel = $warningsModel;

        }

        return $this->warningsModel;
    }

    /**
     * @param \Application\Entity\Provider $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return \Application\Entity\Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }


    public function process($data)
    {
    }
} 