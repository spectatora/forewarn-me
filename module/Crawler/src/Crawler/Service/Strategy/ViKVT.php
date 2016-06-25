<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/5/16
 * Time: 6:30 PM
 */

namespace Crawler\Service\Strategy;

use Crawler\Entity\Warning;
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

        /** @note Last published item already in our db so skip this operation */
        if (!empty($foundEntity))
        {
            return;
        }


        /** @note Loop through all the results */
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

        foreach($nodeValues as $nodeElement)
        {
            /** @note Get unique identifier */
            $uniqueidentifier = $nodeElement['uniqueIdentifier'];

            /** @var  $foundEntity */
            $foundEntity = $warningsModel->findByUniqueIdentifier($uniqueidentifier);
            /** @note If we have it in db - SKIP */
            if (!empty($foundEntity))
            {
                continue;
            }
            /** @note Process single entry - add it to db */
            $this->processSingleEntry($nodeElement['identifier']);
        }

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

            return $node->text();

        });
         /** @note Extract date time object when this occurred */
         $dateTime = DateTimeExtractor::extractDateTime($aboutMessage);
        /** @note Generate unique identifier */
        $uniqueIdentifier = UniqueIdentifier::generate($identifier);

        $places = '';
        /** @note Get information about places */
        if (!empty($nodeValues) && is_array($nodeValues) && count($nodeValues) > 0)
        {
            if (count($nodeValues) > 1)
            {
                $places = trim($nodeValues[1]);
            } else {
                $places = trim($nodeValues[0]);
            }
        }
        /** @note Prepare warning entity and populate it with correct data */
        $warningsEntity = new Warning(array(
            'uniqueIdentifier' => $uniqueIdentifier,
            'time' => $dateTime,
            'message' => $aboutMessage,
            'provider' => $this->getProvider(),
            'places' => $places,
        ));

        $this->getWarningsModel()->save($warningsEntity);
    }
} 