<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace EvlCore\Stdlib\Hydrator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineObjectHydrator;
use RuntimeException;

/**
 * This hydrator has been completely refactored for DoctrineModule 0.7.0. It provides an easy and powerful way
 * of extracting/hydrator objects in Doctrine, by handling most associations types.
 *
 * Starting from DoctrineModule 0.8.0, the hydrator can be used multiple times with different objects
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.7.0
 * @author  Michael Gallego <mic.gallego@gmail.com>
 */
class DoctrineObject extends DoctrineObjectHydrator
{
    /**
     * @var bool
     */
    protected $fixUnderscoreGetters = false;

    /**
     * @var bool
     */
    protected $fixUnderscoreSetters = false;


    /**
     * Extract values from an object using a by-value logic (this means that it uses the entity
     * API, in this case, getters)
     *
     * @param  object $object
     * @throws RuntimeException
     * @return array
     */
    protected function extractByValue($object)
    {
        \ChromePhp::log(__METHOD__);

        $fieldNames = array_merge($this->metadata->getFieldNames(), $this->metadata->getAssociationNames());
        $methods    = get_class_methods($object);
        $filter     = $object instanceof FilterProviderInterface
            ? $object->getFilter()
            : $this->filterComposite;

        $data = array();
        foreach ($fieldNames as $fieldName) {
            \ChromePhp::log('processing: ' . $fieldName);
            if ($filter && !$filter->filter($fieldName)) {
                continue;
            }

            if ($this->getFixUnderscoreGetters()) {
                $fixedFieldName = str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));
                $getter = 'get' . $fixedFieldName;
                $isser  = 'is' . $fixedFieldName;
            } else {
                $getter = 'get' . ucfirst($fieldName);
                $isser  = 'is' . ucfirst($fieldName);
            }

            \ChromePhp::log($getter);
            \ChromePhp::log(in_array($getter, $methods));
            \ChromePhp::log($isser);

            if (in_array($getter, $methods)) {
                $data[$fieldName] = $this->extractValue($fieldName, $object->$getter(), $object);
                \ChromePhp::log($data[$fieldName]);
            } elseif (in_array($isser, $methods)) {
                $data[$fieldName] = $this->extractValue($fieldName, $object->$isser(), $object);
            }

            // Unknown fields are ignored
        }

        return $data;
    }

    /**
     * Hydrate the object using a by-value logic (this means that it uses the entity API, in this
     * case, setters)
     *
     * @param  array  $data
     * @param  object $object
     * @throws RuntimeException
     * @return object
     */
    protected function hydrateByValue(array $data, $object)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->info(__METHOD__);

        $tryObject = $this->tryConvertArrayToObject($data, $object);
        $metadata  = $this->metadata;

        if (is_object($tryObject)) {
          $object = $tryObject;
        }

        foreach ($data as $field => $value) {
            $value  = $this->handleTypeConversions($value, $metadata->getTypeOfField($field));

            if ($this->getFixUnderscoreSetters()) {
                $fixedFieldName = str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
                $setter = 'set' . $fixedFieldName;
            } else {
                $setter = 'set' . ucfirst($field);
            }

            \ChromePhp::log($setter);

            $firephp->info($field, '$field');
            $firephp->info($value, '$value');
            $firephp->info($setter, '$setter');

            if ($metadata->hasAssociation($field)) {
                  $target = $metadata->getAssociationTargetClass($field);

                  if ($metadata->isSingleValuedAssociation($field)) {
                      if (! method_exists($object, $setter)) {
                          continue;
                      }

                      $value = $this->toOne($target, $this->hydrateValue($field, $value, $data));

                      if (null === $value
                      && !current($metadata->getReflectionClass()->getMethod($setter)->getParameters())->allowsNull()
                      ) {
                          continue;
                      }

                      $object->$setter($value);
                  } elseif ($metadata->isCollectionValuedAssociation($field)) {
                      $this->toMany($object, $field, $target, $value);
                  }
              } else {
                  if (! method_exists($object, $setter)) {
                      continue;
                  }

                  $object->$setter($this->hydrateValue($field, $value, $data));
            }
        }

        return $object;
    }

    /**
     * @param bool $fix
     * @return DoctrineObject
     */
    public function setFixUnderscoreGetters($fix)
    {
        \ChromePhp::log(__METHOD__);
        \ChromePhp::log($fix);
        $this->fixUnderscoreGetters = $fix;
        return $this;
    }

    /**
     * @return bool
     */
    public function getFixUnderscoreGetters()
    {
        return $this->fixUnderscoreGetters;
    }

    /**
     * @param bool $fix
     * @return DoctrineObject
     */
    public function setFixUnderscoreSetters($fix)
    {
        \ChromePhp::log(__METHOD__);
        \ChromePhp::log($fix);
        $this->fixUnderscoreSetters = $fix;
        return $this;
    }

    /**
     * @return bool
     */
    public function getFixUnderscoreSetters()
    {
        return $this->fixUnderscoreSetters;
    }
}
