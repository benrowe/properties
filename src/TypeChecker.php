<?php declare(strict_types=1);

namespace Benrowe\Properties;

class TypeChecker
{
    private $value;

    const DOCBLOCK_PARAM_PATTERN = "/^(([a-z\\\])+(\[\])?\|?)+$/i";

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

    private $remap = [
        'int' => 'integer',
        'bool' => 'boolean',
    ];

    /**
     * Grabs the value to check
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Check the already supplied value against the type specified
     */
    public function check(string $type): bool
    {
        if (!preg_match("/^(([a-z\\\])+(\[\])?\|?)+$/i", $type)) {
            throw new TypeException("Unknown type check for \"$type\"");
        }
        $types = $this->normaliseTypes(explode('|', $type));
        foreach ($types as $type) {
            if (substr($type, -2) === '[]') {
                $type = substr($type, 0, -2);
                if ($this->isArrayOf($type, $this->value)) {
                    return true;
                }
            } else {
                if ($this->checkType($type, $this->value)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function checkAsClosure(\Closure $closure): bool
    {
        $result = call_user_func($closure, $this->value, $this);
        if (gettype($result) !== 'boolean') {
            throw new TypeException('Return value for closure is not boolean');
        }
        return $result;
    }

    private function isArrayOf(string $type, $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $val) {
            if (!$this->checkType($type, $val)) {
                return false;
            }
        }
        return true;
    }

    private function checkType(string $type, $value): bool
    {
        if ($this->isBaseType($type)) {
            $calcType = gettype($value);
            if (array_key_exists($type, $this->remap)) {
                $type = $this->remap[$type];
            }

            return $calcType === $type;
        }

        return (bool)($value instanceof $type);
    }

    /**
     * Clean up the supplied array of types
     *
     * @param array $types
     *
     * @return array
     */
    private function normaliseTypes(array $types): array
    {
        return array_filter(array_map('trim', $types));
    }

    /**
     * Determine if the supplied type is one of php's base values
     *
     * @param string $type
     * @return bool
     */
    private function isBaseType(string $type): bool
    {
        return in_array($type, self::TYPES, true);
    }
}
