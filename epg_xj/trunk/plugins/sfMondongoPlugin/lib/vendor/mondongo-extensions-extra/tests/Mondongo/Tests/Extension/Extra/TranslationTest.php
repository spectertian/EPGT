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
use Model\Document\TranslationDocument;

class TranslationTest extends TestCase
{
    public function testTranslation()
    {
        $document = new TranslationDocument();
        $document->translation('en')->setTitle('Title');
        $document->translation('en')->setBody('Body');
        $document->translation('es')->setTitle('Título');
        $document->translation('es')->setBody('Cuerpo');
        $document->setDate(new \DateTime());
        $document->setIsActive(true);
        $document->save();

        $this->assertSame(array(
            'date'         => $document->getDate(),
            'is_active'    => true,
            'translations' => array(
                array(
                    'locale' => 'en',
                    'title'  => 'Title',
                    'body'   => 'Body',
                ),
                array(
                    'locale' => 'es',
                    'title'  => 'Título',
                    'body'   => 'Cuerpo',
                ),
            ),
        ), $document->toArray(true));
    }
}
