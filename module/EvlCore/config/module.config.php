<?php
namespace EvlCore;

use BrainCore\Service\FileService;
use BrainCore\Service\XLSService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

return array(
    'service_manager' => array(
        'aliases' => array(
            // alias to Zend\Mvc\I18n\Translator, from version 2.2 of ZF
            'translator' => 'MvcTranslator'
        ),
        'factories' => array(
            'LoggerService' => 'EvlCore\Factory\Service\LoggerFactory',
        ),
    ),

    'controllers' => array(
        'factories' => array(
        ),
    ),

    'controller_plugins' => array(
        'invokables' => array(
        ),
        'factories' => array(
        )
    ),

    'validators' => array(
        'factories' => array(
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'view_helpers' => array(
        'factories' => array(
        ),
        'invokables' => array(
            'formBootstrapRow' => 'EvlCore\Form\View\Helper\FormBootstrapRow',
        ),
    ),

    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
                'text_domain' => __NAMESPACE__,
            ),
        ),
    ),

    'navigation' => array(
    ),

    'router' => array(
        'routes' => array(
        ),
    ),

    'doctrine' => array(
        'driver' => array(
        ),
    ),
);
