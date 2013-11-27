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

use Mondongo\Tests\TestCase;
use Mondongo\Extension\Core;
use Mondongo\Group;
use Mondongo\Mondator\Container;
use Mondongo\Mondongo;
use Model\Document\Article;
use Model\Document\Author;
use Model\Document\AuthorTelephone;
use Model\Document\Category;
use Model\Document\Comment;
use Model\Document\EmbedNot;
use Model\Document\Source;
use Model\Document\User;

class CoreTest extends TestCase
{
    public function testDocumentMondongoParentClass()
    {
        $r = new \ReflectionClass('Model\Document\Article');
        $this->assertTrue($r->isSubclassOf('Mondongo\Document\Document'));

        $r = new \ReflectionClass('Model\Document\Comment');
        $this->assertTrue($r->isSubclassOf('Mondongo\Document\EmbeddedDocument'));
        $this->assertFalse($r->isSubclassOf('Mondongo\Document\Document'));
    }

    public function testDocumentBaseGetMondongoMethodNamespaced()
    {
        $article = new Article();
        $this->assertSame($this->mondongo, $article->getMondongo());

        $mondongo = new Mondongo();
        \Mondongo\Container::setForDocumentClass('Model\Document\Article', $mondongo);
        $this->assertSame($mondongo, $article->getMondongo());
    }

    public function testDocumentBaseGetMondongoMethodNotNamespaced()
    {
        $article = new \Article();
        $this->assertSame($this->mondongo, $article->getMondongo());

        $mondongo = new Mondongo();
        \Mondongo\Container::setForDocumentClass('Article', $mondongo);
        $this->assertSame($mondongo, $article->getMondongo());
    }

    public function testDocumentBaseGetRepositoryMethodNamespaced()
    {
        $article = new Article();
        $this->assertSame($this->mondongo->getRepository('Model\Document\Article'), $article->getRepository());

        $user = new User();
        $this->assertSame($this->mondongo->getRepository('Model\Document\User'), $user->getRepository());
    }

    public function testDocumentBaseGetRepositoryMethodNotNamespaced()
    {
        $article = new \Article();
        $this->assertSame($this->mondongo->getRepository('Article'), $article->getRepository());
    }

    public function testEmbedNotRepository()
    {
        $this->assertFalse(class_exists('Model\Repository\EmbedNot'));
        $this->assertFalse(class_exists('Model\Repository\Base\EmbedNot'));
    }

    public function testEmbedNotDocumentGetMondongoMethod()
    {
        $embedNot = new EmbedNot();
        $this->assertFalse(method_exists($embedNot, 'getMondongo'));
    }

    public function testEmbedNotDocumentGetRepositoryMethod()
    {
        $embedNot = new EmbedNot();
        $this->assertFalse(method_exists($embedNot, 'getRepository'));
    }

    public function testDocumentDataProperty()
    {
        $article = new Article();
        $this->assertSame(array(
            'fields' => array(
                'title'        => null,
                'slug'         => null,
                'content'      => null,
                'is_active'    => null,
                'author_id'    => null,
                'category_ids' => null,
            ),
            'references' => array(
                'author'     => null,
                'categories' => null,
            ),
            'embeddeds' => array(
                'source'   => null,
                'comments' => null,
            ),
            'relations' => array(
                'summary' => null,
                'news'    => null,
            ),
        ), $article->getDocumentData());
    }

    public function testDocumentFieldsModifiedsProperty()
    {
        $user = new User();
        $this->assertSame(array('is_active' => null), $user->getFieldsModified());
    }

    public function testDocumentFieldsSettersGettersMethods()
    {
        $article = new Article();
        $article->setTitle('Mondongo');
        $this->assertSame('Mondongo', $article->getTitle());
        $article->setTitle('Mondongo 1');
        $this->assertSame(array('title' => null), $article->getFieldsModified());
    }

    public function testDocumentReferencesOneSettersGetters()
    {
        $author = new Author();
        $author->setName('Pablo');
        $author->save();

        $article = new Article();
        $article->setAuthor($author);

        $this->assertSame($author->getId(), $article->getAuthorId());
        $this->assertSame($author, $article->getAuthor());

        $article->save();

        $article = $this->mondongo->getRepository('Model\Document\Article')->findOneById($article->getId());
        $this->assertEquals($author, $a = $article->getAuthor());
        $this->assertSame($a, $article->getAuthor());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDocumentReferencesOneSetterInvalidReferenceClass()
    {
        $article = new Article();
        $article->setAuthor(new Category());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDocumentReferencesOneSetterReferenceNew()
    {
        $article = new Article();
        $article->setAuthor(new Author());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDocumentReferencesOneGetterNotExists()
    {
        $article = new Article();
        $article->setAuthorId(new \MongoId('123'));
        $article->getAuthor();
    }

    public function testDocumentReferencesManySettersGetters()
    {
        $categories = array();
        $ids        = array();
        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category->setName('Category '.$i);
            $category->save();
            if (5 != $i) {
                $categories[] = $category;
                $ids[]        = $category->getId();
            }
        }

        $group = new Group($categories);

        $article = new Article();
        $article->setCategories($group);

        $this->assertSame($group, $article->getCategories());
        $this->assertSame($ids, $article->getCategoryIds());
        $this->assertSame(array($article, 'updateCategories'), $group->getChangeCallback());

        $article->save();

        $article = $this->mondongo->getRepository('Model\Document\Article')->findOneById($article->getId());
        $this->assertEquals($group, $g = $article->getCategories());
        $this->assertSame($g, $article->getCategories());
    }

    public function testDocumentReferencesManyGetterWithoutIdsDocumentNew()
    {
        $article = new Article();
        $categories = $article->getCategories();
        $this->assertInstanceOf('\Mondongo\Group', $categories);
        $this->assertSame(0, count($categories));
    }

    public function testDocumentReferencesManyGetterWithoutIdsDocumentNotNew()
    {
        $article = new Article();
        $article->setTitle('Mondongo');
        $article->save();

        $categories = $article->getCategories();
        $this->assertInstanceOf('\Mondongo\Group', $categories);
        $this->assertSame(0, count($categories));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDocumentReferencesManySetterNotGroup()
    {
        $article = new Article();
        $article->setCategories(new Category());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDocumentReferencesManySetterInvalidReferenceClass()
    {
        $categories = array();
        for ($i = 1; $i <= 4; $i++) {
            $categories[] = $category = new Category();
            $category->setName('Category '.$i);
            $category->save();
        }

        $author = new Author();
        $author->setName('Pablo');
        $author->save();

        $group = new Group($categories);
        $group->add($author);

        $article = new Article();
        $article->setCategories($group);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDocumentReferencesManySetterReferenceNew()
    {
        $categories = array();
        for ($i = 1; $i <= 4; $i++) {
            $categories[] = $category = new Category();
            $category->setName('Category '.$i);
            if (3 != $i) {
                $category->save();
            }
        }

        $article = new Article();
        $article->setCategories(new Group($categories));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDocumentReferencesManyGetterNotExists()
    {
        $article = new Article();
        $article->setCategoryIds(array(new \MongoId('123')));
        $article->getCategories();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDocumentReferencesManyGetterSomeNotExists()
    {
        $categories = array();
        $ids        = array();
        for ($i = 1; $i <= 4; $i++) {
            $categories[] = $category = new Category();
            $category->setName('Category '.$i);
            $category->save();
            if (3 != $i) {
                $ids[] = $category->getId();
            }
        }
        $ids[] = new \MongoId('123');

        $article = new Article();
        $article->setCategoryIds($ids);
        $article->getCategories();
    }

    public function testDocumentReferencesManyUpdate()
    {
        $categories = array();
        for ($i = 1; $i <= 4; $i++) {
            $categories[] = $category = new Category();
            $category->setName('Category '.$i);
            $category->save();
        }

        $group = new Group(array($categories[0], $categories[2]));

        $article = new Article();
        $article->setCategories($group);

        $group->setChangeCallback(null);
        $group->add($categories[1]);
        $this->assertSame(array($categories[0]->getId(), $categories[2]->getId()), $article->getCategoryIds());
        $article->updateCategories();
        $this->assertSame(array($categories[0]->getId(), $categories[2]->getId(), $categories[1]->getId()), $article->getCategoryIds());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDocumentReferencesManyUpdateReferenceClass()
    {
        $categories = array();
        for ($i = 1; $i <= 4; $i++) {
            $categories[] = $category = new Category();
            $category->setName('Category '.$i);
            $category->save();
        }

        $group = new Group($categories);

        $article = new Article();
        $article->setCategories($group);

        $author = new Author();
        $author->setName('Pablo');
        $author->save();

        $group->add($author);
        $article->updateCategories();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDocumentReferencesManyUpdateReferenceNew()
    {
        $categories = array();
        for ($i = 1; $i <= 4; $i++) {
            $categories[] = $category = new Category();
            $category->setName('Category '.$i);
            $category->save();
        }

        $group = new Group($categories);

        $article = new Article();
        $article->setCategories($group);
        $group->add(new Category());
        $article->updateCategories();
    }

    public function testEmbeddedDocumentsOne()
    {
        $article = new Article();

        $this->assertEquals(new Source(), $source = $article->getSource());
        $this->assertSame($source, $article->getSource());

        $source = new Source();
        $article->setSource($source);
        $this->assertSame($source, $article->getSource());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEmbeddedDocumentsOneSetterInvalidEmbedClass()
    {
        $article = new Article();
        $article->setSource(new Comment());
    }

    public function testEmbeddedDocumentsMany()
    {
        $article = new Article();

        $this->assertEquals(new Group(), $group = $article->getComments());
        $this->assertSame($group, $article->getComments());

        $groups = new Group();
        $article->setComments($group);
        $this->assertSame($group, $article->getComments());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEmbeddedDocumentsManySetterNotGroup()
    {
        $article = new Article();
        $article->setComments(new Comment());
    }

    public function testDocumentRelationsOneOne()
    {
        $telephone = new AuthorTelephone();
        $telephone->setNumber('123');
        $telephone->save();

        $telephoneAuthor = array();
        for ($i = 1; $i <= 10; $i++) {
            $author = new Author();
            $author->setName('Author '.$i);
            if (3 == $i) {
                $telephoneAuthor = $author;
                $author->setTelephoneId($telephone->getId());
            }
            $author->save();
        }

        $this->assertEquals($telephoneAuthor, $result = $telephone->getAuthor());
        $this->assertSame($result, $telephone->getAuthor());
    }

    public function testDocumentRelationsOneMany()
    {
        $author = new Author();
        $author->setName('Pablo');
        $author->save();

        $articles = array();
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            if ($i % 2) {
                $articles[] = $article;
                $article->setAuthorId($author->getId());
            }
            $article->setTitle('Article '.$i);
            $article->save();
        }

        $this->assertEquals($articles, $results = $author->getArticles());

        $this->assertSame($results, $author->getArticles());
    }

    public function testDocumentRelationsManyMany()
    {
        $category = new Category();
        $category->setName('Mondongo');
        $category->save();

        $articles = array();
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            if ($i % 2) {
                $articles[] = $article;
                $article->setCategoryIds(array($category->getId()));
            }
            $article->setTitle('Article '.$i);
            $article->save();
        }

        $this->assertEquals($articles, $results = $category->getArticles());

        $this->assertSame($results, $category->getArticles());
    }

    public function testDocumentSetDocumentDataMethod()
    {
        $user = new User();
        $user->setDocumentData(array(
            '_id'       => $id = new \MongoId('123'),
            'username'  => 123456,
            'is_active' => 1,
        ));
        $this->assertSame($id, $user->getId());
        $this->assertSame('123456', $user->getUsername());
        $this->assertSame(true, $user->getIsActive());
        $this->assertSame(array(), $user->getFieldsModified());
    }

    public function testDocumentSetDocumentDataMethodEmbeddeds()
    {
        $article = new Article();
        $article->setDocumentData(array(
            '_id' => new \MongoId('123'),
            'title' => 123456,
            'source' => array(
                'name' => 456,
                'url' => 'http://mondongo.es',
            ),
            'comments' => array(
                array(
                    'name' => 123456,
                    'text' => 789,
                ),
                array(
                    'name' => 1.23,
                    'text' => 7.89,
                ),
            ),
        ));

        $this->assertSame('123456', $article->getTitle());

        $source = $article->getSource();
        $this->assertSame('456', $source->getName());
        $this->assertSame('http://mondongo.es', $source->getUrl());

        $comments = $article->getComments();
        $this->assertInstanceOf('Mondongo\\Group', $comments);
        $this->assertSame(2, $comments->count());
        $elements = $comments->getElements();
        $this->assertSame('123456', $elements[0]->getName());
        $this->assertSame('789', $elements[0]->getText());
        $this->assertSame('1.23', $elements[1]->getName());
        $this->assertSame('7.89', $elements[1]->getText());
        $this->assertSame($elements, $comments->getOriginalElements());
    }

    public function testDocumentFieldsToMongoMethod()
    {
        $user = new User();
        $this->assertSame(array(
            'username'  => '123456',
            'is_active' => true,
        ), $user->fieldsToMongo(array('username' => 123456, 'is_active' => 1)));
    }

    public function testRepositoryDocumentClassPropertyNamespaced()
    {
        $this->assertSame('Model\Document\Article', $this->mondongo->getRepository('Model\Document\Article')->getDocumentClass());
    }

    public function testRepositoryDocumentClassPropertyNotNamespaced()
    {
        $this->assertSame('Article', $this->mondongo->getRepository('Article')->getDocumentClass());
    }

    public function testRepositoryConnectionNameProperty()
    {
        $this->assertNull($this->mondongo->getRepository('Model\Document\Article')->getConnectionName());
        $this->assertSame('global', $this->mondongo->getRepository('Model\Document\ConnectionGlobal')->getConnectionName());
    }

    public function testRepositoryCollectionNameProperty()
    {
        $this->assertSame('article', $this->mondongo->getRepository('\Model\Document\Article')->getCollectionName());
        $this->assertSame('my_name', $this->mondongo->getRepository('Model\Document\CollectionName')->getCollectionName());
    }

    public function testRepositoryIsFileProperty()
    {
        $this->assertTrue($this->mondongo->getRepository('Model\Document\Image')->isFile());
        $this->assertFalse($this->mondongo->getRepository('Model\Document\Article')->isFile());
    }

    public function testRepositoryEnsureIndexesMethod()
    {
        $this->mondongo->getRepository('Model\Document\Article')->ensureIndexes();

        $indexInfo = $this->db->article->getIndexInfo();

        $this->assertSame(array('slug' => 1), $indexInfo[1]['key']);
        $this->assertSame(true, $indexInfo[1]['unique']);

        $this->assertSame(array('author_id' => 1, 'is_active' => 1), $indexInfo[2]['key']);
    }

    /*
     * Errors.
     */

    /**
     * @expectedException \RuntimeException
     */
    public function testDocumentDoesNotHaveOutput()
    {
        $extension = new Core(array(
            'default_repository_output' => '/tmp',
        ));
        $extension->process(new Container(), 'Article', new \ArrayObject(), new \ArrayObject());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRepositoryDoesNotHaveOutput()
    {
        $extension = new Core(array(
            'default_document_output' => '/tmp',
        ));
        $extension->process(new Container(), 'Article', new \ArrayObject(), new \ArrayObject());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testIsFileNotBoolean()
    {
        $extension = new Core();
        $extension->process(new Container(), 'Article', new \ArrayObject(array(
            'is_file' => 1,
        )), new \ArrayObject());
    }

    /**
     * @expectedException \RuntimeException
     * @dataProvider      providerFieldNotStringNorArray
     */
    public function testFieldNotStringNorArray($type)
    {
        $extension = new Core();
        $extension->process(new Container(), 'Article', new \ArrayObject(array(
            'fields' => array(
                'field' => $type,
            ),
        )), new \ArrayObject());
    }

    public function providerFieldNotStringNorArray()
    {
        return array(
            array(1),
            array(1,1),
            array(true),
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFieldDoesNotHaveType()
    {
        $extension = new Core();
        $extension->process(new Container(), 'Article', new \ArrayObject(array(
            'fields' => array(
                'field' => array('default' => 'default'),
            ),
        )), new \ArrayObject());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFieldTypeDoesNotExists()
    {
        $extension = new Core();
        $extension->process(new Container(), 'Article', new \ArrayObject(array(
            'fields' => array(
                'field' => array('type' => 'no'),
            ),
        )), new \ArrayObject());
    }
}
