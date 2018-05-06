<?php declare(strict_types=1);

namespace Benrowe\Properties;

/**
 * Property Trait
 * A convenience trait to bolt-in the property manager into an existing class
 *
 * @package Benrowe\Properties
 * @method Property addProperty(string $name, string $type = null, mixed $default = null)
 * @method Property getProperty(string $name)
 * @method bool hasProperty(string $name)
 * @method Property[] allProperties()
 * @method bool removeProperty(string $name)
 * @method mixed getValue(string $name)
 * @method void setValue(string $name, $value)
 * @method array allValues()
 */
trait PropertyTrait
{
    private $propertyManager;

    /**
     * Conveniently delegate any method calls to the property manager if they
     * don't exist in the parent class
     */
    public function __call($methodName, $params)
    {
        $pm = $this->getPropertyManager();
        if (!method_exists($pm, $methodName)) {
            throw new \Exception('Unknown method '.$methodName);
        }
        return call_user_func_array([$pm, $methodName], $params);
    }

    /**
     *
     */
    public function __get($key)
    {
        return $this->getPropertyManager()->getValue($key);
    }

    public function __set($key, $value)
    {
        return $this->getPropertyManager()->setValue($key, $value);
    }

    /**
     * Get an instance of the property manager
     *
     * @return Manager
     */
    private function getPropertyManager(): Manager
    {
        if (!$this->propertyManager) {
            $this->propertyManager = new Manager;
        }
        return $this->propertyManager;
    }
}
