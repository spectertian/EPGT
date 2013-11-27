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

use Mondongo\Connection;
use Mondongo\Mondongo;
use Mondongo\Repository as RepositoryBase;
use Model\Document\Article;
use Model\Document\Events;
use Model\Document\Image;

class Repository extends RepositoryBase
{
    protected $documentClass = 'User';

    protected $connectionName = 'default';

    protected $collectionName = 'users';
}

class RepositoryTest extends TestCase
{
    public function testCreateCollectionNormalNoLoggable()
    {
        $collection = RepositoryBase::createCollection($this->connection, 'foo', false, null, null);

        $this->assertSame('MongoCollection', get_class($collection));
        $this->assertSame($this->connection->getMongoDB(), $collection->db);
        $this->assertSame('foo', $collection->getName());
    }

    public function testCreateCollectionNormalLoggable()
    {
        $loggable = function() {};

        $collection = RepositoryBase::createCollection($this->connection, 'bar', false, $loggable, 'barfoo');

        $this->assertSame('Mondongo\LoggableMongoCollection', get_class($collection));
        $this->assertSame($this->connection->getMongoDB(), $collection->db);
        $this->assertSame('bar', $collection->getName());
        $this->assertSame($loggable, $collection->getLoggerCallable());
        $this->assertSame('barfoo', $collection->getConnectionName());
    }

    public function testCreateCollectionGridFSNoLoggable()
    {
        $collection = RepositoryBase::createCollection($this->connection, 'foobar', true, null, null);

        $this->assertSame('MongoGridFS', get_class($collection));
        $this->assertSame($this->connection->getMongoDB(), $collection->db);
        $this->assertSame('foobar.files', $collection->getName());
    }

    public function testCreateCollectionGridFSLoggable()
    {
        $loggable = function() {};

        $collection = RepositoryBase::createCollection($this->connection, 'barfoo', true, $loggable, 'foobar');

        $this->assertSame('Mondongo\LoggableMongoGridFS', get_class($collection));
        $this->assertSame($this->connection->getMongoDB(), $collection->db);
        $this->assertSame('barfoo.files', $collection->getName());
        $this->assertSame($loggable, $collection->getLoggerCallable());
        $this->assertSame('foobar', $collection->getConnectionName());
    }

    public function testGetMondongo()
    {
        $mondongo   = new Mondongo();
        $repository = new Repository($mondongo);

        $this->assertSame($mondongo, $repository->getMondongo());
    }

    public function testGetDocumentClass()
    {
        $repository = new Repository(new Mondongo());

        $this->assertSame('User', $repository->getDocumentClass());
    }

    public function testGetConnectionName()
    {
        $repository = new Repository(new Mondongo());

        $this->assertSame('default', $repository->getConnectionName());
    }

    public function testGetCollectionName()
    {
        $repository = new Repository(new Mondongo());

        $this->assertSame('users', $repository->getCollectionName());
    }

    public function testGetConnection()
    {
        $mondongo = new Mondongo();
        $mondongo->setConnections(array(
            'local'  => $local  = new Connection('localhost', 'mondongo_tests_local'),
            'global' => $global = new Connection('localhost', 'mondongo_tests_global'),
        ));

        $this->assertSame($local, $mondongo->getRepository('Model\Document\Article')->getConnection());
        $this->assertSame($global, $mondongo->getRepository('Model\Document\ConnectionGlobal')->getConnection());
    }

    public function testCollection()
    {
        $mondongo = new Mondongo();
        $mondongo->setConnection('default', $this->connection);
        $collection = $mondongo->getRepository('Model\Document\Article')->getCollection();

        $this->assertSame('MongoCollection', get_class($collection));
        $this->assertSame('article', $collection->getName());
    }

    public function testCollectionLoggable()
    {
        $loggerCallable = function() {};

        $mondongo = new Mondongo();
        $mondongo->setLoggerCallable($loggerCallable);
        $mondongo->setConnection('default', $this->connection);
        $collection = $mondongo->getRepository('Model\Document\Article')->getCollection();

        $this->assertSame('Mondongo\LoggableMongoCollection', get_class($collection));
        $this->assertSame('article', $collection->getName());
        $this->assertSame($loggerCallable, $collection->getLoggerCallable());
    }

    public function testCollectionGridFS()
    {
        $mondongo = new Mondongo();
        $mondongo->setConnection('default', $this->connection);
        $collection = $mondongo->getRepository('Model\Document\Image')->getCollection();

        $this->assertSame('MongoGridFS', get_class($collection));
        $this->assertSame('image.files', $collection->getName());
    }

    public function testCollectionGridFSLoggable()
    {
        $loggerCallable = function() {};

        $mondongo = new Mondongo();
        $mondongo->setLoggerCallable($loggerCallable);
        $mondongo->setConnection('default', $this->connection);
        $collection = $mondongo->getRepository('Model\Document\Image')->getCollection();

        $this->assertSame('Mondongo\LoggableMongoGridFS', get_class($collection));
        $this->assertSame('image.files', $collection->getName());
        $this->assertSame($loggerCallable, $collection->getLoggerCallable());
    }

    public function testFind()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertEquals($articles, $repository->find());

        $this->assertNull($repository->find(array('query' => array('_id' => new \MongoId('123')))));
    }

    public function testFindGridFS()
    {
        $file = __DIR__.'/MondongoTest.php';

        $mondongo = new Mondongo();
        $mondongo->setConnection('default', $this->connection);
        $repository = $mondongo->getRepository('Model\Document\Image');

        $image = new Image();
        $image->setFile($file);
        $image->setName('Mondongo');
        $image->setDescription('Foobar');
        $repository->save($image);

        $image  = $repository->find(array('one' => true));
        $result = $this->db->getGridFS('image')->findOne();

        $this->assertEquals($result, $image->getFile());
        $this->assertSame('Mondongo', $image->getName());
        $this->assertSame('Foobar', $image->getDescription());
    }

    public function testFindOptionQuery()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertEquals(array($articles[0], $articles[4]), $repository->find(array('query' => array(
            '_id' => array('$in' => array($articles[0]->getId(), $articles[4]->getId()))
        ))));
    }

    public function testFindOptionFields()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $results = $repository->find(array('fields' => array('content' => 1)));

        $this->assertNull($results[0]->getTitle());
        $this->assertNull($results[0]->getIsActive());
        $this->assertNull($results[3]->getTitle());
        $this->assertNull($results[3]->getIsActive());
    }

    public function testFindOptionSort()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(11);

        $results = $repository->find(array('sort' => array('title' => -1)));

        $this->assertSame('Article 9', $results[0]->getTitle());
        $this->assertSame('Article 8', $results[1]->getTitle());
        $this->assertSame('Article 10', $results[8]->getTitle());
        $this->assertSame('Article 1', $results[9]->getTitle());
        $this->assertSame('Article 0', $results[10]->getTitle());
    }

    public function testFindOptionLimit()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertSame(4, count($repository->find(array('limit' => 4))));
        $this->assertSame(6, count($repository->find(array('limit' => 6))));
    }

    public function testFindOptionSkip()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertEquals(array($articles[8], $articles[9]), $repository->find(array(
            'skip' => 8,
        )));
        $this->assertEquals(array($articles[6], $articles[7], $articles[8], $articles[9]), $repository->find(array(
            'skip' => 6,
        )));
    }

    public function testFindOptionOne()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertEquals($articles[0], $repository->find(array('query' => array('_id' => $articles[0]->getId()), 'one' => true)));
        $this->assertEquals($articles[4], $repository->find(array('query' => array('_id' => $articles[4]->getId()), 'one' => true)));
    }

    public function testFindOne()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertEquals($articles[0], $repository->findOne());
        $this->assertEquals($articles[3], $repository->findOne(array('query' => array('_id' => $articles[3]->getId()))));

        $this->assertNull($repository->findOne(array('query' => array('_id' => new \MongoId('123')))));
    }

    public function testFindOneById()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertEquals($articles[2], $repository->findOneById($articles[2]->getId()));
        $this->assertEquals($articles[5], $repository->findOneById($articles[5]->getId()));

        $this->assertNull($repository->findOneById(new \MongoId('123')));
    }

    public function testCount()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $this->assertSame(10, $repository->count());
    }

    public function testCountQuery()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        for ($i = 1; $i <= 5; $i++) {
            $articles[$i]->setTitle('Count');
            $repository->save($articles[$i]);
        }

        $this->assertSame(5, $repository->count(array('title' => 'Count')));
    }

    public function testRemove()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $repository->remove();

        $this->assertSame(0, $this->db->article->find()->count());
    }

    public function testRemoveQuery()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $articles[3]->setTitle('No');
        $articles[3]->save();

        $repository->remove(array('title' => new \MongoRegex('/^Article/')));

        $this->assertSame(1, $this->db->article->find()->count());
        $this->assertSame(1, $this->db->article->find(array('_id' => $articles[3]->getId()))->count());
    }

    public function testSaveInsertUnique()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');

        $article = new Article();
        $article->setTitle('Mondongo');
        $repository->save($article);

        $this->assertSame(1, $this->db->article->find()->count());

        $result = $this->db->article->findOne();

        $this->assertEquals($article->getId(), $result['_id']);
        $this->assertEquals('Mondongo', $result['title']);
    }

    public function testSaveUpdateUnique()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $articles[4]->setTitle('Mondongo Updated');
        $repository->save($articles[4]);
        $this->assertEquals(array(
            '_id'     => $articles[4]->getId(),
            'title'   => 'Mondongo Updated',
            'content' => 'Content',
        ), $this->db->article->findOne(array('_id' => $articles[4]->getId())));

        $this->assertSame(9, $this->db->article->find(array('title' => new \MongoRegex('/^Article/')))->count());
    }

    public function testSaveInsertGridFSSaveFile()
    {
        $file = __DIR__.'/MondongoTest.php';

        $repository = $this->mondongo->getRepository('Model\Document\Image');

        $image = new Image();
        $image->setFile($file);
        $image->setName('Mondongo');
        $image->setDescription('Foobar');
        $repository->save($image);

        $result = $this->db->getGridFS('image')->findOne();

        $this->assertEquals($result->file['_id'], $image->getId());
        $this->assertSame(file_get_contents($file), $result->getBytes());
        $this->assertSame('Mondongo', $result->file['name']);
        $this->assertSame('Foobar', $result->file['description']);
    }

    public function testSaveInsertGridFSSaveBytes()
    {
        $bytes = file_get_contents(__DIR__.'/MondongoTest.php');

        $repository = $this->mondongo->getRepository('Model\Document\Image');

        $image = new Image();
        $image->setFile($bytes);
        $image->setName('Mondongo');
        $image->setDescription('Foobar');
        $repository->save($image);

        $result = $this->db->getGridFS('image')->findOne();

        $this->assertEquals($result->file['_id'], $image->getId());
        $this->assertSame($bytes, $result->getBytes());
        $this->assertEquals('Mondongo', $image->getName());
        $this->assertEquals('Foobar', $image->getDescription());
    }

    public function testSaveUpdate()
    {
        $file = __DIR__.'/MondongoTest.php';

        $repository = $this->mondongo->getRepository('Model\Document\Image');

        $image = new Image();
        $image->setFile($file);
        $image->setName('Mondongo');
        $image->setDescription('Foobar');
        $repository->save($image);

        $image->setName('GridFS');
        $image->setDescription('Rocks');
        $repository->save($image);

        $result = $this->db->getGridFS('image')->findOne();

        $this->assertEquals($result->file['_id'], $image->getId());
        $this->assertSame(file_get_contents($file), $result->getBytes());
        $this->assertEquals('GridFS', $image->getName());
        $this->assertEquals('Rocks', $image->getDescription());
    }

    public function testSaveEvents()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Events');

        $document = new Events();
        $document->setName('Mondongo');
        $repository->save($document);

        $this->assertSame(array(
            'preInsertExtensions',
            'preInsert',
            'preSaveExtensions',
            'preSave',
            'postInsertExtensions',
            'postInsert',
            'postSaveExtensions',
            'postSave',
        ), $document->getEvents());

        $document->clearEvents();
        $document->setName('Pablo');
        $repository->save($document);

        $this->assertSame(array(
            'preUpdateExtensions',
            'preUpdate',
            'preSaveExtensions',
            'preSave',
            'postUpdateExtensions',
            'postUpdate',
            'postSaveExtensions',
            'postSave'
        ), $document->getEvents());
    }

    public function testDeleteUnique()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $repository->delete($articles[3]);
        $this->assertSame(9, $this->db->article->find()->count());
        $this->assertSame(0, $this->db->article->find(array('_id' => $articles[3]->getId()))->count());
    }

    public function testDeleteMultiple()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Article');
        $articles   = $this->createArticles(10);

        $repository->delete(array($articles[4], $articles[7]));
        $this->assertSame(8, $this->db->article->find()->count());
        $this->assertSame(0, $this->db->article->find(array(
            '_id' => array('$in' => array($articles[4]->getId(), $articles[7]->getId())),
        ))->count());
    }

    public function testDeleteEvents()
    {
        $repository = $this->mondongo->getRepository('Model\Document\Events');

        $document = new Events();
        $document->setName('Mondongo');
        $repository->save($document);

        $document->clearEvents();
        $repository->delete($document);

        $this->assertSame(array(
            'preDeleteExtensions',
            'preDelete',
            'postDeleteExtensions',
            'postDelete',
        ), $document->getEvents());
    }
}
