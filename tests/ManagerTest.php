<?php

namespace Benrowe\Properties;

use \Benrowe\Properties\Manager;
use \Benrowe\Properties\Property;

class ManagerTest extends \PHPUnit_Framework_TestCase
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
}
