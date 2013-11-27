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

use Mondongo\LoggableMongoGridFS;

class LoggableMongoGridFSTest extends TestCase
{
    public function testLoggerCallable()
    {
        $loggerCallable = function() {};

        $collection = new LoggableMongoGridFS($this->mongo, $this->db, 'image');
        $this->assertSame($this->mongo, $collection->getMongo());
        $collection->setLoggerCallable($loggerCallable);
        $this->assertSame($loggerCallable, $collection->getLoggerCallable());
    }

    public function testConnectionName()
    {
        $collection = new LoggableMongoGridFS($this->mongo, $this->db, 'image');
        $collection->setConnectionName('foobar');
        $this->assertSame('foobar', $collection->getConnectionName());
    }
}
