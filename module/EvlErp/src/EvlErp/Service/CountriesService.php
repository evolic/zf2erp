<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Service;

use Doctrine\ORM\EntityManager;
use EvlErp\Doctrine\Repository\CountriesRepository;
use EvlErp\Entity\Country;

/**
 * Class CountriesService - service used to perform basic logic operations on countrys.
 *
 * @package EvlErp\Service
 */
class CountriesService implements CountriesServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * Method used to obtain orders repository.
     *
     * @return CountriesRepository
     */
    public function getCountriesRepository()
    {
        return $this->getEntityManager()->getRepository('EvlErp\Entity\Country');
    }

    /**
     * Method used to persist new country in database
     *
     * @param Country $country
     * @param string $locale
     * @return boolean
     */
    public function addCountry(Country $country, $locale)
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $this->getEntityManager()->persist($country);
            // commit transaction
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();

            return true;
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            $this->getEntityManager()->close();

            return false;
        }
    }


    /**
     * Method used to obtain EntityManager.
     *
     * @return EntityManager - entity manager object
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Method used to inject EntityManager.
     *
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
