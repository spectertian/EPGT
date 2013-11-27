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

namespace Mondongo\Tests\Extension\Extra;

use Mondongo\Tests\TestCase;
use Model\Document\Sluggable;

class SluggableTest extends TestCase
{
    public function testSluggable()
    {
        $documents = array();

        $documents[1] = new Sluggable();
        $documents[1]->setTitle(' Testing Sluggable Extensión ');
        $documents[1]->save();

        $this->assertSame('testing-sluggable-extensi-n', $documents[1]->getSlug());

        $documents[2] = new Sluggable();
        $documents[2]->setTitle(' Testing Sluggable Extensión ');
        $documents[2]->save();

        $this->assertSame('testing-sluggable-extensi-n-2', $documents[2]->getSlug());
    }
}
