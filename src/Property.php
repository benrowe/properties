<?php

declare(strict_types=1);

namespace Benrowe\Properties;

use Closure;

/**
 * Defines a unique property
 *
 * @package Benrowe\Properties
 * @todo add support for validating a property's value when being set
 */
class Property
{
    private $name;
    private $type = null;
    private $default = null;
    private $value = null;

    private $setter;
    private $getter;

    const TYPES = [
            'string',
            'integer',
            'float',
            'boolean',
            'array',
            'object',
            'null',
            'resource',
        ];

    /**
     * Create a new Property Instance
     *
     * @param string $name
     * @param string $type
     * @param string $default
     */
    public function __construct(string $name, string $type = null, $default = null)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setDefault($default);
    }

    /**
     * Set the property name
     * @param string $name the name of the property
     */
    public function setName(string $name): void
    {
        // add name validation
        $this->name = $name;
    }

    /**
     * Get the property name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the type for the property
     *
     * @param string $type
     * @todo add support for interface/class checking!
     */
    public function setType($type)
    {
        if ($type === null) {
            // no type set
            $this->type = null;
            return;
        }

        $type = strtolower($type);
        if (!in_array($type, self::TYPES, true)) {
            throw new PropertyException('Invalid type');
        }
        $this->type = $type;
    }

    /**
     * Get the property type
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the default value of the property if nothing is explicitly set
     *
     * @param mixed $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * Get the default value
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set the value against the property
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        if ($this->setter) {
            $value = call_user_func($this->setter, $value);
        }
        $this->value = $value;
    }

    /**
     * Get the currently set value, if no value is set the default is used
     *
     * @return mixed
     */
    public function getValue()
    {
        if ($this->value === null) {
            return $this->default;
        }
        $value = $this->value;
        if ($this->getter) {
            $value = call_user_func($this->getter, $value);
        }

        return $value;
    }

    /**
     * Inject a custom closure to handle the storage of the value when it is
     * set into the property
     * @param  Closure $setter the custom function to run when the value is
     * being set
     * @return self
     */
    public function setter(Closure $setter)
    {
        $this->setter = $setter;

        return $this;
    }

    /**
     * Specify a custom closer to handle the retreival of the value stored
     * against this property
     *
     * @param  Closure $getter [description]
     * @return self
     */
    public function getter(Closure $getter)
    {
        $this->getter = $getter;

        return $this;
    }
}
