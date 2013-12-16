<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Doctrine\Repository;

use Doctrine\ORM\Query;

interface CompaniesRepositoryInterface
{
    /**
     * Method used to obtain available Companies from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available companies
     */
    public function getCompanies($criteria, $hydrate = Query::HYDRATE_OBJECT);
}
