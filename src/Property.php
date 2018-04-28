<?php declare(strict_types=1);

namespace Benrowe\Properties;

use Closure;

/**
 * Defines a unique property.
 * As a base, the property must have a a name. Additionally
 *
 * @package Benrowe\Properties
 * @todo add support for validating a property's value when being set
 */
class Property
{
    /**
     * @var string property name
     */
    private $name;

    /**
     * @var string|Closure|null the value type, {@see setType} for more details
     */
    private $type = null;

    /**
     * @var mixed the default value
     */
    private $default = null;

    /**
     * The currently set value
     */
    private $value = null;

    /**
     * @var Closure|string|null the setter mutator
     */
    private $setter;

    /**
     * @var Closure|string|null the getter mutator
     */
    private $getter;

    /**
     * @var string[] the base types the component will allow
     */
    const TYPES = [
            'string',
            'integer',
            'int',
            'float',
            'boolean',
            'bool',
            'array',
            'object',
            'null',
            'resource',
        ];

    const DOCBLOCK_PARAM_PATTERN = "/^(([a-z\\\])+(\[\])?\|?)+$/i";

    /**
     * Create a new Property Instance
     *
     * @param string $name the name of the property
     * @param string|Closure|null $type {@see setType}
     * @param string|null $default the default value
     */
    public function __construct(string $name, $type = null, $default = null)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setDefault($default);
    }

    /**
     * Set the property name
     *
     * @param string $name the name of the property
     */
    public function setName(string $name)
    {
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
     * The type acts as a validator for when the {@see setValue} is called.
     * Properties are strict so the type specified here must be exact
     *
     * The following types are supported:
     * - php primitative types {@see self::TYPES} for a list
     * - docblock style string
     * - fully quantified class name of instanceof
     * - Closure: bool determines if the value is acceptable
     * - null. no set checking, effectively treated as 'mixed'
     *
     * @param string|Closure|null $type
     * @return void
     * @throws PropertyException if type is unsupported
     */
    public function setType($type): void
    {
        // null/callable
        if (is_callable($type) || $type === null) {
            $this->type = $type;
            return;
        }

        // primitaves
        if (in_array(strtolower($type), self::TYPES, true)) {
            $this->type = strtolower($type);
            return;
        }

        if (preg_match(self::DOCBLOCK_PARAM_PATTERN, $type)) {
            $this->type = $type;
            return;
        }

        // unknown, drop
        throw new PropertyException(PropertyException::UNKNOWN_TYPE);
    }

    /**
     * Get the property type
     * @return closure|string|null
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
        // check the value against the type specified
        if ($this->type !== null && !$this->checkType($this->type, $value)) {
            throw new PropertyException("Value specified for \"{$this->name}\" is not of the correct type");
        }
        $this->value = $value;
    }

    /**
     * Check the the value against the type and see if we have a match
     *
     * @param string|Closure $type the type
     * @param mixed $value the value to check
     *
     * @return bool
     */
    private function checkType($type, $value): bool
    {
        if (is_callable($type)) {
            // call the type closure as function ($value, $property)
            return call_user_func($type, $value, $this);
        }
        if (in_array($type, self::TYPES)) {
            return $this->typeCheck($type, $value);
        }
        // docblock style type
        $types = explode('|', $type);
        foreach ($types as $type) {
            if (substr($type, -2) === '[]') {
                if ($this->arrayOf(substr($type, 0, -2), $value)) {
                    return true;
                }
            } else {
                if ($this->typeCheck($type, $value)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine if the value is an array of the type specified
     *
     * @param string $type
     * @param mixed $value
     *
     * @return bool
     */
    private function arrayOf(string $type, $value): bool
    {
        if (!is_array($value)) {
            return false;
        }
        foreach ($value as $val) {
            if (!$this->typeCheck($type, $val)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check the type against the value (either a base type, or a instance of a class)
     *
     * @param string $type
     * @param mixed  $value
     *
     * @return bool
     */
    private function typeCheck(string $type, $value): bool
    {
        if (in_array($type, self::TYPES)) {
            return gettype($value) === $type;
        }
        // at this point, we assume the type is a FQCN..
        return (bool)($value instanceof $type);
    }

    /**
     * Get the currently set value, if no value is set the default is used
     *
     * @param mixed $default runtime default value. specify the default value for this
     *                       property when you call this method
     * @return mixed
     */
    public function getValue($default = null)
    {
        if ($this->value === null) {
            return $default !== null ? $default : $this->default;
        }
        $value = $this->value;
        if ($this->getter) {
            $value = call_user_func($this->getter, $value);
        }

        return $value;
    }

    /**
     * Register a closure to mutate the properties value before being stored.
     * This can be to cast the value to the $type specified
     *
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
