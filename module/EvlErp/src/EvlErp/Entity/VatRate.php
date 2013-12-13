<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use EvlErp\Doctrine\Repository\VatRatesRepository;
use NumberFormatter;
use Zend\I18n\Filter\NumberFormat;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Between as BetweenValidator;

/**
 * A VAT rate entity class
 *
 * @ORM\Entity(repositoryClass="EvlErp\Doctrine\Repository\VatRatesRepository")
 * @ORM\Table(name="vat_rates")
 * @property int $id
 * @property float $value
 */
class VatRate implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="float")
     */
    protected $value;

//     /**
//      * Products using specified VAT rate
//      *
//      * @var Doctrine\Common\Collections\ArrayCollection $meals
//      * @ORM\OneToMany(targetEntity="Product", mappedBy="vat_rate", cascade={"persist","remove"})
//      */
//     protected $products;


    public function __construct()
    {
//         $this->products = new ArrayCollection();
    }


    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
        return $this;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->id    = isset($data['id']) ? $data['id'] : null;
        $this->value = $data['value'];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'       => 'id',
                'required'   => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'value',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    new NumberFormat("en_US", NumberFormatter::TYPE_DOUBLE)
                ),
                'validators' => array(
                    array(
                        'name' => 'Float',
                    ),
                    array (
                        'name' => 'Between',
                        'options' => array (
                            'min' => 0,
                            'max' => 100,
                            'inclusive' => true,
                            'messages' => array(
                                BetweenValidator::NOT_BETWEEN_STRICT => 'Vat rate must be between %min% and %max%',
                            )
                        )
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
