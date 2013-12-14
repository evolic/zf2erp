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
use EvlErp\Entity\Country;
use EvlErp\Form\CountryForm;
use EvlErp\Service\CountriesService;
use EvlErp\Validator\CountryNotExists as CountryNotExistsValidator;
use Loculus\Mvc\Controller\DefaultController;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\View\Model\ViewModel;

class CountriesController extends DefaultController
  implements CountriesControllerInterface
{
    /**
     *
     * @var CountryForm
     */
    private $countryForm;

    /**
     *
     * @var CountriesService
     */
    private $countriesService;


    public function indexAction()
    {
        $locale = $this->params()->fromRoute('locale');
        $orderBy = $this->params()->fromRoute('order_by', '');
        $limit = CountriesControllerInterface::DEFAULT_LIMIT_PER_PAGE;

        $criteria = array(
            'limit' => $limit,
            'order_by' => $orderBy,
        );

        $countries = $this->getCountriesService()->getCountriesRepository()->getCountries(
            $criteria, $locale, Query::HYDRATE_OBJECT
        );

        $messages = $this->FlashMessenger()->getSuccessMessages();
        $errors = $this->FlashMessenger()->getErrorMessages();

        $this->viewModel->setVariables(array(
            'countries' => $countries,
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

        $form = new CountryForm();

        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $firephp->info('is post');
            $country = new Country();
            $form->setInputFilter($country->getInputFilter());
            // Validator checking if specified country name is already taken
            $countryNotExistsValidator = $this->getServiceLocator()->get('ValidatorManager')->get('CountryNotExistsValidator');
            /* @var CountryNotExistsValidator $countryNotExistsValidator */
            $countryNotExistsValidator->setLocale($locale);

            $form->attachObjectExistsValidator($countryNotExistsValidator);
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $firephp->info('is valid');
                $values = $form->getData();
                $firephp->info($values, '$values');
                $country->populate($values);

                if ($this->getCountriesService()->addCountry($country, $locale)) {
                    $this->FlashMessenger()->addSuccessMessage('New country has been successfully added');
                } else {
                    $this->FlashMessenger()->addErrorMessage('Error occurred while adding new country');
                }

                // Redirect to list of countries
                return $this->redirect()->toRoute('erp/countries', array('locale' => $locale));
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
     * Method used to inject form handling adding new country.
     *
     * @param CountryForm $orderLunchForm
     */
    public function setCountryForm(CountryForm $form)
    {
        $this->countryForm = $form;
    }

    /**
     * Method used to obtain form handling adding new country.
     *
     * @return CountryForm
     */
    public function getCountryForm()
    {
        return $this->countryForm;
    }

    /**
     * Method used to inject countries service.
     *
     * @param CountriesService $service
     */
    public function setCountriesService(CountriesService $service)
    {
        $this->countriesService = $service;
    }

    /**
     * Method used to obtain countries service.
     *
     * @return CountriesService
     */
    public function getCountriesService()
    {
        return $this->countriesService;
    }
}
