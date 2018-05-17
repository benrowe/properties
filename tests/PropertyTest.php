<?php declare(strict_types=1);

namespace Benrowe\Properties;

use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
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
                return strrev($value);
            });

        $property->setValue('FOO');
        $this->assertSame('oof', $property->getValue());
    }

    public function testGetDefault()
    {
        $property = new Property('name', 'string');
        $this->assertSame(null, $property->getDefault());
        $property->setDefault('foo');
        $this->assertSame('foo', $property->getDefault());
        $property->setDefault(1);
        $this->assertSame(1, $property->getDefault());
    }

    public function testSetValue()
    {
        $property = new Property('name');
        $property->setValue('foo');

        $this->assertSame('foo', $property->getValue());
    }

    public function testDefaultValueRuntime()
    {
        $property = new Property('name');
        $this->assertNull($property->getValue());
        $this->assertSame(12345, $property->getValue(12345));
        $property->setValue('foo');
        $this->assertSame('foo', $property->getValue(12345));
    }

    public function testValidate()
    {
        $property = new Property('name');
    }

    /**
     * @expectedException \Benrowe\Properties\PropertyException
     */
    public function testInvalidType()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage(PropertyException::UNKNOWN_TYPE);
        $property = new Property('name', '!!');
    }

    public function testClosureType()
    {
        $closure = function ($value) {
            $type = gettype($value);
            return $type === 'string' || $type === 'integer';
        };
        $property = new Property('test', $closure);
        $property->setValue(8);
        $this->assertSame(8, $property->getValue());
        $property->setValue('8.88');
        $this->assertSame('8.88', $property->getValue());
    }

    /**
     * @expectedException \Benrowe\Properties\PropertyException
     */
    public function testInvalidTypeClosure()
    {
        $closure = function ($value) {
            $type = gettype($value);
            return $type === 'string' || $type === 'integer';
        };
        $property = new Property('test', $closure);
        $property->setValue(8.88);
    }

    /**
     * @expectedException \Benrowe\Properties\PropertyException
     */
    public function testInvalidBaseType()
    {
        $property = new Property('name', 'string');
        $property->setValue('foo');
        $this->assertSame('foo', $property->getValue());

        $property->setValue(true);
    }

    public function testInstanceofTypeException()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage("Value specified for \"name\" is not of the correct type");
        $property = new Property('name', PropertyException::class);
        $property->setValue(new \stdClass);
    }

    public function testInstanceOfType()
    {
        $property = new Property('name', PropertyException::class);
        $property->setValue(new PropertyException);

        $this->assertInstanceOf(PropertyException::class, $property->getValue());
    }

    public function testInstaceOfArrayType()
    {
        $property = new Property('name', PropertyException::class."[]");
        $property->setValue([new PropertyException, new PropertyException]);

        $this->assertCount(2, $property->getValue());
    }


    public function testInstaceOfArrayTypeException()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage("Value specified for \"name\" is not of the correct type");
        $property = new Property('name', PropertyException::class."[]");
        $property->setValue([new PropertyException, new \stdClass]);

        $this->assertCount(2, $property->getValue());
    }
    public function testInstaceOfArrayTypeNonArrayException()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage("Value specified for \"name\" is not of the correct type");
        $property = new Property('name', PropertyException::class."[]");
        $property->setValue('hi');

        $this->assertCount(2, $property->getValue());
    }
}
