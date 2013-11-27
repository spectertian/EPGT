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

use Mondongo\Type\ArrayType;

class ArrayTypeTest extends TestCase
{
    protected $array = array('foo' => 'bar');

    public function testToMongo()
    {
        $type = new ArrayType();
        $this->assertSame(serialize($this->array), $type->toMongo($this->array));
    }

    public function testToPHP()
    {
        $type = new ArrayType();
        $this->assertSame($this->array, $type->toPHP(serialize($this->array)));
    }

    public function testToMongoInString()
    {
        $type = new ArrayType();
        $function = $this->getTypeFunction($type->toMongoInString());

        $this->assertSame(serialize($this->array), $function($this->array));
    }

    public function testToPHPInString()
    {
        $type = new ArrayType();
        $function = $this->getTypeFunction($type->toPHPInString());

        $this->assertSame($this->array, $function(serialize($this->array)));
    }
}
