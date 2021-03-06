<?php declare(strict_types=1);

namespace Benrowe\Properties;

use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    private $manager;

    public function setUp()
    {
        $this->manager = new Manager();
    }

    public function testAddProperty()
    {
        $this->assertInstanceOf(Property::class, $this->manager->addProperty('name'));
    }

    public function testHasProperty()
    {
        $this->manager->addProperty('name');
        $this->assertTrue($this->manager->hasProperty('name'));
    }

    public function testGetProperty()
    {
        $this->manager->addProperty('name');
        $this->assertInstanceOf(Property::class, $this->manager->getProperty('name'));
    }

    public function testRemoveProperty()
    {
        $this->manager->addProperty('removeme');
        $this->assertTrue($this->manager->hasProperty('removeme'));
        $this->assertTrue($this->manager->removeProperty('removeme'));
        $this->assertFalse($this->manager->removeProperty('removeme'));
        $this->assertFalse($this->manager->hasProperty('removeme'));
    }

    public function testAllProperties()
    {
        $this->assertCount(0, $this->manager->allProperties());
        $this->manager->addProperty('name');
        $this->manager->addProperty('name', 'string');
        $this->assertCount(1, $this->manager->allProperties());
    }

    public function testValues()
    {
        $this->manager->addProperty('name');
        $this->assertNull($this->manager->getValue('name'));
        $this->manager->setValue('name', 'foo');
        $this->assertSame('foo', $this->manager->getValue('name'));
        $this->assertSame('foo', $this->manager->getProperty('name')->getValue());
    }

    public function testSetValue()
    {
    }

    public function testAllValues()
    {
        $this->manager->addProperty('null');
        $this->assertSame(['null' => null], $this->manager->allValues());
        $this->manager->addProperty('foo', null, 'bar');
        $this->assertSame(['null' => null, 'foo' => 'bar'], $this->manager->allValues());
        $this->manager->setValue('foo', 111);
        $this->assertSame(['null' => null, 'foo' => 111], $this->manager->allValues());
    }
}
