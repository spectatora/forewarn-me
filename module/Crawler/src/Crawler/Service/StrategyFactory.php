<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/5/16
 * Time: 6:04 PM
 */

namespace Crawler\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StrategyFactory implements ServiceLocatorAwareInterface
{

    const STRATEGY_NAMESPACE = "Crawler\\Service\\Strategy\\";

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

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
     * @param $strategyIndicator
     * @return StrategyInterface
     * @throws StrategyNotFoundException
     * @throws StrategyWrongInterfaceException
     */
    public function factory($strategyIndicator)
    {
        $filter = new \Zend\Filter\Word\DashToCamelCase();

        $choosedStrategy = ucfirst($filter->filter($strategyIndicator));

        $className = self::STRATEGY_NAMESPACE . $choosedStrategy;

        if (!class_exists($className, true)) {
            throw new StrategyNotFoundException(
                "Strategy with the name of '$choosedStrategy' not found");
        }

        /** @var StrategyInterface $strategyInstance */
        $strategyInstance = $this->getServiceLocator()->get($className);

        if (!($strategyInstance instanceof StrategyInterface)) {
            throw new StrategyWrongInterfaceException("$className must implement StrategyInterface");
        }

        /** @var \Application\Model\Provider $providerModel */
        $providerModel = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\Provider');
        /** @var \Application\Entity\Provider $provider */
        $provider = $providerModel->findOneBy(array('providerKey' => $strategyIndicator));

        $strategyInstance->setProvider($provider);

        return $strategyInstance;
    }
} 