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

    public function setName($name)
    {
        // add name validation
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the type for the property
     *
     * @param string $type [description]
     */
    public function setType(?string $type)
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
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
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
