<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/5/16
 * Time: 6:30 PM
 */

namespace Crawler\Service\Strategy;

use Crawler\Service\Strategy\AbstractStrategy;
use Crawler\Service\UniqueIdentifier;
use Crawler\Service\DateTimeExtractor;
use Symfony\Component\DomCrawler\Crawler;

class ViKVT extends AbstractStrategy
{
    const PROVIDER_URL = "http://www.vik-vt.com/";

    public function process($data)
    {

        $newsUrl = self::PROVIDER_URL . "?mod=news";
        $crawler = new Crawler;
        $crawler->addHTMLContent(file_get_contents($newsUrl), 'UTF-8');

        if ($crawler->filter("body")->text() == "404")
        {
            return array();
        }

        $firstDate = $crawler->filterXPath("//div[@class='mb5 date']")->first()->text();
        $firstIdentifier = $crawler->filterXPath("//div[@class='mb15 text_06']/a")->first()->attr("href");
        $firstUniqueIdenfitier = UniqueIdentifier::generate($firstIdentifier);

        /** @var \Crawler\Model\Warnings $warningsModel */
        $warningsModel = $this->getWarningsModel();
        /** @var  $foundEntity */
        $foundEntity = $warningsModel->findByUniqueIdentifier($firstUniqueIdenfitier);


        print '<hr />';
        var_dump($firstDate,$firstIdentifier, $firstUniqueIdenfitier);
        print '<hr />';
        //ABOUT PAGE

        $this->processSingleEntry($firstIdentifier);

        //END OF ABOUT PAGE

        $nodeValues = $crawler->filter('div.list_item')->each(function ($node, $i) {

            $time = $node->filterXPath("//div[@class='mb5 date']")->text();
            $identifier = $node->filterXPath("//div[@class='mb15 text_06']/a")->first()->attr("href");
            $uniqueIdentifier = UniqueIdentifier::generate($identifier);


            return array(
                'time' => $time,
                'identifier' => $identifier,
                'uniqueIdentifier' => $uniqueIdentifier
            );

        });


        var_dump($nodeValues);


        return;
    }

    public function processSingleEntry($identifier)
    {
        $currentPage = self::PROVIDER_URL . $identifier;
        $currentCrawler = new Crawler;
        $currentCrawler->addHTMLContent(file_get_contents($currentPage), 'UTF-8');
        if ($currentCrawler->filter("body")->text() == "404")
        {
            return array();
        }

        $content = $currentCrawler->filter('div.content');

        $aboutTime = $content->filterXPath("//div[@class='mb15 text_08 fs14']")->text();

        $actualMessage = $content->filter("div.fs12");

        $aboutMessage = $actualMessage->text();


        $nodeValues = $actualMessage->filter('strong')->each(function ($node, $i) {

            return array(
                $node->text()
            );

        });

       $dateTime = DateTimeExtractor::extractDateTime($aboutMessage);

        $uniqueIdentifier = UniqueIdentifier::generate($identifier);

        if (!empty($nodeValues) && is_array($nodeValues) && count($nodeValues) > 1)
        {
            $places = trim($nodeValues[1][0]);
        }


        var_dump($places, $uniqueIdentifier, $dateTime,$aboutTime, $nodeValues, $aboutMessage);
    }
} 