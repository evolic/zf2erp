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
use EvlErp\Doctrine\Repository\ProductsRepository;
use EvlErp\Entity\Company;
use EvlErp\Entity\ProductCategory;
use EvlErp\Entity\Unit;
use EvlErp\Entity\VatRate;
use EvlErp\Service\ProductsService;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * A Product entity class
 *
 * @ORM\Entity(repositoryClass="EvlErp\Doctrine\Repository\ProductsRepository")
 * @ORM\Table(
 *   name="products",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="products_unique_code_idx", columns={"code"})}
 * )
 * @Gedmo\SoftDeleteable(fieldName="deleted_at")
 * @ORM\HasLifecycleCallbacks
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property float $price_netto
 * @property float $price_brutto
 * @property string $description
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property ProductCategory $category
 * @property Unit $unit
 * @property VatRate $vatRate
 * @property ArrayCollection $suppliers
 */
class Product
{
    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $code;

    /**
     * @ORM\Column(type="float")
     */
    private $price_netto;

    /**
     * @ORM\Column(type="float")
     */
    private $price_brutto;

    /**
     * @ORM\Column(type="text")
     */
    private $description;


    /**
     * Date of creation
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * Date of last update
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * Date of delete
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;


    /**
     * Product category instance
     *
     * @var EvlErp\Entity\ProductCategory
     * @ORM\ManyToOne(targetEntity="ProductCategory", inversedBy="products")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * Unit instance
     *
     * @var EvlErp\Entity\Unit
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="products")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

    /**
     * VAT rate instance
     *
     * @var EvlErp\Entity\VatRate
     * @ORM\ManyToOne(targetEntity="VatRate", inversedBy="products")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="vat_rate_id", referencedColumnName="id")
     * })
     */
    private $vat_rate;

    /**
     * @ORM\ManyToMany(targetEntity="EvlErp\Entity\Company", inversedBy="products")
     * @ORM\JoinTable(name="product_suppliers",
     *   joinColumns={
     *     @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *   })
     * @var ArrayCollection
     */
    protected $suppliers;


    public function __construct()
    {
        $this->suppliers = new ArrayCollection();
    }


    /**
     * Assigns unique 8-characters product code if not set (on insert)
     *
     * @ORM\PrePersist
     * */
    public function assignUniqueProductCodeOnPrePersist()
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        if ($this->code === null) {
            // generate unique code
            $idx = 0;
            do {
                ++$idx;
                $firephp->info(sprintf('generating unique code (%d)', $idx));
                $code = substr(md5($this->name . microtime().rand()), 0, 8);
                $firephp->info($code, '$code');
                $criteria = array('code' => $code);
            }
            while ($this->getProductsService()->getProductsRepository()->findBy($criteria));

            $firephp->info($code, '$code is unique');
            $this->code = $code;
        } else {
            $firephp->info('nothing to do');
        }

        $firephp->groupEnd();
    }

    /**
     * Calculates brutto price based on netto price and specified VAT rate (on insert and update)
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * */
    public function calculateBruttoPriceOnPrePersist()
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $vatRate = $this->getVatRate()->getValue();
        $this->price_brutto = round($this->price_netto * (100 + $vatRate) / 100, 2);
        $firephp->info($this->price_brutto, '$this->price_brutto');

        $firephp->groupEnd();
    }

    /**
     * @param int $id Product id
     * @return Product
     */
    public function setId($id)
    {
         $this->id = $id;
         return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name Product name
     * @return Product
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

//     /**
//      * @param string $code Product code
//      * @return Product
//      */
//     public function setCode($code)
//     {
//         $this->code = $code;
//         return $this;
//     }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param float $price Product netto price
     * @return Product
     */
    public function setPriceNetto($price)
    {
        $this->price_netto = $price;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceNetto()
    {
        return $this->price_netto;
    }

//     /**
//      * @param float $price Product brutto price
//      * @return Product
//      */
//     public function setPriceBrutto($price)
//     {
//         $this->price_brutto  = $price;
//         return $this;
//     }

    /**
     * @return float
     */
    public function getPriceBrutto()
    {
        return $this->price_brutto;
    }

    /**
     * @param string $code Product description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $format
     * @return \DateTime
     */
    public function getCreatedAt($format = 'Y-m-d H:i:s')
    {
        return $this->created_at->format($format);
    }

    /**
     * @param string $format
     * @return \DateTime
     */
    public function getUpdatedAt($format = 'Y-m-d H:i:s')
    {
        return $this->updated_at->format($format);
    }


    public function setCategory(ProductCategory $category)
    {
      $this->category = $category;
      return $this;
    }

    /**
     * @return ProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setUnit(Unit $unit)
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    public function setVatRate(VatRate $vat_rate)
    {
        $this->vat_rate = $vat_rate;
        return $this;
    }

    /**
     * @return VatRate
     */
    public function getVatRate()
    {
        return $this->vat_rate;
    }


    /**
     * Gets the companies (supplier) related to the current product
     *
     * @return ArrayCollection
     */
    public function getSuppliers()
    {
        return $this->suppliers;
    }


    public function addSuppliers(Collection $suppliers)
    {
        foreach ($suppliers as $supplier) {
            /* @var $supplier Company */
            $supplier->getProducts()->add($this);
            $this->getSuppliers()->add($supplier);
        }
    }

    public function removeSuppliers(Collection $suppliers)
    {
        foreach ($suppliers as $supplier) {
            /* @var $supplier Company */
            $supplier->getProducts()->add(null);
            $this->getSuppliers()->remove($supplier);
        }
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
     * Method used to inject products service.
     *
     * @param ProductsService $service
     */
    public function setProductsService(ProductsService $service)
    {
        $this->productsService = $service;
    }

    /**
     * Method used to obtain products service.
     *
     * @return ProductsService
     */
    public function getProductsService()
    {
        return $this->productsService;
    }
}
