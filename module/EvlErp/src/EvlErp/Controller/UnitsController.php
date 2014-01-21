<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use EvlErp\Entity\Unit;
use EvlErp\Form\UnitForm;
use EvlErp\Service\UnitsService;
use Loculus\Mvc\Controller\DefaultController;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\View\Model\ViewModel;

class UnitsController extends DefaultController
  implements UnitsControllerInterface
{
    /**
     *
     * @var UnitForm
     */
    private $unitForm;

    /**
     *
     * @var UnitsService
     */
    private $unitsService;


    public function indexAction()
    {
        $locale = $this->params()->fromRoute('locale');
        $orderBy = $this->params()->fromRoute('order_by', '');
        $limit = UnitsControllerInterface::DEFAULT_LIMIT_PER_PAGE;

        $criteria = array(
            'limit' => $limit,
            'order_by' => $orderBy,
        );

        $units = $this->getUnitsService()->getUnitsRepository()->getUnits(
            $criteria, Query::HYDRATE_ARRAY
        );

        $messages = $this->FlashMessenger()->getSuccessMessages();
        $errors = $this->FlashMessenger()->getErrorMessages();

        $this->viewModel->setVariables(array(
            'units' => $units,
            'messages' => $messages,
            'errors' => $errors,
        ));
        return $this->viewModel;
    }

    public function addAction()
    {
        $locale = $this->params()->fromRoute('locale');

        if (!$locale) {
            $this->getEvent()->getResponse()->setStatusCode(400);
            return $this->viewModel;
        }

        $form = new UnitForm();

        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $unit = new Unit();
            $form->setInputFilter($unit->getInputFilter());
            $form->attachObjectExistsValidator($this->getUnitsService()->getUnitsRepository());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $values = $form->getData();
                $unit->populate($values);

                if ($this->getUnitsService()->addUnit($unit)) {
                    $this->FlashMessenger()->addSuccessMessage('New unit has been successfully added');
                } else {
                    $this->FlashMessenger()->addErrorMessage('Error occurred while adding new unit');
                }

                // Redirect to list of units
                return $this->redirect()->toRoute('erp/units', array('locale' => $locale));
            }
        }

        $this->viewModel->setVariables(array(
            'form' => $form,
        ));

        return $this->viewModel;
    }


    /**
     * Method used to inject form handling adding new unit.
     *
     * @param UnitForm $form
     */
    public function setUnitForm(UnitForm $form)
    {
        $this->unitForm = $form;
    }

    /**
     * Method used to obtain form handling adding new unit.
     *
     * @return UnitForm
     */
    public function getUnitForm()
    {
        return $this->unitForm;
    }

    /**
     * Method used to inject units service.
     *
     * @param UnitsService $service
     */
    public function setUnitsService(UnitsService $service)
    {
        $this->unitsService = $service;
    }

    /**
     * Method used to obtain units service.
     *
     * @return UnitsService
     */
    public function getUnitsService()
    {
        return $this->unitsService;
    }
}
