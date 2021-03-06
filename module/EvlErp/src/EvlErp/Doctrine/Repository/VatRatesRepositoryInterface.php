<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Doctrine\Repository;

use Doctrine\ORM\Query;

interface VatRatesRepositoryInterface
{
    /**
     * Method used to obtain orders repository.
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available VAT rates
     */
    public function getVatRates($criteria, $hydrate = Query::HYDRATE_OBJECT);
}
