<?php declare(strict_types=1);

namespace Benrowe\Properties;

use PHPUnit\Framework\TestCase;

class TraitTest extends TestCase
{
    private $concrete;

    public function setUp()
    {
        $this->concrete = $this->getMockForTrait(PropertyTrait::class);
    }

    /**
     * @expectedException \Benrowe\Properties\PropertyException
     */
    public function testNonExistProperty()
    {
        $this->concrete->foo;
    }

    /**
     * @expectedException \Exception
     */
    public function testNonExistMethod()
    {
        $this->concrete->foo();
    }

    public function testAddProperty()
    {
        $this->assertInstanceOf(Property::class, $this->concrete->addProperty('test'));
        $this->assertInstanceOf(Property::class, $this->concrete->addProperty('test')->setter(function ($value) {
            return $value;
        }));
        $this->assertNull($this->concrete->test);
        $this->concrete->test = 'hi';
        $this->assertSame('hi', $this->concrete->test);
    }
}
