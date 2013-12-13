<?php
namespace EvlErp;

return array(
    'service_manager' => array(
        'factories' => array(
            'VatRatesService' => 'EvlErp\Factory\Service\VatRatesServiceFactory',
        )
    ),
    'controllers' => array(
        'factories' => array(
            'evl-erp/vat-rates' => 'EvlErp\Factory\Controller\VatRatesControllerFactory',
//             'evl-erp/products' => 'EvlErp\Factory\Controller\ProductsControllerFactory',
        ),
        'invokables' => array(
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
//                     'products' => array(
//                         'type' => 'literal',
//                         'options' => array(
//                             'route' => '/vat-rates',
//                             'defaults' => array(
//                                 'controller' => 'evl-erp/products',
//                             ),
//                         ),
//                         'child_routes' => array(
//                             'actions' => array(
//                                 'type' => 'segment',
//                                 'options' => array(
//                                     'route' => '/:action[/:id][,[:page],[:order_by]].html',
//                                     'constraints' => array(
//                                         'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                         'id'       => '[0-9]+',
//                                         'page'     => '[0-9]+',
//                                         'order_by' => '[a-z][a-z_]*',
//                                     ),
//                                 ),
//                                 'may_terminate' => true,
//                             ),
//                         ),
//                         'may_terminate' => true,
//                     ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
                'text_domain' => 'album'
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