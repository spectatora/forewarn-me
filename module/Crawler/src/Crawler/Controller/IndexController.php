<?php
namespace Crawler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Symfony\Component\DomCrawler\Crawler;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {


        $newUrl = "http://www.vik-vt.com/?mod=news";
        $crawler = new Crawler;
        $crawler->addHTMLContent(file_get_contents($newUrl), 'UTF-8');

        if ($crawler->filter("body")->text() == "404")
        {
            return array();
        }

        $firstDate = $crawler->filterXPath("//div[@class='mb5 date']")->first()->text();
        $firstIdentifier = $crawler->filterXPath("//div[@class='mb15 text_06']/a")->first()->attr("href");


        $nodeValues = $crawler->filter('div.list_item')->each(function ($node, $i) {

            $time = $node->filterXPath("//div[@class='mb5 date']")->text();
            $identifier = $node->filterXPath("//div[@class='mb15 text_06']/a")->first()->attr("href");

            return array(
                'time' => $time,
                'identifier' => $identifier
            );

        });


        var_dump($nodeValues);


        return $this->getResponse();
    }
}
