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

namespace Mondongo\Tests\Mondator\Output;

use Mondongo\Mondator\Output\Container;
use Mondongo\Mondator\Output\Output;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testOutputs()
    {
        $outputs = array();

        $outputs[1] = new Output('/path1');
        $outputs[2] = new Output('/path2');
        $outputs[3] = new Output('/path3');
        $outputs[4] = new Output('/path4');

        $container = new Container();

        // setOutput
        $container->setOutput('output1', $outputs[1]);
        $container->setOutput('output2', $outputs[2]);
        $this->assertSame(array(
            'output1' => $outputs[1],
            'output2' => $outputs[2],
        ), $container->getOutputs());

        // hasOutput
        $this->assertTrue($container->hasOutput('output1'));
        $this->assertFalse($container->hasOutput('output3'));

        // getOutput
        $this->assertSame($outputs[1], $container->getOutput('output1'));
        $this->assertSame($outputs[2], $container->getOutput('output2'));

        // setOutputs
        $container->setOutputs($setOutputs = array(
            'output3' => $outputs[3],
            'output4' => $outputs[4]
        ));
        $this->assertSame($setOutputs, $container->getOutputs());

        // removeOutput
        $container->setOutputs(array(
            'output1' => $outputs[1],
            'output2' => $outputs[2],
            'output3' => $outputs[3],
            'output4' => $outputs[4],
        ));
        $container->removeOutput('output2');
        $this->assertFalse($container->hasOutput('output2'));
        $this->assertTrue($container->hasOutput('output1'));
        $this->assertTrue($container->hasOutput('output3'));
        $this->assertTrue($container->hasOutput('output4'));

        // clearOutputs
        $container->clearOutputs();
        $this->assertSame(array(), $container->getOutputs());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetOutputNotExists()
    {
        $container = new Container();
        $container->getOutput('output');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveOutputNotExists()
    {
        $container = new Container();
        $container->removeOutput('output');
    }

    public function testArrayAccessInterface()
    {
        $output1 = new Output('/path1');
        $output2 = new Output('/path2');

        $container = new Container();

        // set
        $container['output1'] = $output1;
        $container['output2'] = $output2;

        // exists
        $this->assertTrue(isset($container['output1']));
        $this->assertFalse(isset($container['output3']));

        // get
        $this->assertSame($output1, $container['output1']);
        $this->assertSame($output2, $container['output2']);

        // unset
        unset($container['output2']);
        $this->assertFalse(isset($container['output2']));
        $this->assertTrue(isset($container['output1']));
    }

    public function testCountableInterface()
    {
        $container = new Container();
        $container->setOutputs(array(
            new Output('/path1'),
            new Output('/path2'),
        ));

        $this->assertSame(2, $container->count());
        $this->assertSame(2, count($container));
    }

    public function testIteratorAggregateInterface()
    {
        $container = new Container();
        $container->setOutputs(array(
            new Output('/path1'),
            new Output('/path2'),
        ));

        $this->assertEquals(new \ArrayIterator($container->getOutputs()), $container->getIterator());
        $this->assertInstanceOf('\IteratorAggregate', $container);
    }
}
