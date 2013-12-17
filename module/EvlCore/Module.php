<?php

namespace EvlCore;

use EvlCore\Service\LoggerService;
use Zend\Console\Console;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Module on bootstrap method.
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $firephp = \FirePHP::getInstance();

        $eventManager   = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();

        // errors logging
        $sharedManager = $eventManager->getSharedManager();
        $sharedManager->attach('Zend\Mvc\Application', array(MvcEvent::EVENT_DISPATCH_ERROR, MvcEvent::EVENT_RENDER_ERROR), function (MvcEvent $e) use ($serviceManager) {
            if ($e->getParam('exception')) {
                $serviceManager->get('LoggerService')->get(LoggerService::EXCEPTION)->crit($e->getParam('exception'));
//                 if (!Console::isConsole()) {
//                     if (class_exists('\ChromePhp')) {
//                         \ChromePhp::error(get_class($e->getParam('exception')));
//                         \ChromePhp::error($e->getParam('exception'));
//                         \ChromePhp::error($e->getParam('exception')->getTraceAsString());
//                     }
//                     if (class_exists('\FirePHP')) {
//                         $firephp = \FirePHP::getInstance(true);
//                         $firephp->error(get_class($e->getParam('exception')));
//                         $firephp->error($e->getParam('exception')->getMessage());
//                         $firephp->error($e->getParam('exception')->getTraceAsString());
//                     }
//                 }
            }
        }, 100);
    }
}
