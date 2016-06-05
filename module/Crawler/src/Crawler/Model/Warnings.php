<?php
namespace Crawler\Model;

use Doctrine\ORM\EntityRepository;
use Crawler\Entity\Warning as WarningEntity;


/**
 * Created by PhpStorm.
 * User: prototype
 * Date: 6/5/16
 * Time: 4:13 PM
 */

class Warnings extends EntityRepository
{
    /**
     * Find Site Section by ID
     *
     * @param integer $id
     * @return WarningEntity|null
     */
    public function findById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Saves page
     *
     * @param WarningEntity $warning
     * @return $this
     */
    public function save(WarningEntity $warning)
    {
        $this->_em->persist($warning);
        $this->_em->flush($warning);
        return $this;
    }

    /**
     * Update page
     *
     * @param WarningEntity $warning
     * @return $this
     */
    public function update(WarningEntity $warning)
    {
        $this->_em->merge($warning);
        $this->_em->flush();
        return $this;
    }

    /**
     * Delete page
     *
     * @param WarningEntity $warning
     * @return $this
     */
    public function delete(WarningEntity $warning)
    {
        $this->_em->remove($warning);
        $this->_em->flush($warning);
        return $this;
    }

    /**
     * Gets all site sections
     *
     * @return WarningEntity[]
     */
    public function getAllSiteSections()
    {
        return $this->findBy([],['id'=> 'DESC']);
    }
} 