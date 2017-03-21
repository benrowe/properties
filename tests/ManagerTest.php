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

    public function testGetProperty()
    {
        $this->manager->addProperty('name');
        $this->assertInstanceOf(Property::class, $this->manager->getProperty('name'));
    }

    public function testHasProperty()
    {
        $this->manager->addProperty('name');
        $this->assertTrue($this->manager->hasProperty('name'));
    }



    public function testRemoveProperty()
    {
        $this->manager->addProperty('removeme');
        $this->assertTrue($this->manager->removeProperty('removeme'));
        $this->assertTrue($this->manager->hasProperty('removeme'));
        $this->assertFalse($this->manager->removeProperty('removeme'));
        $this->assertFalse($this->manager->hasProperty('removeme'));

    }

    public function testGetValue()
    {

    }

    public function testSetValue()
    {

    }
}
