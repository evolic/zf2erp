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
use EvlErp\Doctrine\Repository\UnitsRepository;
use EvlErp\Entity\Country;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A Company entity class
 *
 * @ORM\Entity(repositoryClass="EvlErp\Doctrine\Repository\CompaniesRepository")
 * @ORM\Table(name="companies")
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string $postcode
 * @property string $address
 */
class Company implements InputFilterAwareInterface
{
    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=63)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=63)
     */
    private $address;

    /**
     * VAT Identification Number (e.g. NIP in Poland)
     *
     * @ORM\Column(type="string", length=31)
     */
    private $vatin;

    /**
     * Enterprise Identification Number (e.g. REGON in Poland)
     *
     * @ORM\Column(type="string", length=31)
     */
    private $ein;

    /**
     * Business Entity Identification Number (e.g. KRS in Poland)
     * > Sole proprietorship
     * > Partnership
     * > Corporation
     * > Cooperative
     *
     * @ORM\Column(type="string", length=31, nullable=true)
     */
    private $bein;


    /**
     * Cuisine instance
     *
     * @var EvlErp\Entity\Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="companies")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    protected $country;

//     /**
//      * Products using specified unit
//      *
//      * @var Doctrine\Common\Collections\ArrayCollection $products
//      * @ORM\OneToMany(targetEntity="Product", mappedBy="unit", cascade={"persist","remove"})
//      */
//     protected $products;


    public function __construct()
    {
//         $this->products = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }


    /**
     * @return string
     */
    public function getVatIN()
    {
        return $this->vatid;
    }

    /**
     * @return string
     */
    public function getEIN()
    {
        return $this->ein;
    }

    /**
     * @return string
     */
    public function getBEIN()
    {
        return $this->bein;
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
        $this->id       = isset($data['id']) ? $data['id'] : null;
        $this->name     = $data['name'];
        $this->city     = $data['city'];
        $this->postocde = $data['postocde'];
        $this->address  = $data['address'];
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
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 127,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'city',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 2,
                            'max'      => 63,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'postcode',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                            'max'      => 7,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'address',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 2,
                            'max'      => 63,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
