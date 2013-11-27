<?php

/*
 * Copyright 2010 Pablo Díez Pascual <pablodip@gmail.com>
 *
 * This file is part of Mondongo.
 *
 * Mondongo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Mondongo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Mondongo. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Mondongo\Tests\Type;

use Mondongo\Type\Container;
use Mondongo\Type\Type;

class ContainerTest extends TestCase
{
    public function setup()
    {
        Container::resetTypes();
    }

    public function testHasType()
    {
        $this->assertTrue(Container::hasType('string'));
        $this->assertFalse(Container::hasType('no'));
    }

    public function testAddType()
    {
        Container::addType('testing', 'Mondongo\\Tests\\Type\\TestingType');
        $this->assertTrue(Container::hasType('testing'));

        $this->assertInstanceOf('Mondongo\\Tests\\Type\\TestingType', Container::getType('testing'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddTypeAlreadyExists()
    {
        Container::addType('string', 'Mondongo\\Tests\\Type\\TestingType');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddTypeClassNotSubclassOfType()
    {
        Container::addType('testing', '\\DateTime');
    }

    public function testGetType()
    {
        $string = Container::getType('string');
        $float  = Container::getType('float');

        $this->assertInstanceOf('\\Mondongo\\Type\\StringType', $string);
        $this->assertInstanceOf('\\Mondongo\\Type\\FloatType', $float);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetTypeNotExists()
    {
        Container::getType('no');
    }

    public function testRemoveType()
    {
        Container::removeType('string');
        $this->assertFalse(Container::hasType('string'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveTypeNotExists()
    {
        Container::removeType('no');
    }

    public function testResetTypes()
    {
        Container::addType('testing', 'Mondongo\\Tests\\Type\\TestingType');
        Container::resetTypes();

        $this->assertTrue(Container::hasType('string'));
        $this->assertFalse(Container::hasType('testing'));
    }
}

class TestingType extends Type
{
    public function toMongo($value)
    {
    }

    public function toPHP($value)
    {
    }

    public function toMongoInString()
    {
    }

    public function toPHPInString()
    {
    }
}
