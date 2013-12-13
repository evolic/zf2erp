<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response;
use Loculus\Mvc\View\Http\BadRequestStrategy;

class Module
{
    /**
     * Module on bootstrap method.
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('route', function ($event) {
            $sm = $event->getApplication()->getServiceManager();
            $config = $event->getApplication()->getServiceManager()->get('Configuration');
            $localesConfig = $config['locales'];
            $locales = $localesConfig['list'];
            $locale = $event->getRouteMatch()->getParam('locale');

            // unsupported locale provided
            if (!in_array($locale, array_keys($locales))
                && $event->getApplication()->getRequest()->getUri()->getPath() !== '/') {

                $locale = $localesConfig['default'];
                $url = $event->getRouter()->assemble(array(
                    'locale' => $localesConfig['default']
                ), array('name' => 'home'));
                $response = $event->getApplication()->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(Response::STATUS_CODE_302);
                $response->sendHeaders();
                exit;
            }

            // If there is no lang parameter in the route, switch to default locale
            if (empty($locale)) {
                $locale = $localesConfig['default'];
            }

            $translator = $sm->get('translator');
            $translator->setLocale($locale);
        }, -10);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /**
         * Log any Uncaught Errors
         */
        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
        $sm = $e->getApplication()->getServiceManager();
        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error', function($event) use ($sm) {
            if ($event->getParam('exception')){
                $sm->get('Zend\Log')->crit($event->getParam('exception'));
            }

            // get view model for layout
            $view = $event->getViewModel();
            $view->setVariable('locale', $sm->get('translator')->getLocale());
        });

        /**
         * Setup Bad request strategy
         */
        $config            = $sm->get('Config');
        $viewManagerConfig = isset($config['view_manager']) && (is_array($config['view_manager']) || $config['view_manager'] instanceof ArrayAccess)
        ? $config['view_manager'] : array();

        /**
         * @var BadRequestStrategy
         */
        $badRequestStrategy   = $sm->get('Loculus\Mvc\View\Http\BadRequestStrategy');

        $displayExceptions       = false;
        $displayBadRequestReason = false;
        $badRequestTemplate      = '400';

        if (isset($viewManagerConfig['display_exceptions'])) {
            $displayExceptions = $viewManagerConfig['display_exceptions'];
        }
        if (isset($viewManagerConfig['display_bad_request_reason'])) {
            $displayBadRequestReason = $viewManagerConfig['display_bad_request_reason'];
        }
        if (isset($viewManagerConfig['bad_request_template'])) {
            $badRequestTemplate = $viewManagerConfig['bad_request_template'];
        }

        $badRequestStrategy->setDisplayExceptions($displayExceptions);
        $badRequestStrategy->setDisplayBadRequestReason($displayBadRequestReason);
        $badRequestStrategy->setBadRequestTemplate($badRequestTemplate);

        $eventManager->attach($badRequestStrategy);
        $sharedManager->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($badRequestStrategy, 'prepareBadRequestViewModel'), -90);
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

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
