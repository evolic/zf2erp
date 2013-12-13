<?php
namespace EvlCore\Factory\Service;

use EvlCore\Service\LoggerService;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LoggerFactory - factory used to create LoggerService.
 *
 * @package EvlCore\Factory\Service
 */
class LoggerFactory implements FactoryInterface
{
    /**
     * Factory method.
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoggerService|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $router = $serviceLocator->get('Router');
        $request = $serviceLocator->get('Request');
        $matched = $router->match($request);
        $subscriptionDomain = null;
        $path = "./data/log";

        // create logger service
        $loggerService = new LoggerService();

        // cron logging is global
        if ($path === "./data/log") {
            // cron logger
            $cronLogger = new Logger();
            $writer = new Stream($path . '/cron.log');
            $cronLogger->addWriter($writer);
            $loggerService->add(LoggerService::CRON, $cronLogger);
        }
        // exception logger
        $exceptionLogger = new Logger();
        $writer = new Stream($path . '/error.log');
        $exceptionLogger->addWriter($writer);
        $loggerService->add(LoggerService::EXCEPTION, $exceptionLogger);
        // mailing logger
        $mailingLogger = new Logger();
        $writer = new Stream($path . '/mailing.log');
        $mailingLogger->addWriter($writer);
        $loggerService->add(LoggerService::MAILING, $mailingLogger);
        // subscription manager logger
        $managerLogger = new Logger();
        $writer = new Stream($path . '/action.log');
        $managerLogger->addWriter($writer);
        $loggerService->add(LoggerService::ACTION, $managerLogger);
        // access logger
        $managerAccessLogger = new Logger();
        $writer = new Stream($path . '/access.log');
        $managerAccessLogger->addWriter($writer);
        $loggerService->add(LoggerService::ACCESS, $managerAccessLogger);
        // upload logger
        $fileUploadLogger = new Logger();
        $writer = new Stream($path . '/file-upload.log');
        $fileUploadLogger->addWriter($writer);
        $loggerService->add(LoggerService::FILE_UPLOAD, $fileUploadLogger);
        // acl deny
        $aclDenyLogger = new Logger();
        $writer = new Stream($path . '/acl-deny.log');
        $aclDenyLogger->addWriter($writer);
        $loggerService->add(LoggerService::ACL_DENY, $aclDenyLogger);
        // alert
        $alertLogger = new Logger();
        $writer = new Stream($path . '/alert.log');
        $alertLogger->addWriter($writer);
        $loggerService->add(LoggerService::ALERT, $alertLogger);
        return $loggerService;
    }
}
