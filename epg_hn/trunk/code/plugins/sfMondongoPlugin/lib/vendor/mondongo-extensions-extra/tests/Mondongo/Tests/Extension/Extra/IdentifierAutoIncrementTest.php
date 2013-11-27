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
use Model\Document\IdentifierAutoIncrement;

class IdentifierAutoIncrementTest extends TestCase
{
    public function testIdentifierAutoIncrement()
    {
        $documents = array();

        $documents[1] = new IdentifierAutoIncrement();
        $documents[1]->setField('value');
        $documents[1]->save();

        $this->assertSame(1, $documents[1]->getIdentifier());

        $documents[1]->setField(null);
        $documents[1]->save();

        $this->assertSame(1, $documents[1]->getIdentifier());

        for ($i = 2; $i <= 10; $i++) {
            $documents[$i] = $document = new IdentifierAutoIncrement();
            $document->setField('value');
            $document->save();
        }

        foreach ($documents as $identifier => $document) {
            $d = $document->getRepository()->findOneByMongoId($document->getId());
            $this->assertSame($identifier, $d->getIdentifier());
        }
    }
}
