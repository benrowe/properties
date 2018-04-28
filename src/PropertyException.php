<?php declare(strict_types=1);

namespace Benrowe\Properties;

/**
 * @package Benrowe\Properties
 */
class PropertyException extends \Exception
{
    const UNKNOWN_TYPE = 'Property $type is not supported';
}
