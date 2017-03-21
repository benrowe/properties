<?php

declare(strict_types=1);

namespace Benrowe\Properties;

/**
 * The property manager class is a collection container for managing
 * serveral properties
 *
 * @package Benrowe\Properties
 */
class Manager
{
    private $properties = [];

    /**
     * Add a new property to the stack
     * @param string $name the property name
     * @param string $type the data type for the property (string, int, bool, etc)
     * @param mixed $default the default value, until explicity assigned this is
     * the value for the property
     */
    public function addProperty($name, $type = null, $default = null): Property
    {
        $property = new Property($name, $type, $default);
        $this->properties[$name] = $property;

        return $property;
    }

    /**
     * [getProperty description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     * @throws Exception if the property doesn't exist
     */
    public function getProperty($name): Property
    {
        if (!$this->hasProperty($name)) {
            throw new Exception('Unknown property "'.$name.'"');
        }
        return $this->properties[$name];
    }

    /**
     * Does the manager contain an instance of the property
     * based on it's name
     * @param  string  $name property identifier
     * @return boolean
     */
    public function hasProperty($name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * Remove the property from the manager
     * @param  string $name
     * @return bool
     */
    public function removeProperty($name): bool
    {
        if (!$this->hasProperty($name)) {
            return false;
        }
        unset($this->properties[$name]);
        return true;
    }

    /**
     * Get the value of the property, if it exists
     * @param  [type] $name [description]
     * @return [type]       [description]
     * @throws Exception if the property doesn't exist
     */
    public function getValue($name)
    {
        return $this->getProperty($name)->getValue();
    }

    /**
     * Set the value of the property, if it exists
     * @param [type] $name  [description]
     * @param [type] $value [description]
     * @throws Exception if the property doesn't exist
     */
    public function setValue($name, $value)
    {
        return $this->getProperty($name)->setValue($value);
    }
}
