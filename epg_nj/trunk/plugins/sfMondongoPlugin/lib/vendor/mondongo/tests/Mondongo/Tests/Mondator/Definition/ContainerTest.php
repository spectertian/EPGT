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

namespace Mondongo\Tests\Mondator\Definition;

use Mondongo\Mondator\Definition\Container;
use Mondongo\Mondator\Definition\Definition;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testDefinitions()
    {
        $definitions = array();

        $definitions[1] = new Definition('Class1');
        $definitions[2] = new Definition('Class2');
        $definitions[3] = new Definition('Class3');
        $definitions[4] = new Definition('Class4');

        $container = new Container();

        // setDefinition
        $container->setDefinition('definition1', $definitions[1]);
        $container->setDefinition('definition2', $definitions[2]);
        $this->assertSame(array(
            'definition1' => $definitions[1],
            'definition2' => $definitions[2],
        ), $container->getDefinitions());

        // hasDefinition
        $this->assertTrue($container->hasDefinition('definition1'));
        $this->assertFalse($container->hasDefinition('definition3'));

        // getDefinition
        $this->assertSame($definitions[1], $container->getDefinition('definition1'));
        $this->assertSame($definitions[2], $container->getDefinition('definition2'));

        // setDefinitions
        $container->setDefinitions($setDefinitions = array(
            'definition3' => $definitions[3],
            'definition4' => $definitions[4]
        ));
        $this->assertSame($setDefinitions, $container->getDefinitions());

        // removeDefinition
        $container->setDefinitions(array(
            'definition1' => $definitions[1],
            'definition2' => $definitions[2],
            'definition3' => $definitions[3],
            'definition4' => $definitions[4],
        ));
        $container->removeDefinition('definition2');
        $this->assertFalse($container->hasDefinition('definition2'));
        $this->assertTrue($container->hasDefinition('definition1'));
        $this->assertTrue($container->hasDefinition('definition3'));
        $this->assertTrue($container->hasDefinition('definition4'));

        // clearDefinitions
        $container->clearDefinitions();
        $this->assertSame(array(), $container->getDefinitions());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetDefinitionNotExists()
    {
        $container = new Container();
        $container->getDefinition('definition');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveDefinitionNotExists()
    {
        $container = new Container();
        $container->removeDefinition('definition');
    }

    public function testArrayAccessInterface()
    {
        $definition1 = new Definition('Class1');
        $definition2 = new Definition('Class2');

        $container = new Container();

        // set
        $container['definition1'] = $definition1;
        $container['definition2'] = $definition2;

        // exists
        $this->assertTrue(isset($container['definition1']));
        $this->assertFalse(isset($container['definition3']));

        // get
        $this->assertSame($definition1, $container['definition1']);
        $this->assertSame($definition2, $container['definition2']);

        // unset
        unset($container['definition2']);
        $this->assertFalse(isset($container['definition2']));
        $this->assertTrue(isset($container['definition1']));
    }

    public function testCountableInterface()
    {
        $container = new Container();
        $container->setDefinitions(array(
            new Definition('Class1'),
            new Definition('Class2'),
        ));

        $this->assertSame(2, $container->count());
        $this->assertSame(2, count($container));
    }

    public function testIteratorAggregateInterface()
    {
        $container = new Container();
        $container->setDefinitions(array(
            new Definition('Class1'),
            new Definition('Class2'),
        ));

        $this->assertEquals(new \ArrayIterator($container->getDefinitions()), $container->getIterator());
        $this->assertInstanceOf('\IteratorAggregate', $container);
    }
}
