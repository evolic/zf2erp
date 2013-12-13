<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Controller;

use EvlErp\Form\UnitForm;
use EvlErp\Service\UnitsService;

interface UnitsControllerInterface
{
    const DEFAULT_LIMIT_PER_PAGE = 20;


    public function indexAction();


    /**
     * Method used to inject form handling adding new unit.
     *
     * @param UnitForm $form
     */
    public function setUnitForm(UnitForm $form);

    /**
     * Method used to obtain form handling adding new unit.
     *
     * @return UnitForm
     */
    public function getUnitForm();

    /**
     * Method used to inject units service.
     *
     * @param UnitsService $service
     */
    public function setUnitsService(UnitsService $service);

    /**
     * Method used to obtain units service.
     *
     * @return UnitsService
     */
    public function getUnitsService();
}
