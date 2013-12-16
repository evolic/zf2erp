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

/**
 * Class CompaniesService - service used to perform basic logic operations on companies.
 *
 * @package EvlErp\Service
 */
class CompaniesService implements CompaniesServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * Method used to obtain orders repository.
     *
     * @return CompaniesRepository
     */
    public function getCompaniesRepository()
    {
        return $this->getEntityManager()->getRepository('EvlErp\Entity\Company');
    }

    /**
     * Method used to persist new company in database
     *
     * @param Company $company
     * @return boolean
     */
    public function addCompany(Company $company)
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $this->getEntityManager()->persist($company);
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
