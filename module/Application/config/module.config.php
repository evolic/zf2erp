<?php

use Gedmo\Translatable\TranslatableListener;

return array(
    'controllers' => array(
        'invokables' => array(
            'app/index' => 'Application\Controller\IndexController'
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            // alias to Zend\Mvc\I18n\Translator, from version 2.2 of ZF
            'translator' => 'MvcTranslator'
        ),
        'factories' => array(
//             'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'gedmo_translatable_listener' => function ($sm) {
                // Gedmo: attach listener to event manager
                $translatableListener = new TranslatableListener();

                // set the default locale
                $config = $sm->get('Configuration');
                $localesConfig = $config['locales'];
                $defaultLocale = $localesConfig['default'];
                $translatableListener->setDefaultLocale($defaultLocale);

                // will not fallback to default locale translations instead of empty values
                $translatableListener->setTranslationFallback(false);

                return $translatableListener;
            },
        ),
        'services' => array(
            'session' => new Zend\Session\Container('zf2erp'),
        ),
    ),
    'router' => array(
        'routes' => array(
            'choose-lang' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller'    => 'app/index',
                        'action'        => 'index',
                    ),
                ),
            ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/:locale',
                    'constraints' => array(
                        'locale' => '[a-z]{2}(-[A-Z]{2}){0,1}'
                    ),
                    'defaults' => array(
                        'controller' => 'evl-erp/vat-rates',
                        'action'     => 'index'
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '[/:locale]/application',
                    'constraints' => array(
                        'locale' => '[a-z]{2}(-[A-Z]{2}){0,1}'
                    ),
                    'defaults' => array(
                        'controller'    => 'app/index',
                        'action'        => 'zf2',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '[/:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'not_supported_locale' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '[/:locale]/not-supported-locale.html',
                    'constraints' => array(
                        'locale' => '[a-z]{2}(-[A-Z]{2}){0,1}'
                    ),
                    'defaults' => array(
                        'controller'    => 'app/index',
                        'action'        => 'not-supported-locale',
                    ),
                ),
            ),
            'docs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/:locale/docs',
                    'constraints' => array(
                        'locale' => '[a-z]{2}(-[A-Z]{2}){0,1}'
                    ),
                    'defaults' => array(
                        'controller' => 'app/index',
                        'action'     => 'docs'
                    ),
                ),
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'en-US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'bad_request_template'     => 'error/400',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/400'               => __DIR__ . '/../view/error/400.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'Loculus\Mvc\View\Http\BadRequestStrategy',
        ),
    ),

    // Doctrine config
    'doctrine' => array(
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    // pick any listeners you need
//                     'Gedmo\Tree\TreeListener',
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\Sluggable\SluggableListener',
//                     'Gedmo\Loggable\LoggableListener',
//                     'Gedmo\Sortable\SortableListener'
//                     'Gedmo\Translatable\TranslatableListener',
                    'gedmo_translatable_listener',
                ),
            ),
        ),
        'driver' => array(
            'translatable_metadata_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    'vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity',
                ),
            )
        ),
        'orm_default' => array(
            'drivers' => array(
                'Gedmo\Translatable\Entity' => 'translatable_metadata_driver',
            ),
        ),
    ),
);
