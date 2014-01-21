<?php
namespace EvlErp;

use EvlErp\Controller\CompaniesController;
use EvlErp\Validator\CountryNotExists as CountryNotExistsValidator;
use Zend\Mvc\Controller\ControllerManager;
use EvlErp\Form\Fieldset\CompanyFieldset;

return array(
    'service_manager' => array(
        'factories' => array(
            'CompaniesService' => 'EvlErp\Factory\Service\CompaniesServiceFactory',
            'CountriesService' => 'EvlErp\Factory\Service\CountriesServiceFactory',
            'ProductCategoriesService' => 'EvlErp\Factory\Service\ProductCategoriesServiceFactory',
            'ProductsService' => 'EvlErp\Factory\Service\ProductsServiceFactory',
            'UnitsService' => 'EvlErp\Factory\Service\UnitsServiceFactory',
            'VatRatesService' => 'EvlErp\Factory\Service\VatRatesServiceFactory',
        )
    ),
    'controllers' => array(
        'factories' => array(
            'evl-erp/companies' => 'EvlErp\Factory\Controller\CompaniesControllerFactory',
            'evl-erp/countries' => 'EvlErp\Factory\Controller\CountriesControllerFactory',
            'evl-erp/product-categories' => 'EvlErp\Factory\Controller\ProductCategoriesControllerFactory',
            'evl-erp/products' => 'EvlErp\Factory\Controller\ProductsControllerFactory',
            'evl-erp/units' => 'EvlErp\Factory\Controller\UnitsControllerFactory',
            'evl-erp/vat-rates' => 'EvlErp\Factory\Controller\VatRatesControllerFactory',
        ),
        'invokables' => array(
        ),
    ),
    'validators' => array(
        'factories' => array(
            'CountryNotExistsValidator' => 'EvlErp\Factory\Validator\CountryNotExistsValidatorFactory',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'erp' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/:locale/erp',
                    'defaults' => array(
                        'controller' => 'evl-erp/products',
                        'action'     => 'index',
                        // @TODO page is not supported yet
                        'page'       => 1,
                        'order_by'   => '',
                    ),
                ),
                'child_routes' => array(
                    'companies' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/companies',
                            'defaults' => array(
                                'controller' => 'evl-erp/companies',
                            ),
                        ),
                        'child_routes' => array(
                            'actions' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/:action[/:id][,[:page],[:order_by]].html',
                                    'constraints' => array(
                                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'       => '[0-9]+',
                                        'page'     => '[0-9]+',
                                        'order_by' => '[a-z][a-z_]*',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'countries' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/countries',
                            'defaults' => array(
                                'controller' => 'evl-erp/countries',
                            ),
                        ),
                        'child_routes' => array(
                            'actions' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/:action[/:id][,[:page],[:order_by]].html',
                                    'constraints' => array(
                                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'       => '[0-9]+',
                                        'page'     => '[0-9]+',
                                        'order_by' => '[a-z][a-z_]*',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'product-categories' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/product-categories',
                            'defaults' => array(
                                'controller' => 'evl-erp/product-categories',
                            ),
                        ),
                        'child_routes' => array(
                            'actions' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/:action[/:id][,[:page],[:order_by]].html',
                                    'constraints' => array(
                                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'       => '[0-9]+',
                                        'page'     => '[0-9]+',
                                        'order_by' => '[a-z][a-z_]*',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'products' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/products',
                            'defaults' => array(
                                'controller' => 'evl-erp/products',
                            ),
                        ),
                        'child_routes' => array(
                            'actions' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/:action[/:id][,[:page],[:order_by]].html',
                                    'constraints' => array(
                                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'       => '[0-9]+',
                                        'page'     => '[0-9]+',
                                        'order_by' => '[a-z][a-z_]*',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'ajax-listing' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/ajax-listing',
                                    'defaults' => array(
                                        'action' => 'ajax-listing',
                                    ),
                                ),
                            ),
                            'ajax-calculate-price' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/ajax-calculate-price.json',
                                    'defaults' => array(
                                        'action' => 'ajax-calculate-price',
                                    ),
                                ),
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'units' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/units',
                            'defaults' => array(
                                'controller' => 'evl-erp/units',
                            ),
                        ),
                        'child_routes' => array(
                            'actions' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/:action[/:id][,[:page],[:order_by]].html',
                                    'constraints' => array(
                                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'       => '[0-9]+',
                                        'page'     => '[0-9]+',
                                        'order_by' => '[a-z][a-z_]*',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'vat-rates' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/vat-rates',
                            'defaults' => array(
                                'controller' => 'evl-erp/vat-rates',
                            ),
                        ),
                        'child_routes' => array(
                            'actions' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/:action[/:id][,[:page],[:order_by]].html',
                                    'constraints' => array(
                                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'       => '[0-9]+',
                                        'page'     => '[0-9]+',
                                        'order_by' => '[a-z][a-z_]*',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
                'text_domain' => 'evl-erp'
            ),
            array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
                'text_domain' => 'evl-erp-php'
            ),
        ),
    ),

    // View setup for this module
    'view_manager' => array(
        'template_path_stack' => array(
            'evl-erp' => __DIR__ . '/../view',
        ),
    ),

    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
);
