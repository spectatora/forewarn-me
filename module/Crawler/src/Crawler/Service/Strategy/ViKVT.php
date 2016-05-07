<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/5/16
 * Time: 6:30 PM
 */

namespace Crawler\Service\Strategy;

use Crawler\Service\Strategy\AbstractStrategy;
use Symfony\Component\DomCrawler\Crawler;

class ViKVT extends AbstractStrategy
{
    const PROVIDER_URL = "http://www.vik-vt.com/";

    public function process($data)
    {

        $base = "http://www.vik-vt.com/";
        $newUrl = "http://www.vik-vt.com/?mod=news";
        $crawler = new Crawler;
        $crawler->addHTMLContent(file_get_contents($newUrl), 'UTF-8');

        if ($crawler->filter("body")->text() == "404")
        {
            return array();
        }

        $firstDate = $crawler->filterXPath("//div[@class='mb5 date']")->first()->text();
        $firstIdentifier = $crawler->filterXPath("//div[@class='mb15 text_06']/a")->first()->attr("href");

        //ABOUT PAGE

        $currentPage = $base . $firstIdentifier;
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

        var_dump($aboutTime, $nodeValues, $aboutMessage);

        //END OF ABOUT PAGE

        $nodeValues = $crawler->filter('div.list_item')->each(function ($node, $i) {

            $time = $node->filterXPath("//div[@class='mb5 date']")->text();
            $identifier = $node->filterXPath("//div[@class='mb15 text_06']/a")->first()->attr("href");

            return array(
                'time' => $time,
                'identifier' => $identifier
            );

        });


        var_dump($nodeValues);


        return;
    }

    public function processSingleEntry($uniqueIdentifier)
    {

    }
} 