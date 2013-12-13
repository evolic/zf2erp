<?php

namespace EvlCore\Service;

use Zend\Log\Logger;

/**
 * Class LoggerService - logger multiton service.
 *
 * @package EvlCore\Service
 */
class LoggerService
{
    /**
     * Const logger names
     */
    const EXCEPTION = "exception";
    const ACCESS = "access";
    const ACTION = "action";
    const FILE_UPLOAD = "file_upload";
    const MAILING = "mailing";
    const ACL_DENY = "acl_deny";
    const CRON = "cron";
    const ALERT = "alert";


    /**
     * Logger multiton
     * @var array
     */
    private $loggers = array();


    /**
     * Method used to add new logger instance.
     *
     * @param $key - logger key
     * @param Logger $logger - logger object
     */
    public function add($key, Logger $logger)
    {
        if (!$this->exist($key)) {
            $this->loggers[$key] = $logger;
        }
    }

    /**
     * Method used to obtain registered logger instance.
     *
     * @param $key - logger key
     * @return Logger - logger object
     */
    public function get($key)
    {
        return $this->loggers[$key];
    }

    /**
     * Method check if given logger exist in multition.
     *
     * @param $key - logger key
     * @return bool - true if exist
     */
    public function exist($key)
    {
        foreach (array_keys($this->loggers) as $logger) {
            if ($key === $logger) {
                return true;
            }
        }

        return false;
    }
}
