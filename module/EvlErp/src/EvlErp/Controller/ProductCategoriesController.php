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
use EvlErp\Entity\ProductCategory;
use EvlErp\Form\ProductCategoryForm;
use EvlErp\Service\ProductCategoriesService;
use Loculus\Mvc\Controller\DefaultController;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\View\Model\ViewModel;

class ProductCategoriesController extends DefaultController
  implements ProductCategoriesControllerInterface
{
    /**
     *
     * @var ProductCategoryForm
     */
    private $productCategoryForm;

    /**
     *
     * @var ProductCategoriesService
     */
    private $productCategoriesService;


    public function indexAction()
    {
        $locale = $this->params()->fromRoute('locale');
        $orderBy = $this->params()->fromRoute('order_by', '');
        $limit = ProductCategoriesControllerInterface::DEFAULT_LIMIT_PER_PAGE;

        $criteria = array(
            'limit' => $limit,
            'order_by' => $orderBy,
        );

        $productCategories = $this->getProductCategoriesService()->getProductCategoriesRepository()->getProductCategories(
            $criteria, Query::HYDRATE_ARRAY
        );

        $messages = $this->FlashMessenger()->getSuccessMessages();
        $errors = $this->FlashMessenger()->getErrorMessages();

        $this->viewModel->setVariables(array(
            'productCategories' => $productCategories,
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

        $form = new ProductCategoryForm();

        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $productCategory = new ProductCategory();
            $form->setInputFilter($productCategory->getInputFilter());
            $form->attachObjectExistsValidator($this->getProductCategoriesService()->getProductCategoriesRepository());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $values = $form->getData();
                $productCategory->populate($values);

                if ($this->getProductCategoriesService()->addProductCategory($productCategory)) {
                    $this->FlashMessenger()->addSuccessMessage('New product category has been successfully added');
                } else {
                    $this->FlashMessenger()->addErrorMessage('Error occurred while adding new product category');
                }

                // Redirect to list of productCategories
                return $this->redirect()->toRoute('erp/product-categories', array('locale' => $locale));
            }
        }

        $this->viewModel->setVariables(array(
            'form' => $form,
        ));

        return $this->viewModel;
    }


    /**
     * Method used to inject form handling adding new product category.
     *
     * @param ProductCategoryForm $orderLunchForm
     */
    public function setProductCategoryForm(ProductCategoryForm $form)
    {
        $this->productCategoryForm = $form;
    }

    /**
     * Method used to obtain form handling adding new product category.
     *
     * @return ProductCategoryForm
     */
    public function getProductCategoryForm()
    {
        return $this->productCategoryForm;
    }

    /**
     * Method used to inject productCategories service.
     *
     * @param ProductCategoriesService $service
     */
    public function setProductCategoriesService(ProductCategoriesService $service)
    {
        $this->productCategoriesService = $service;
    }

    /**
     * Method used to obtain productCategories service.
     *
     * @return ProductCategoriesService
     */
    public function getProductCategoriesService()
    {
        return $this->productCategoriesService;
    }
}
