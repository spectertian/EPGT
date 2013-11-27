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

namespace Mondongo\Tests;

use Mondongo\Container;
use Mondongo\Mondongo;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Container::clearDefault();
    }

    public function testDefault()
    {
        $this->assertFalse(Container::hasDefault());

        $mondongo = new Mondongo();
        Container::setDefault($mondongo);
        $this->assertTrue(Container::hasDefault());
        $this->assertSame($mondongo, Container::getDefault());

        Container::clearDefault();
        $this->assertFalse(Container::hasDefault());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetDefaultNotExists()
    {
        Container::getDefault();
    }

    public function testForDocumentClasses()
    {
        $mondongo1 = new Mondongo();
        $mondongo2 = new Mondongo();
        $mondongo3 = new Mondongo();

        Container::setDefault($mondongo3);

        $this->assertFalse(Container::hasForDocumentClass('Article'));

        Container::setForDocumentClass('Article', $mondongo1);
        Container::setForDocumentClass('Category', $mondongo2);
        Container::setForDocumentClass('Comment', $mondongo2);

        $this->assertTrue(Container::hasForDocumentClass('Article'));
        $this->assertSame($mondongo1, Container::getForDocumentClass('Article'));
        $this->assertSame($mondongo2, Container::getForDocumentClass('Category'));
        $this->assertSame($mondongo2, Container::getForDocumentClass('Comment'));
        $this->assertSame($mondongo3, Container::getForDocumentClass('User'));

        Container::removeForDocumentClass('Category');
        $this->assertFalse(Container::hasForDocumentClass('Category'));
        $this->assertTrue(Container::hasForDocumentClass('Article'));

        Container::clearForDocumentClasses();
        $this->assertFalse(Container::hasForDocumentClass('Article'));
        $this->assertFalse(Container::hasForDocumentClass('Comment'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetForDocumentClassNotExists()
    {
        Container::getForDocumentClass('Article');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveForDocumentClassNotExists()
    {
        Container::removeForDocumentClass('Article');
    }
}
