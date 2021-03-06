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
use EvlErp\Entity\Product;
use EvlErp\Form\ProductForm;
use EvlErp\Form\ProductPriceForm;
use EvlErp\Service\ProductsService;
use Loculus\Mvc\Controller\DefaultController;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\View\Model\JsonModel as JsonViewModel;
use Zend\View\Model\ViewModel;

class ProductsController extends DefaultController
  implements ProductsControllerInterface
{
    /**
     *
     * @var ProductForm
     */
    private $productForm;

    /**
     *
     * @var ProductPriceForm
     */
    private $productPriceForm;

    /**
     *
     * @var ProductsService
     */
    private $productsService;


    /**
     * Lists products
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $locale  = $this->params()->fromRoute('locale');
        $page    = (int) $this->params()->fromRoute('page', 1);
        $limit   = ProductsControllerInterface::DEFAULT_LIMIT_PER_PAGE;
        $orderBy = $this->params()->fromRoute('order_by', '');
        $sortDir = 'ASC';

        $criteria = array(
            'order_by' => $orderBy,
            'sort_dir' => $sortDir,
        );

        $list = $this->getProductsService()->getProductsRepository()->getProducts(
            $criteria, Query::HYDRATE_ARRAY
        );

        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber(1);
        Paginator::setDefaultItemCountPerPage($limit);
        Paginator::setDefaultScrollingStyle('Sliding');

        $messages = $this->FlashMessenger()->getSuccessMessages();
        $errors = $this->FlashMessenger()->getErrorMessages();

        $this->viewModel->setVariables(array(
            'paginator' => $paginator,
            'messages' => $messages,
            'errors' => $errors,
        ));
        return $this->viewModel;
    }

    /**
     * Gets products list via ajax call from jQuery Data Table
     *
     * @return JsonViewModel
     */
    public function ajaxListingAction()
    {
        $locale  = $this->params()->fromRoute('locale');
        $keyword = $this->params()->fromQuery('sSearch');
        if ($keyword) {
            // filter data
            $stringTrimFilter = new \Zend\Filter\StringTrim();
            $stripTagsFilter = new \Zend\Filter\StripTags();

            $keyword = $stringTrimFilter->filter($keyword);
            $keyword = $stripTagsFilter->filter($keyword);
            // protect against SQL injection
//             $keyword = addslashes($keyword);
        }

        // DataTable params
        $sEcho = (int) $this->params()->fromQuery('sEcho', 0);
        $iDisplayLength = (int) $this->params()->fromQuery('iDisplayLength', ProductsControllerInterface::DEFAULT_LIMIT_PER_PAGE);
        $iDisplayStart  = (int) $this->params()->fromQuery('iDisplayStart', 0);
        if ($iDisplayStart === 0) {
            $page = 1;
        } else {
            $page = ($iDisplayStart / $iDisplayLength) + 1;
        }
        // sorting data
        switch ($this->params()->fromQuery('iSortCol_0')) {
            case 2:
                $orderBy = 'name';
                break;
            case 3:
                $orderBy = 'category_name';
                break;
            case 4:
            case 5:
                $orderBy = 'price';
                break;
            default:
                $orderBy = '';
        }
        switch ($this->params()->fromQuery('sSortDir_0')) {
            case 'desc':
                $sortDir = 'DESC';
                break;
            case 'asc':
            default:
                $sortDir = 'ASC';
        }

        $criteria = array(
            'keyword' => $keyword,
            'order_by' => $orderBy,
            'sort_dir' => $sortDir,
        );
        $list = $this->getProductsService()->getProductsRepository()->getProducts($criteria);

        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page);
        Paginator::setDefaultItemCountPerPage($iDisplayLength);

        return new JsonViewModel(array(
            'sEcho' => $sEcho,
            'iTotalRecords' => $paginator->getTotalItemCount(),
            'iTotalDisplayRecords' => $paginator->getTotalItemCount(),
            'aaData' => $this->_createProductsArray($paginator->getItemsByPage($page), $locale),
        ));
    }

    /**
     * Gets product price (brutto/netto) via ajax call
     *
     * @return JsonViewModel
     */
    public function ajaxCalculatePriceAction()
    {
        // translator
        $translator = $this->getServiceLocator()->get('translator');

        $result = array(
            'success' => false,
            'message' => $translator->translate('Wrong usage of the method', 'evl-core'),
        );

        $locale  = $this->params()->fromRoute('locale');

        if (!$locale) {
            $this->getEvent()->getResponse()->setStatusCode(400);
            return new JsonViewModel($result);
        }

        $form    = $this->getProductPriceForm();
        $request = $this->getRequest();
        $price   = false;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $product = $form->getData();
                /* @var $product Product */

                $price = $this->getProductsService()->calculatePrice($product);
            } else {
                $messages = $form->getMessages();

                $this->getEvent()->getResponse()->setStatusCode(400);
                // Cannot calculate price for specified values
                $result['message'] = $translator->translate(
                    'Invalid params of the request', 'evl-core'
                );
                $result['messages'] = $messages;

                return new JsonViewModel($result);
            }
        }

        return new JsonViewModel(array(
            'success' => true,
            'price' => $price,
            'message' => $translator->translate('OK (calculated)', 'evl-core'),
        ));
    }

    /**
     * Method used to create products array that will be transformed into JSON DataTable format.
     *
     * @param  $list - product entities array
     * "
     * @return array
     */
    private function _createProductsArray($products, $locale)
    {
        // translator
        $translator = $this->getServiceLocator()->get('translator');

        $list = array();

        $i = 0;

        foreach ($products as $product) {
            /* @var $product Product */
            $idx = 0;

            $url = $this->url()->fromRoute(
                'erp/products/actions', array(
                    'locale' => $locale,
                    'action'=>'edit',
                    'id' => $product->getId(),
                )
            );
            $name  = $product->getName();
            $title = sprintf($translator->translate('Edit: %s'), $name);
            $link  = sprintf('<a href="%s" title="%s">%s</a>', $url, $title, $name);
            $updated_at = substr($product->getUpdatedAt(), 0, 16);

            $list[] = array(
                'DT_RowId' => 'row-' . $product->getId(),
                'DT_RowClass' => 'product-row',
                $idx++ => ++$i, // #
                $idx++ => $product->getId(), // product id
                $idx++ => $link, // product name
                $idx++ => $product->getCategory()->getName(), // product category name
                $idx++ => $product->getCode(), // product code
                $idx++ => number_format ($product->getPriceNetto(), 2, '.', ' ') . ' PLN', // price (netto)
                $idx++ => number_format ($product->getPriceBrutto(), 2, '.', ' ') . ' PLN', // price (brutto)
                $idx++ => $updated_at, // date of last modification
                $idx++ => '',
            );
        }

        return $list;
    }

    /**
     * Handles adding new products
     *
     * @return ViewModel
     */
    public function addAction()
    {
        $locale = $this->params()->fromRoute('locale');

        if (!$locale) {
            $this->getEvent()->getResponse()->setStatusCode(400);
            return $this->viewModel;
        }

        $form = $this->getProductForm();
        $form->setMode(ProductForm::MODE_ADD);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $product = $form->getData();
                /* @var $product Product */

                // assign services to Product Entity
                $product->setProductsService($this->getServiceLocator()->get('ProductsService'));

                if ($this->getProductsService()->addProduct($product)) {
                    $this->FlashMessenger()->addSuccessMessage('New product has been successfully added');
                } else {
                    $this->FlashMessenger()->addErrorMessage('Error occurred while adding new product');
                }

                // Redirect to list of products
                return $this->redirect()->toRoute('erp/products', array('locale' => $locale));
            }
        }

        $this->viewModel->setVariables(array(
            'form' => $form,
        ));

        return $this->viewModel;
    }

    /**
     * Handles products editing
     *
     * @return ViewModel
     */
    public function editAction()
    {
        $locale = $this->params()->fromRoute('locale');
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$locale || !$id) {
            $this->getEvent()->getResponse()->setStatusCode(400);
            return $this->viewModel;
        }

        $product = $this->getProductsService()->getProductsRepository()->find($id);

        if (!$product) {
            $this->getEvent()->getResponse()->setStatusCode(404);
            return $this->viewModel;
        }

        $form = $this->getProductForm();
        $form->setMode(ProductForm::MODE_EDIT);
        /* @var $form ProductForm */
        $form->bind($product);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $product = $form->getData();
                /* @var $product Product */

                // assign services to Product Entity
                $product->setProductsService($this->getServiceLocator()->get('ProductsService'));

                if ($this->getProductsService()->updateProduct($product)) {
                    $this->FlashMessenger()->addSuccessMessage('Product data have been successfully updated');
                } else {
                    $this->FlashMessenger()->addErrorMessage('Error occurred while updating product');
                }

                // Redirect to list of products
                return $this->redirect()->toRoute('erp/products', array('locale' => $locale));
            }
        }

        $this->viewModel->setVariables(array(
            'form' => $form,
            'product' => $product,
        ));

        return $this->viewModel;
    }


    /**
     * Method used to inject form handling adding new product.
     *
     * @param ProductForm $form
     */
    public function setProductForm(ProductForm $form)
    {
        $this->productForm = $form;
    }

    /**
     * Method used to obtain form handling adding new product.
     *
     * @return ProductForm
     */
    public function getProductForm()
    {
        return $this->productForm;
    }

    /**
     * Method used to inject form handling adding new product.
     *
     * @param ProductPriceForm $form
     */
    public function setProductPriceForm(ProductPriceForm $form)
    {
        $this->productPriceForm = $form;
    }

    /**
     * Method used to obtain form handling adding new product.
     *
     * @return ProductPriceForm
     */
    public function getProductPriceForm()
    {
        return $this->productPriceForm;
    }

    /**
     * Method used to inject products service.
     *
     * @param ProductsService $service
     */
    public function setProductsService(ProductsService $service)
    {
        $this->productsService = $service;
    }

    /**
     * Method used to obtain products service.
     *
     * @return ProductsService
     */
    public function getProductsService()
    {
        return $this->productsService;
    }
}
