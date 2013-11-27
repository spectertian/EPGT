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

namespace Mondongo\Tests\Mondator;

use Mondongo\Mondator\Container;
use Mondongo\Mondator\Extension;

class ExtensionTesting extends Extension
{
    protected function setUp()
    {
        $this->addRequiredOptions(array(
            'required',
        ));

        $this->addOptions(array(
            'optional' => 'default_value',
            'foo'      => null,
            'bar'      => null,
        ));
    }

    protected function doProcess()
    {
    }
}

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorOptions()
    {
        $extension = new ExtensionTesting(array('required' => 'value'));
        $this->assertSame(array(
            'required' => 'value',
            'optional' => 'default_value',
            'foo'      => null,
            'bar'      => null,
        ), $extension->getOptions());

        $extension = new ExtensionTesting(array('required' => 'barfoo', 'foo' => 'foobar'));
        $this->assertSame(array(
            'required' => 'barfoo',
            'optional' => 'default_value',
            'foo'      => 'foobar',
            'bar'      => null,
        ), $extension->getOptions());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorOptionNotExists()
    {
        new ExtensionTesting(array('foobar' => 'barfoo'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConstructorNotSomeRequiredOption()
    {
        new ExtensionTesting(array('foo' => 'bar'));
    }

    public function testHasOption()
    {
        $extension = new ExtensionTesting(array('required' => 'value'));
        $this->assertTrue($extension->hasOption('foo'));
        $this->assertFalse($extension->hasOption('foobar'));
    }

    public function testSetOption()
    {
        $extension = new ExtensionTesting(array('required' => 'value'));
        $extension->setOption('foo', 'barfoo');
        $this->assertSame('barfoo', $extension->getOption('foo'));
        $this->assertSame('value', $extension->getOption('required'));
        $this->assertSame('default_value', $extension->getOption('optional'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetOptionNotExists()
    {
        $extension = new ExtensionTesting(array('required' => 'value'));
        $extension->setOption('foobar', 'barfoo');
    }

    public function testGetOptions()
    {
        $extension = new ExtensionTesting(array('required' => 'value'));
        $this->assertSame(array(
            'required' => 'value',
            'optional' => 'default_value',
            'foo'      => null,
            'bar'      => null,
        ), $extension->getOptions());
    }

    public function testGetOption()
    {
        $extension = new ExtensionTesting(array('required' => 'value'));
        $this->assertSame('value', $extension->getOption('required'));
        $this->assertSame('default_value', $extension->getOption('optional'));
        $this->assertNull($extension->getOption('bar'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetOptionNotExists()
    {
        $extension = new ExtensionTesting(array('required' => 'value'));
        $extension->getOption('foobar');
    }

    public function testProcessExtensionsAsArray()
    {
        $extension = new \Mondongo\Tests\Mondator\Fixtures\Extension\ProcessOthersFromArray();

        $container   = new Container();
        $class       = 'Article';
        $configClass = new \ArrayObject(array(
            'extensions' => array(
                array(
                    'class'   => 'Mondongo\Tests\Mondator\Fixtures\Extension\InitDefinition',
                    'options' => array(
                        'definition_name' => 'mydefinition',
                        'class_name'      => 'MyClassName',
                    ),
                ),
                array(
                    'class'   => 'Mondongo\Tests\Mondator\Fixtures\Extension\AddProperty',
                    'options' => array(
                        'definition' => 'mydefinition',
                        'visibility' => 'MyClassName',
                        'name'       => 'myVar',
                        'value'      => 'foo',
                    ),
                ),
            ),
        ));

        $extension->process($container, $class, $configClass, new \ArrayObject());

        $definitions = $container->getDefinitions();

        $this->assertSame(1, count($definitions->getDefinitions()));
        $this->assertTrue(isset($definitions['mydefinition']));
        $properties = $definitions['mydefinition']->getProperties();
        $this->assertSame(1, count($properties));
        $this->assertSame('myVar', $properties[0]->getName());
    }
}
