<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Controller;

use EvlErp\Form\CompanyForm;
use EvlErp\Service\CompaniesService;

interface CompaniesControllerInterface
{
    const DEFAULT_LIMIT_PER_PAGE = 20;


    public function indexAction();


    /**
     * Method used to inject form handling adding new company.
     *
     * @param CompanyForm $form
     */
    public function setCompanyForm(CompanyForm $form);

    /**
     * Method used to obtain form handling adding new company.
     *
     * @return CompanyForm
     */
    public function getCompanyForm();

    /**
     * Method used to inject companies service.
     *
     * @param CompaniesService $service
     */
    public function setCompaniesService(CompaniesService $service);

    /**
     * Method used to obtain companies service.
     *
     * @return CompaniesService
     */
    public function getCompaniesService();
}
