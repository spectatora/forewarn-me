<?php
namespace Crawler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Symfony\Component\DomCrawler\Crawler;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $providerKey = 'ViKVT';


        /** @var \Crawler\Service\StrategyFactory $strategyFactory */
        $strategyFactory = $this->getServiceLocator()->get('Crawler\Service\StrategyFactory');
        /** @var \Crawler\Service\StrategyInterface $strategy */

        try {
            $strategy = $strategyFactory->factory($providerKey);
            $strategy->process(array());
        } catch (\Exception $e)
        {
            print $e->getMessage();
        }


        die('end of code');

        return $this->getResponse();
    }
}
