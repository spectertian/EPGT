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
use Model\Document\Article;
use Model\Document\Author;
use Model\Document\Category;
use Model\Document\Comment;
use Model\Document\Source;

class DocumentFromToArrayTest extends TestCase
{
    public function testFromArray()
    {
        $article = new Article();
        $article->fromArray(array(
            'title'     => 'Mondongo',
            'content'   => 'Content',
            'is_active' => true,
            'author_id' => '123',
            'category_ids' => array(
                '234',
                '345',
            ),
            'source' => array(
                'name' => 'Mondongo',
                'url'  => 'http://mondongo.es',
            ),
            'comments' => array(
                array(
                    'name' => 'Pablo',
                    'text' => 'Wow',
                ),
                array(
                    'name' => 'Name 2',
                    'text' => 'Text 2',
                ),
            ),
        ));

        $this->assertSame('Mondongo', $article->getTitle());
        $this->assertSame('Content', $article->getContent());
        $this->assertTrue($article->getIsActive());
        $this->assertSame('123', $article->getAuthorId());
        $this->assertSame(array('234', '345'), $article->getCategoryIds());
        $this->assertSame('Mondongo', $article->getSource()->getName());
        $this->assertSame('http://mondongo.es', $article->getSource()->getUrl());
        $this->assertSame(2, $article->getComments()->count());
        $this->assertSame('Pablo', $article->getComments()->getByKey(0)->getName());
        $this->assertSame('Wow', $article->getComments()->getByKey(0)->getText());
        $this->assertSame('Name 2', $article->getComments()->getByKey(1)->getName());
        $this->assertSame('Text 2', $article->getComments()->getByKey(1)->getText());
    }

    public function testFromArrayReferencesOne()
    {
        $author = new Author();
        $author->setName('Pablo');
        $author->save();

        $article = new Article();
        $article->fromArray(array(
            'author' => $author,
        ));

        $this->assertSame($author, $article->getAuthor());
    }

    public function testFromArrayReferencesMany()
    {
        $categories = array();
        for ($i = 1; $i <= 10; $i++) {
            $categories[] = $category = new Category();
            $category->setName('Category '.$i);
            $category->save();
        }

        $article = new Article();
        $article->fromArray(array(
            'categories' => $categories,
        ));

        $this->assertSame($categories, $article->getCategories()->getElements());
    }

    public function testFromArrayEmbeddedsOne()
    {
        $source = new Source();

        $article = new Article();
        $article->fromArray(array(
            'source' => $source,
        ));

        $this->assertSame($source, $article->getSource());
    }

    public function testFromArrayEmbeddedsMany()
    {
        $comments = array();
        for ($i = 1; $i <= 10; $i++) {
            $comments[] = new Comment();
        }

        $article = new Article();
        $article->fromArray(array(
            'comments' => $comments,
        ));

        $this->assertSame($comments, $article->getComments()->getElements());
    }

    public function testToArray()
    {
        $article = new Article();
        $article->setTitle('Mondongo');
        $article->setContent('Content');
        $article->setIsActive(true);
        $article->getSource()->setName('Mondongo');
        $article->getSource()->setUrl('http://mondongo.es');
        $article->getComments()->add($comment = new Comment());
        $comment->setName('Pablo');
        $comment->setText('Wow');
        $article->getComments()->add($comment = new Comment());
        $comment->setName('Name 2');
        $comment->setText('Text 2');

        $this->assertSame(array(
            'title'     => 'Mondongo',
            'content'   => 'Content',
            'is_active' => true,
            'source'    => array(
                'name' => 'Mondongo',
                'url'  => 'http://mondongo.es',
            ),
            'comments' => array(
                array(
                    'name' => 'Pablo',
                    'text' => 'Wow',
                ),
                array(
                    'name' => 'Name 2',
                    'text' => 'Text 2',
                ),
            ),
        ), $article->toArray());

        $this->assertSame(array(
            'title'     => 'Mondongo',
            'content'   => 'Content',
            'is_active' => true,
        ), $article->toArray(false));
    }
}
