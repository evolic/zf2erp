<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Service;

use Doctrine\ORM\EntityManager;
use EvlErp\Doctrine\Repository\CompaniesRepository;
use EvlErp\Entity\Company;

interface CompaniesServiceInterface
{
    /**
     * Method used to obtain companies repository.
     *
     * @return CompaniesRepository
     */
    public function getCompaniesRepository();

    /**
     * Method used to persist new company in database
     *
     * @param Company $company
     * @return boolean
     */
    public function addCompany(Company $company);


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
