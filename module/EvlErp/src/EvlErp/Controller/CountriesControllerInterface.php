<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Controller;

use EvlErp\Form\CountryForm;
use EvlErp\Service\CountriesService;

interface CountriesControllerInterface
{
    const DEFAULT_LIMIT_PER_PAGE = 20;


    /**
     * Lists countries
     */
    public function indexAction();

    /**
     * Handles adding new countries
     */
    public function addAction();


    /**
     * Method used to inject form handling adding new Country.
     *
     * @param CountryForm $form
     */
    public function setCountryForm(CountryForm $form);

    /**
     * Method used to obtain form handling adding new Country.
     *
     * @return CountryForm
     */
    public function getCountryForm();

    /**
     * Method used to inject Countries service.
     *
     * @param CountriesService $service
     */
    public function setCountriesService(CountriesService $service);

    /**
     * Method used to obtain Countries service.
     *
     * @return CountriesService
     */
    public function getCountriesService();
}
