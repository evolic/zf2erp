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
use EvlErp\Entity\VatRate;
use EvlErp\Form\VatRateForm;
use EvlErp\Service\VatRatesService;
use Loculus\Mvc\Controller\DefaultController;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\View\Model\ViewModel;

class VatRatesController extends DefaultController
  implements VatRatesControllerInterface
{
    /**
     *
     * @var VatRateForm
     */
    private $vatRateForm;

    /**
     *
     * @var VatRatesService
     */
    private $vatRatesService;


    public function indexAction()
    {
        $locale = $this->params()->fromRoute('locale');
        $orderBy = $this->params()->fromRoute('order_by', '');
        $limit = VatRatesControllerInterface::DEFAULT_LIMIT_PER_PAGE;

        $criteria = array(
            'limit' => $limit,
            'order_by' => $orderBy,
        );

        $vatRates = $this->getVatRatesService()->getVatRatesRepository()->getVatRates(
            $criteria, Query::HYDRATE_ARRAY
        );

        $messages = $this->FlashMessenger()->getSuccessMessages();
        $errors = $this->FlashMessenger()->getErrorMessages();

        $this->viewModel->setVariables(array(
            'vatRates' => $vatRates,
            'messages' => $messages,
            'errors' => $errors,
        ));
        return $this->viewModel;
    }

    public function addAction()
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $locale = $this->params()->fromRoute('locale');

        if (!$locale) {
            $this->getEvent()->getResponse()->setStatusCode(400);
            return $this->viewModel;
        }

        $form = new VatRateForm();

        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $firephp->info('is post');
            $vatRate = new VatRate();
            $form->setInputFilter($vatRate->getInputFilter());
            $form->attachObjectExistsValidator($this->getVatRatesService()->getVatRatesRepository());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $firephp->info('is valid');
                $values = $form->getData();
                $firephp->info($values, '$values');
                $vatRate->populate($values);

                if ($this->getVatRatesService()->addVatRate($vatRate)) {
                    $this->FlashMessenger()->addSuccessMessage('New VAT rate has been successfully added');
                } else {
                    $this->FlashMessenger()->addErrorMessage('Error occurred while adding new VAT rate');
                }

                // Redirect to list of VAT rates
                return $this->redirect()->toRoute('erp/vat-rates', array('locale' => $locale));
            } else {
                $firephp->warn('not valid');
                $values = $form->getData();
                $firephp->info($values, '$values');
                $firephp->error($form->getMessages(), 'error messages');
            }
        }

        $this->viewModel->setVariables(array(
            'form' => $form,
        ));

        $firephp->groupEnd();

        return $this->viewModel;
    }


    /**
     * Method used to inject form handling adding VAT rate.
     *
     * @param VatRateForm $orderLunchForm
     */
    public function setVatRateForm(VatRateForm $form)
    {
        $this->vatRateForm = $form;
    }

    /**
     * Method used to obtain form handling adding VAT rate.
     *
     * @return VatRateForm
     */
    public function getVatRateForm()
    {
        return $this->vatRateForm;
    }

    /**
     * Method used to inject VAT rates service.
     *
     * @param VatRatesService $service
     */
    public function setVatRatesService(VatRatesService $service)
    {
        $this->vatRatesService = $service;
    }

    /**
     * Method used to obtain VAT rates service.
     *
     * @return VatRatesService
     */
    public function getVatRatesService()
    {
        return $this->vatRatesService;
    }
}
