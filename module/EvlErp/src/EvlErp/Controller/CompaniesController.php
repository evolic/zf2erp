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
use EvlErp\Entity\Company;
use EvlErp\Form\CompanyForm;
use EvlErp\Service\CompaniesService;
use Loculus\Mvc\Controller\DefaultController;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\View\Model\ViewModel;

class CompaniesController extends DefaultController
  implements CompaniesControllerInterface
{
    /**
     *
     * @var CompanyForm
     */
    private $companyForm;

    /**
     *
     * @var CompaniesService
     */
    private $companiesService;


    public function indexAction()
    {
        $locale = $this->params()->fromRoute('locale');
        $orderBy = $this->params()->fromRoute('order_by', '');
        $limit = CompaniesControllerInterface::DEFAULT_LIMIT_PER_PAGE;

        $criteria = array(
            'limit' => $limit,
            'order_by' => $orderBy,
        );

        $companies = $this->getCompaniesService()->getCompaniesRepository()->getCompanies(
            $criteria, Query::HYDRATE_ARRAY
        );

        $messages = $this->FlashMessenger()->getSuccessMessages();
        $errors = $this->FlashMessenger()->getErrorMessages();

        $this->viewModel->setVariables(array(
            'companies' => $companies,
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

        $form = $this->getCompanyForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                /* @var $company Company */
                $company = $form->getData();

                // translator
                $translator = $this->getServiceLocator()->get('translator');

                if ($this->getCompaniesService()->addCompany($company)) {
                    $this->FlashMessenger()->addSuccessMessage(
                        $translator->translate('New company has been successfully added', 'evl-erp')
                    );
                } else {
                    $this->FlashMessenger()->addErrorMessage(
                        $translator->translate('Error occurred while adding new unit', 'evl-erp')
                    );
                }

                // Redirect to list of companies
                return $this->redirect()->toRoute('erp/companies', array('locale' => $locale));
            }
        }

        $this->viewModel->setVariables(array(
            'form' => $form,
        ));

        return $this->viewModel;
    }


    /**
     * Method used to inject form handling adding new company.
     *
     * @param CompanyForm $form
     */
    public function setCompanyForm(CompanyForm $form)
    {
        $this->companyForm = $form;
    }

    /**
     * Method used to obtain form handling adding new company.
     *
     * @return CompanyForm
     */
    public function getCompanyForm()
    {
        return $this->companyForm;
    }

    /**
     * Method used to inject companies service.
     *
     * @param CompaniesService $service
     */
    public function setCompaniesService(CompaniesService $service)
    {
        $this->companiesService = $service;
    }

    /**
     * Method used to obtain companies service.
     *
     * @return CompaniesService
     */
    public function getCompaniesService()
    {
        return $this->companiesService;
    }
}
