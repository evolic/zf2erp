<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Controller;

use EvlErp\Form\VatRateForm;
use EvlErp\Service\VatRatesService;

interface VatRatesControllerInterface
{
    const DEFAULT_LIMIT_PER_PAGE = 20;


    public function indexAction();


    /**
     * Method used to inject form handling adding new VAT rate.
     *
     * @param VatRateForm $form
     */
    public function setVatRateForm(VatRateForm $form);

    /**
     * Method used to obtain form handling a lunch ordering.
     *
     * @return VatRateForm
     */
    public function getVatRateForm();

    /**
     * Method used to inject VAT rates service.
     *
     * @param VatRatesService $service
     */
    public function setVatRatesService(VatRatesService $service);

    /**
     * Method used to obtain VAT rates service.
     *
     * @return VatRatesService
     */
    public function getVatRatesService();
}
