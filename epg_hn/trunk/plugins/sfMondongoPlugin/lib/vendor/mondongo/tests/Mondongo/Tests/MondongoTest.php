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
use Model\Document\Article;

class MondongoTest extends TestCase
{
    public function testLoggerCallable()
    {
        $loggerCallable = function() {};

        $mondongo = new Mondongo();
        $mondongo->setLoggerCallable($loggerCallable);
        $this->assertSame($loggerCallable, $mondongo->getLoggerCallable());
    }

    public function testConnections()
    {
        $connections = array(
            'local'  => new Connection('localhost', 'mondongo_tests_local'),
            'global' => new Connection('localhost', 'mondongo_tests_global'),
            'extra'  => new Connection('localhost', 'mondongo_tests_extra'),
        );

        // hasConnection, setConnection, getConnection
        $mondongo = new Mondongo();
        $this->assertFalse($mondongo->hasConnection('local'));
        $mondongo->setConnection('local', $connections['local']);
        $this->assertTrue($mondongo->hasConnection('local'));
        $mondongo->setConnection('extra', $connections['extra']);
        $this->assertSame($connections['local'], $mondongo->getConnection('local'));
        $this->assertSame($connections['extra'], $mondongo->getConnection('extra'));

        // setConnections, getConnections
        $mondongo = new Mondongo();
        $mondongo->setConnection('extra', $connections['extra']);
        $mondongo->setConnections($setConnections = array(
          'local'  => $connections['local'],
          'global' => $connections['global'],
        ));
        $this->assertEquals($setConnections, $mondongo->getConnections());

        // removeConnection
        $mondongo = new Mondongo();
        $mondongo->setConnections($connections);
        $mondongo->removeConnection('local');
        $this->assertSame(array(
          'global' => $connections['global'],
          'extra'  => $connections['extra'],
        ), $mondongo->getConnections());

        // clearConnections
        $mondongo = new Mondongo();
        $mondongo->setConnections($connections);
        $mondongo->clearConnections();
        $this->assertSame(array(), $mondongo->getConnections());

        // defaultConnection
        $mondongo = new Mondongo();
        $mondongo->setConnections($connections);
        $mondongo->setDefaultConnectionName('global');
        $this->assertSame($connections['global'], $mondongo->getDefaultConnection());
        $mondongo->setDefaultConnectionName(null);
        $this->assertSame($connections['local'], $mondongo->getDefaultConnection());
    }

    public function testDefaultConnectionName()
    {
        $mondongo = new Mondongo();
        $this->assertNull($mondongo->getDefaultConnectionName());
        $mondongo->setDefaultConnectionName('mondongo_connection');
        $this->assertSame('mondongo_connection', $mondongo->getDefaultConnectionName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveConnectionNotExists()
    {
        $mondongo = new Mondongo();
        $mondongo->removeConnection('no');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetConnectionNotExists()
    {
        $mondongo = new Mondongo();
        $mondongo->getConnection('no');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetDefaultConnectionNotExists()
    {
        $mondongo = new Mondongo();
        $mondongo->setDefaultConnectionName('local');
        $mondongo->getDefaultConnection();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testgetDefaultConnectionThereIsNotConnections()
    {
        $mondongo = new Mondongo();
        $mondongo->getDefaultConnection();
    }

    public function testGetRepository()
    {
        $mondongo = new Mondongo();

        $articleRepository = $mondongo->getRepository('Model\Document\Article');
        $this->assertInstanceOf('Model\Repository\Article', $articleRepository);
        $this->assertSame($mondongo, $articleRepository->getMondongo());
        $this->assertSame($articleRepository, $mondongo->getRepository('Model\Document\Article'));

        $userRepository = $mondongo->getRepository('Model\Document\User');
        $this->assertInstanceOf('Model\Repository\User', $userRepository);
    }

    public function testFind()
    {
        $articles = $this->createArticles(10);

        $this->assertEquals($articles, $this->mondongo->find('Model\Document\Article'));
    }

    public function testFindOptions()
    {
        $articles = $this->createArticles(10);

        $this->assertEquals($articles[3], $this->mondongo->find('Model\Document\Article', array(
            'query' => array('_id' => $articles[3]->getId()),
            'one'   => true,
        )));
    }

    public function testFindOne()
    {
        $articles = $this->createArticles(10);

        $this->assertEquals($articles[0], $this->mondongo->findOne('Model\Document\Article'));

        $this->assertEquals($articles[3], $this->mondongo->findOne('Model\Document\Article', array(
            'query' => array('_id' => $articles[3]->getId()),
        )));
    }

    public function testFindOneById()
    {
        $articles = $this->createArticles(10);

        $this->assertEquals($articles[3], $this->mondongo->findOneById('Model\Document\Article', $articles[3]->getId()));
    }

    public function testCount()
    {
        $articles = $this->createArticles(10);

        $this->assertSame(10, $this->mondongo->count('Model\Document\Article'));
    }

    public function testCountQuery()
    {
        $articles = $this->createArticles(10);

        for ($i = 1; $i <= 5; $i++) {
            $articles[$i]->setTitle('Count');
            $articles[$i]->save();
        }

        $this->assertSame(5, $this->mondongo->count('Model\Document\Article', array('title' => 'Count')));
    }

    public function testRemove()
    {
        $articles = $this->createArticles(10);

        $this->mondongo->remove('Model\Document\Article');

        $this->assertSame(0, $this->db->article->find()->count());
    }

    public function testRemoveOptions()
    {
        $articles = $this->createArticles(10);

        $articles[3]->setTitle('No');
        $articles[3]->save();

        $this->mondongo->remove('Model\Document\Article', array('title' => new \MongoRegex('/^Article/')));

        $this->assertSame(1, $this->db->article->find()->count());
        $this->assertSame(1, $this->db->article->find(array('_id' => $articles[3]->getId()))->count());
    }

    public function testSave()
    {
        $article = new Article();
        $article->setTitle('Title');
        $this->mondongo->save('Model\Document\Article', $article);

        $this->assertFalse($article->isNew());
    }

    public function testDelete()
    {
        $articles = $this->createArticles(10);

        $this->mondongo->delete('Model\Document\Article', $articles[5]);

        $this->assertSame(9, $this->db->article->find()->count());
    }
}
