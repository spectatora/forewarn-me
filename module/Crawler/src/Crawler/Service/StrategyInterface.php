<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/5/16
 * Time: 5:58 PM
 */

namespace Crawler\Service;

interface StrategyInterface {
    public function process($data);

    public function setProvider($provider);
} 