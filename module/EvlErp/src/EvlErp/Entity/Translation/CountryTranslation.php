<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Table(name="countries_translations", indexes={
 *      @ORM\Index(name="countries_translations_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class CountryTranslation extends AbstractTranslation
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer");
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
    protected $id;

    /**
     * @var int $foreignKey
     *
     * @ORM\Column(name="foreign_key", type="integer")
     */
    protected $foreignKey;


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
