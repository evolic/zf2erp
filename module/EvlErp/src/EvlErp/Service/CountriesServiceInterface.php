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

interface CountriesServiceInterface
{
    /**
     * Method used to obtain countrys repository.
     *
     * @return CountriesRepository
     */
    public function getCountriesRepository();

    /**
     * Method used to persist new country in database
     *
     * @param Country $country
     * @param string $locale
     * @return boolean
     */
    public function addCountry(Country $country, $locale);


    /**
     * Method used to obtain EntityManager.
     *
     * @return EntityManager - entity manager object
     */
    public function getEntityManager();

    /**
     * Method used to inject EntityManager.
     *
     * @param EntityManager $entityManager
     * @return void
     */
    public function setEntityManager(EntityManager $entityManager);
}
