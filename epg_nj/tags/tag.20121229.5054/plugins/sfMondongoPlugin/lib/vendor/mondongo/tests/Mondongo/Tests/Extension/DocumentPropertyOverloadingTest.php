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

namespace Mondongo\Tests\Extension;

use Mondongo\Tests\TestCase;
use Model\Document\Article;

class DocumentPropertyOverloadingTest extends TestCase
{
    public function test__set()
    {
        $article = new Article();
        $article->title = 'Mondongo';
        $this->assertSame('Mondongo', $article->getTitle());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test__setNameNotExists()
    {
        $article = new Article();
        $article->no = 'Mondongo';
    }

    public function test__get()
    {
        $article = new Article();
        $article->setTitle('Mondongo');
        $this->assertSame('Mondongo', $article->title);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test__getNameNotExists()
    {
        $article = new Article();
        $article->no;
    }
}
