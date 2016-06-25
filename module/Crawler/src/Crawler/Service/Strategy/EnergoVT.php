<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/22/16
 * Time: 10:21 AM
 */

namespace Crawler\Service\Strategy;

use Crawler\Entity\Warning;
use Crawler\Service\Strategy\AbstractStrategy;
use Symfony\Component\DomCrawler\Crawler;
use Crawler\Service\UniqueIdentifier;
use Crawler\Service\DateTimeExtractor;

class EnergoVT extends AbstractStrategy
{
    const PROVIDER_URL = "http://www.energo-pro-grid.bg/bg/Oblast-Veliko-Tarnovo";

    //const PROVIDER_URL = "http://www.energo-pro-grid.bg/bg/ROC-Varna";
    //const PROVIDER_URL = "http://www.energo-pro-grid.bg/bg/Oblast-Shumen";

    public function process($data)
    {

        $providerUrl = self::PROVIDER_URL;
        $crawler = new Crawler;
        $crawler->addHTMLContent(file_get_contents($providerUrl), 'windows-1251');

        if ($crawler->filter("body")->text() == "404")
        {
            return array();
        }


        $firstText = $crawler->filter("div#div_tab_1")->filter('p')->first()->text();
        $firstUniqueIdentifier = UniqueIdentifier::generate($firstText);

        if (!empty($firstText) && is_string($firstText))
        {
            $trimmedData = trim($firstText);
            /** @note No warnings found on the page - SKIP  */
            if ($trimmedData == EnergoProData::NO_WARNINGS)
            {
               return;
            }
        }

        /** @var \Crawler\Model\Warnings $warningsModel */
        $warningsModel = $this->getWarningsModel();
        /** @var  $foundEntity */
        $foundEntity = $warningsModel->findByUniqueIdentifier($firstUniqueIdentifier);

        /** @note Last published item already in our db so skip this operation */
        if (!empty($foundEntity))
        {
            return;
        }

        /** @note Loop through all the results */
        $nodeValues = $crawler->filter("div#div_tab_1")->filter('p')->each(function ($node, $i) {

            $time = $node->filterXPath("//strong")->text();
            $text = $node->text();
            $uniqueIdenfitier = UniqueIdentifier::generate($text);


            /** @note Extract date time object when this occurred */
            $dateTimeValue = DateTimeExtractor::extractDateTime($time);


            return array(
                'time' => $dateTimeValue,
                'message' => $text,
                'uniqueIdentifier' => $uniqueIdenfitier,
                'places' => ""
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
            $this->processSingleEntry($nodeElement);
        }

        return;
    }

    public function processSingleEntry($entryData)
    {
        /** @note Prepare warning entity and populate it with correct data */
        $warningsEntity = new Warning($entryData);
        $warningsEntity->setProvider($this->getProvider());

        $this->getWarningsModel()->save($warningsEntity);
    }
} 