<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use EvlErp\Entity\country;
use Gedmo\Translatable\TranslatableListener;

/**
 * Class CountriesRepository - orders repository. Provides additional database query methods.
 *
 * @package EvlErp\Doctrine\Repository
 */
class CountriesRepository extends EntityRepository
  implements CountriesRepositoryInterface
{
    /**
     * Method used to obtain available countries from the database
     *
     * @param array $criteria - additional criteria
     * @param string $locale - country name locale
     * @param int $hydrate - result hydration mode
     * @return array - available countries
     */
    public function getCountries($criteria, $locale, $hydrate = Query::HYDRATE_OBJECT)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
            ->from('EvlErp\Entity\country', 'c')
        ;

        if (isset($criteria['order_by']) && $criteria['order_by']) {
            switch ($criteria['order_by']) {
                case 'name':
                    $qb->orderBy('c.' . $criteria['order_by']);
                    break;
            }
        } else {
            $qb->orderBy('c.name', 'ASC');
        }
        if (isset($criteria['limit']) && $criteria['limit']) {
            $qb->setMaxResults($criteria['limit']);
        }

        $firephp->info($qb->getDQL(), 'DQL');

        $query = $qb->getQuery();

        $firephp->info($query->getSQL(), 'SQL');

        // set the translation query hint
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        // locale
        $query->setHint(
            TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $locale // take locale from session or request etc.
        );
        // fallback
//         $query->setHint(
//             \Gedmo\Translatable\TranslatableListener::HINT_FALLBACK,
//             1 // fallback to default values in case if record is not translated
//         );
        $query->setHint(TranslatableListener::HINT_FALLBACK, false);

        $firephp->groupEnd();

        return $query->getResult($hydrate);
    }

    /**
     * Method used to get country from the database by it's name and locale
     *
     * @param array $criteria - additional criteria
     * @param string $locale - country name locale
     * @param int $hydrate - result hydration mode
     * @return array - available countries
     */
    public function findCountry($name, $locale, $hydrate = Query::HYDRATE_OBJECT)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
            ->from('EvlErp\Entity\country', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $name)
        ;

        $firephp->info($qb->getDQL(), 'DQL');

        $query = $qb->getQuery();
        $firephp->info($query->getSQL(), 'SQL');

        // set the translation query hint
        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        // locale
        $query->setHint(
            TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $locale // take locale from session or request etc.
        );
        // use INNER JOIN instead of LEFT OUTER
        $query->setHint(TranslatableListener::HINT_INNER_JOIN, true);

        // query can return no result if country name is not taken
        $result = $query->getResult($hydrate);

        $firephp->groupEnd();

        return array_shift($result);
    }
}
