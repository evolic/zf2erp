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
     * @return VatRatesRepository
     */
    public function getVatRates($criteria, $hydrate = Query::HYDRATE_OBJECT);
}