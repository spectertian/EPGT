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

use Model\Document\Article;

class DocumentDataMapTest extends \PHPUnit_Framework_TestCase
{
    public function testDocumentDataMap()
    {
        $this->assertSame(array(
            'fields' => array(
                'title'        => array('type' => 'string'),
                'slug'         => array('type' => 'string'),
                'content'      => array('type' => 'string'),
                'is_active'    => array('type' => 'boolean'),
                'author_id'    => array('type' => 'reference_one'),
                'category_ids' => array('type' => 'reference_many'),
            ),
            'references' => array(
                'author'     => array('class' => 'Model\Document\Author', 'field' => 'author_id', 'type' => 'one'),
                'categories' => array('class' => 'Model\Document\Category', 'field' => 'category_ids', 'type' => 'many'),
            ),
            'embeddeds' => array(
                'source'   => array('class' => 'Model\Document\Source', 'type' => 'one'),
                'comments' => array('class' => 'Model\Document\Comment', 'type' => 'many'),
            ),
            'relations' => array(
                'summary' => array('class' => 'Model\Document\Summary', 'field' => 'article_id', 'type' => 'one'),
                'news'    => array('class' => 'Model\Document\News', 'field' => 'article_id', 'type' => 'many'),
            ),
        ), Article::getDataMap());
    }
}
