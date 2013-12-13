<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Doctrine\Repository;

use Doctrine\ORM\Query;

interface ProductCategoriesRepositoryInterface
{
    /**
     * Method used to obtain available ProductCategories from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available product categories
     */
    public function getProductCategories($criteria, $hydrate = Query::HYDRATE_OBJECT);
}
