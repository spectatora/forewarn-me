<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/7/16
 * Time: 3:35 PM
 */

namespace Crawler\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Table(name="warnings")
 * @ORM\Entity(repositoryClass="Crawler\Model\Warnings")
 */
class Warning extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false)
     *
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=63, nullable=false)
     */
    private $provider;

    /**
     * @var string
     *
     * @ORM\Column(name="places", type="string", length=256, nullable=false)
     */
    private $places;

    /**
     * @var string
     *
     * @ORM\Column(name="uniqueIdentifier", type="string", length=256, nullable=false)
     */
    private $uniqueIdentifier;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $places
     */
    public function setPlaces($places)
    {
        $this->places = $places;
    }

    /**
     * @return string
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param \Datetime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return \Datetime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $uniqueIdentifier
     */
    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }
} 