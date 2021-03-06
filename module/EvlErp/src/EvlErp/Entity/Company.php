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
 * @property string $address
 * @property string $postcode
 * @property string $city
 * @property string $vatin
 * @property string $ein
 * @property string $bein
 * @property Country $country
 * @property ArrayCollection $products
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
     * Country instance
     *
     * @var EvlErp\Entity\Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="companies")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * @ORM\ManyToMany(targetEntity="EvlErp\Entity\Product", mappedBy="suppliers")
     */
    private $products;


    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    /**
     * @param string $name
     * @return Company
     */
    public function setName($name)
    {
      $this->name = $name;
      return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $city
     * @return Company
     */
    public function setCity($city)
    {
      $this->city = $city;
      return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $postcode
     * @return Company
     */
    public function setPostcode($postcode)
    {
      $this->postcode = $postcode;
      return $this;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $address
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
      return $this->address;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }


    /**
     * Gets VAT Identification Number (e.g. NIP in Poland)
     *
     * @return string
     */
    public function getVatIN()
    {
        return $this->vatin;
    }

    /**
     * Sets VAT Identification Number (e.g. NIP in Poland)
     *
     * @param string $identificationNumber VAT Identification Number
     * @return Company
     */
    public function setVatIN($identificationNumber)
    {
        $this->vatin = $identificationNumber;
        return $this;
    }

    /**
     * Gets Enterprise Identification Number (e.g. REGON in Poland)
     *
     * @return string
     */
    public function getEIN()
    {
        return $this->ein;
    }

    /**
     * Sets Enterprise Identification Number (e.g. REGON in Poland)
     *
     * @param string $identificationNumber Enterprise Identification Number
     * @return Company
     */
    public function setEIN($identificationNumber)
    {
        $this->ein = $identificationNumber;
        return $this;
    }

    /**
     * Gets Business Entity Identification Number (e.g. KRS in Poland)
     *
     * @return string
     */
    public function getBEIN()
    {
        return $this->bein;
    }

    /**
     * Sets Business Entity Identification Number (e.g. KRS in Poland)
     *
     * @param string $identificationNumber Enterprise Identification Number
     * @return Company
     */
    public function setBEIN($identificationNumber)
    {
        $this->bein = $identificationNumber;
        return $this;
    }


    /**
     * Gets the products related to the current company, which is supplier for those products
     *
     * @return ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }


    /**
     * Magic getter to expose private properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save private properties.
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
