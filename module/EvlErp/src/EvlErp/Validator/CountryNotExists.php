<?php

namespace EvlErp\Validator;

use DoctrineModule\Validator\NoObjectExists;
use EvlErp\Doctrine\Repository\CountriesRepository;

class CountryNotExists extends NoObjectExists
{
    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_OBJECT_FOUND    => "There is already country with specified name: '%value%'",
    );


    /**
     * Locale used to determine if name of country is already taken
     * @var string
     */
    protected $locale;

    /**
     * Country-Object-Repository from which to search for entities
     *
     * @var CountriesRepository
     */
    protected $objectRepository;


    /**
     * {@inheritDoc}
     */
    public function isValid($value)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->info(__METHOD__);
        $firephp->info($value, '$value');
        $firephp->info( $this->getLocale(), 'locale');

        $match = $this->objectRepository->findCountry($value, $this->getLocale());

        if (is_object($match)) {
            $firephp->info('object found');
            $firephp->info(get_class($match), 'get_class($match)');
            $this->error(self::ERROR_OBJECT_FOUND, $value);

            return false;
        }

        return true;
    }


    /**
     * Retrieve locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set locale used to determine if name of country is already taken
     *
     * @param  string $locale
     * @return Country
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
