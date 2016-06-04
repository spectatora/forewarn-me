<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 6/4/16
 * Time: 3:36 PM
 */

namespace Crawler\Service;


class UniqueIdentifier
{

    /**
     * Generates key
     *
     * @param $key
     * @return string
     */
    public static function generate($key)
    {
        return sha1($key);
    }

} 