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
use Model\Document\Timestampable;

class TimestampableTest extends TestCase
{
    public function testTimestampable()
    {
        $document = new Timestampable();
        $document->setField('value');
        $document->save();

        $this->assertEquals(new \DateTime(), $createdAt = $document->getCreatedAt());
        $this->assertNull($document->getUpdatedAt());

        $document->setField(null);
        $document->save();

        $this->assertEquals(new \DateTime(), $document->getUpdatedAt());
        $this->assertSame($createdAt, $document->getCreatedAt());
    }
}
