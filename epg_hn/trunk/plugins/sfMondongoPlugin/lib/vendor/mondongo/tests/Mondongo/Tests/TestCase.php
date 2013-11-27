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
use Mondongo\Connection;
use Mondongo\Mondongo;
use Mondongo\Type\Container as TypeContainer;
use Model\Document\Article;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $server = 'mongodb://localhost';

    protected $dbName = 'mondongo_tests';

    protected $mongo;

    protected $db;

    protected $connection;

    protected $mondongo;

    public function setUp()
    {
        Container::clearDefault();
        Container::clearForDocumentClasses();

        TypeContainer::resetTypes();

        $this->mongo = new \Mongo($this->server);

        $this->db = $this->mongo->selectDB($this->dbName);

        foreach (array(
            'author',
            'author_telephone',
            'category',
            'article',
            'news',
            'summary',
            'user',
            'image.files',
            'image.chunks',
        ) as $collectionName) {
            $collection = $this->db->selectCollection($collectionName);

            // documents
            if ($collection->find()->count()) {
                $collection->drop();
            }

            // indexes
            if ($collection->getIndexInfo()) {
                $collection->deleteIndexes();
            }
        }

        $this->connection = new Connection('localhost', 'mondongo_tests');

        $this->mondongo = new Mondongo();
        $this->mondongo->setLoggerCallable(function($log) {});
        $this->mondongo->setConnection('default', $this->connection);

        Container::setDefault($this->mondongo);
    }

    protected function createArticles($nb)
    {
        $articles = array();
        for ($i = 0; $i < $nb; $i++) {
            $articles[] = $a = new Article();
            $a->setTitle('Article '.$i);
            $a->setContent('Content');
        }
        $this->mondongo->getRepository('Model\Document\Article')->save($articles);

        return $articles;
    }
}
