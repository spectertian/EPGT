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
use Mondongo\Mondator\Definition\Container as DefinitionContainer;
use Mondongo\Mondator\Output\Container as OutputContainer;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $container = new Container();
        $this->assertEquals(new DefinitionContainer(), $container->getDefinitions());
        $this->assertEquals(new OutputContainer(), $container->getOutputs());

        $container = new Container($definitions = new DefinitionContainer(), $outputs = new OutputContainer());
        $this->assertSame($definitions, $container->getDefinitions());
        $this->assertSame($outputs, $container->getOutputs());
    }

    public function testDefinitions()
    {
        $container   = new Container();
        $definitions = new DefinitionContainer();

        $container->setDefinitions($definitions);
        $this->assertSame($definitions, $container->getDefinitions());
    }

    public function testOutputs()
    {
        $container = new Container();
        $outputs   = new OutputContainer();

        $container->setOutputs($outputs);
        $this->assertSame($outputs, $container->getOutputs());
    }
}
