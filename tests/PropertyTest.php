<?php

namespace Benrowe\Properties;

use \Benrowe\Properties\Property;

class PropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testInit()
    {
        $property = new Property('name');
        $this->assertSame('name', $property->getName());
        $this->assertNull($property->getType());
        $this->assertNull($property->getValue());

        $property = new Property('name', 'STRING', 'hello');
        $this->assertSame('name', $property->getName());
        $this->assertSame('string', $property->getType());
        $this->assertSame('hello', $property->getValue());
    }

    public function testGetterSetter()
    {
        $property = new Property('name');
        $property
            ->setter(function ($value) {
                return strtolower($value);
            })
            ->getter(function ($value) {
                return str_reverse($value);
            });

        $property->setValue('FOO');
        $this->assertSame('oof', $property->getValue());
    }

    public function testSetValue()
    {
        $property = new Property('name');
        $property->setValue('foo');

        $this->assertSame('foo', $property->getValue());
    }

    public function testValidate()
    {
        $property = new Property('name');
    }
}
