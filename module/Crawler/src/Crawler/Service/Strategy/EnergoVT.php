<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/22/16
 * Time: 10:21 AM
 */

namespace Crawler\Service\Strategy;

use Crawler\Service\Strategy\AbstractStrategy;
use Symfony\Component\DomCrawler\Crawler;

class EnergoVT extends AbstractStrategy
{
    //const PROVIDER_URL = "http://www.energo-pro-grid.bg/bg/Oblast-Veliko-Tarnovo";


    const PROVIDER_URL = "http://www.energo-pro-grid.bg/bg/ROC-Varna";

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
        $firstTime = $crawler->filter("div#div_tab_1")->filter('p')->first()->filterXPath("//strong")->text();

        var_dump($firstText, $firstTime);
        print '<br /><hr />';

        $nodeValues = $crawler->filter("div#div_tab_1")->filter('p')->each(function ($node, $i) {

            $time = $node->filterXPath("//strong")->text();
            $text = $node->text();


            return array(
                'time' => $time,
                'message' => $text
            );

        });

        var_dump($nodeValues);die;


        $todayAndTomorrowContainer = $crawler->filter("div#div_tab_1")->text();

        var_dump($todayAndTomorrowContainer);
        die;

        $firstDate = $crawler->filterXPath("//div[@class='mb5 date']")->first()->text();
        $firstIdentifier = $crawler->filterXPath("//div[@class='mb15 text_06']/a")->first()->attr("href");

        print '<hr />';
        var_dump($firstDate,$firstIdentifier);
        print '<hr />';
        //ABOUT PAGE

        $this->processSingleEntry($firstIdentifier);

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
        $currentPage = self::PROVIDER_URL . $uniqueIdentifier;
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
    }
} 