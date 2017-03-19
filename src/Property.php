<?php

declare(strict_types=1);

namespace Benrowe\Properties;

use Closure;

/**
 * Defines a unique property
 *
 * @package Benrowe\Properties
 */
class Property
{
    private $name;
    private $type = null;
    private $default = null;
    private $value = null;

    private $setter;
    private $getter;

    /**
     * Create a new Property Instance
     */
    public function __construct($name, $type = null, $default = null)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setDefault($default);
    }

    /**
     * Set the property name
     */
    public function setName($name)
    {
        // add name validation
        $this->name = $name;
    }

    /**
     * Get the property name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the type for the property
     *
     * @param string $type [description]
     */
    public function setType($type)
    {
        if ($type === null) {
            $this->type = null;
            return $this;
        }

        $types = [
            'string',
            'integer',
            'float',
            'boolean',
            'array',
            'object',
            'null',
            'resource',
        ];
        $type = strtolower($type);
        if (!in_array($type, $types, true)) {
            throw new PropertyException('Invalid type');
        }
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setDefault($default)
    {
        $this->default = $default;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setValue($value)
    {
        if ($this->setter) {
            $value = call_user_func($this->setter, $value);
        }
        $this->value = $value;
    }

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
     * [setter description]
     * @param  Closure $setter [description]
     * @return setter
     */
    public function setter(Closure $setter)
    {
        $this->setter = $setter;

        return $this;
    }

    /**
     * Specify the getter
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
