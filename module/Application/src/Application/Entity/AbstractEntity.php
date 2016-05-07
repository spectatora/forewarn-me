<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/7/16
 * Time: 3:36 PM
 */

namespace Application\Entity;

use Zend\Filter\Encrypt;
use Zend\Filter\Decrypt;

abstract class AbstractEntity
{
    const ENCRYPTION_KEY = 'this is the encryption key';

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate($values)
    {
        // For each value, attempt to find the setter and use it
        foreach ($values as $key => $value) {
            $set = 'set' . $this->dashToCamel($key);

            if (method_exists($this, $set)) {
                $this->$set($value);
            }
        }
    }

    /**
     * Returns an array of fully lowercase attributes and their associated values
     *
     * @return array
     */
    public function toArray()
    {
        $methods = get_class_methods(get_class($this));
        $array = [];

        // Filter out only the getters
        foreach ($methods as $key => $method) {
            if ((strpos($method, 'get') === 0) && $method != 'getArrayCopy') {
                $name = strtolower(substr($method, 3));
                $array[$name] = $this->$method();
            }
        }

        return $array;
    }

    private function dashToCamel($string)
    {
        $string = str_replace('-', ' ', $string);
        $string = ucfirst($string);
        $string = str_replace(' ', '', $string);

        return $string;
    }

    public function encrypt($string)
    {
        $filter = new Encrypt([
            'adapter' => 'BlockCipher',
            'key' => self::ENCRYPTION_KEY
        ]);

        return $filter->filter($string);
    }

    public function decrypt($string)
    {
        $filter = new Decrypt([
            'adapter' => 'BlockCipher',
            'key' => self::ENCRYPTION_KEY
        ]);

        return $filter->filter($string);
    }
    /**
     * Get an array copy of object
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 