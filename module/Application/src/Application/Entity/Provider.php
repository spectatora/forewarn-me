<?php
/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 5/7/16
 * Time: 4:04 PM
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity;

/**
 * @ORM\Table(name="providers")
 * @ORM\Entity(repositoryClass="Application\Model\Provider")
 */
class Provider extends AbstractEntity
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=63, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", length=63, nullable=false)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=63, nullable=false)
     */
    private $area;

    /**
     * @var ProviderType
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\ProviderType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="typeId", referencedColumnName="id")
     * })
     *
     */
    private $type;

    /**
     * @param string $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

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
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Application\Entity\ProviderType $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \Application\Entity\ProviderType
     */
    public function getType()
    {
        return $this->type;
    }

} 