<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 6/5/16
 * Time: 11:43 AM
 */

namespace Crawler\Service;


class DateTimeExtractor
{
    /**
     * Extracts date time from given string
     *
     * @note Fetches only the first match of date and time
     * @param $source
     * @return \DateTime
     */
    public static function extractDateTime($source)
    {
        if (!empty($source))
        {
            try {

                $dateRegEx = "/\\d{2}.\\d{2}.\\d{4}/im";
                preg_match($dateRegEx, $source, $dateMatches);

                if (!empty($dateMatches))
                {
                    $dateValue = $dateMatches[0];

                    $timeRegEx = "/от \\d{2}:\\d{2}/im";
                    preg_match($timeRegEx, $source, $timeMatches);

                    if (!empty($timeMatches))
                    {
                        $timeValue = str_replace("от ", "", $timeMatches[0]);

                        //prepare date time string containing information about date and time
                        $dateTimeValue = $dateValue . " " . $timeValue;
                        $dateTime = \DateTime::createFromFormat('d.m.Y H:i', $dateTimeValue);
                        //returns date time object based on the date and time data
                        return $dateTime;
                    }

                    $dateTimeFromDateOnly = \DateTime::createFromFormat('d.m.Y',$dateValue);
                    //returns date time object based only on date found in the text
                    return $dateTimeFromDateOnly;

                }



            } catch(\Exception $e)
            {
                //TODO: log errors
            }
        }

        return new \DateTime();
    }
} 