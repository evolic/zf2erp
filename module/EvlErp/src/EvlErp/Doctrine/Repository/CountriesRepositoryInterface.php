<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Doctrine\Repository;

use Doctrine\ORM\Query;

interface CountriesRepositoryInterface
{
    /**
     * Method used to obtain available countries from the database
     *
     * @param array $criteria - additional criteria
     * @param string $locale - country name locale
     * @param int $hydrate - result hydration mode
     * @return array - available countries
     */
    public function getCountries($criteria, $locale, $hydrate = Query::HYDRATE_OBJECT);

    /**
     * Method used to get country from the database by it's name and locale
     *
     * @param array $criteria - additional criteria
     * @param string $locale - country name locale
     * @param int $hydrate - result hydration mode
     * @return array - available countries
     */
    public function findCountry($name, $locale, $hydrate = Query::HYDRATE_OBJECT);
}
