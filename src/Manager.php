<?php declare(strict_types=1);

namespace Benrowe\Properties;

/**
 * The property manager class is a collection container for managing
 * serveral properties
 *
 * @package Benrowe\Properties
 */
class Manager
{
    /**
     * @var Property[]
     */
    private $properties = [];

    /**
     * Add a new property to the stack
     *
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
     * Get the property by its name
     *
     * @param  string $name
     * @return Property
     * @throws Exception if the property doesn't exist
     */
    public function getProperty(string $name): Property
    {
        if (!$this->hasProperty($name)) {
            throw new PropertyException('Unknown property "'.$name.'"');
        }
        return $this->properties[$name];
    }

    /**
     * Does the manager contain an instance of the property
     * based on it's name
     * @param  string  $name property identifier
     * @return boolean
     */
    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * Get all of the properties registered by the manager
     *
     * @return Property[]
     */
    public function allProperties(): array
    {
        return $this->properties;
    }

    /**
     * Remove the property from the manager
     *
     * @param  string $name
     * @return bool
     */
    public function removeProperty(string $name): bool
    {
        if (!$this->hasProperty($name)) {
            return false;
        }
        unset($this->properties[$name]);
        return true;
    }

    /**
     * Get the value of the property, if it exists
     *
     * @param  string $name property name
     * @return mixed
     * @throws Exception if the property doesn't exist
     */
    public function getValue(string $name)
    {
        return $this->getProperty($name)->getValue();
    }

    /**
     * Set the value of the property, if it exists
     *
     * @param string $name  the property identifier
     * @param mixed $value the value to store against the property
     * @throws Exception if the property doesn't exist
     */
    public function setValue(string $name, $value)
    {
        return $this->getProperty($name)->setValue($value);
    }

    /**
     * Get all the values from the properties
     */
    public function allValues(): array
    {
        $values = [];
        foreach (array_keys($this->properties) as $key) {
            $values[$key] = $this->getValue($key);
        }
        return $values;
    }
}
