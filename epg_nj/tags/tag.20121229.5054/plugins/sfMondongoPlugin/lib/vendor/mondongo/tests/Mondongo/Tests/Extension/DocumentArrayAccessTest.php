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

class DocumentArrayAccessTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testOffsetExists()
    {
        $article = new Article();
        isset($article['title']);
    }

    public function testOffsetSet()
    {
        $article = new Article();
        $article['title'] = 'Mondongo';
        $this->assertSame('Mondongo', $article->getTitle());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetSetNameNotExists()
    {
        $article = new Article();
        $article['no'] = 'Mondongo';
    }

    public function testOffsetGet()
    {
        $article = new Article();
        $article->setTitle('Mondongo');
        $this->assertSame('Mondongo', $article['title']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetGetNameNotExists()
    {
        $article = new Article();
        $article['no'];
    }

    /**
     * @expectedException \LogicException
     */
    public function testOffsetUnset()
    {
        $article = new Article();
        unset($article['title']);
    }
}
