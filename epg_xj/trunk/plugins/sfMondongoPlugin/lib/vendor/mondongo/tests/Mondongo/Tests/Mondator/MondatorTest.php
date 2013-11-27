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

namespace Mondongo\Tests\Mondator;

use Mondongo\Mondator\Extension;
use Mondongo\Mondator\Mondator;

class MondatorTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigClasses()
    {
        $mondator = new Mondator();
        $mondator->setConfigClass('Article', $article = array(
            'title'   => 'string',
            'content' => 'string',
        ));
        $mondator->setConfigClass('Comment', $comment = array(
            'name' => 'string',
            'text' => 'string',
        ));

        $this->assertTrue($mondator->hasConfigClass('Article'));
        $this->assertFalse($mondator->hasConfigClass('Category'));

        $this->assertSame($article, $mondator->getConfigClass('Article'));
        $this->assertSame($comment, $mondator->getConfigClass('Comment'));

        $this->assertSame(array('Article' => $article, 'Comment' => $comment), $mondator->getConfigClasses());

        $mondator->setConfigClasses($classes = array(
            'Category' => array('name' => 'string'),
            'Post'     => array('message' => 'string'),
        ));
        $this->assertSame($classes, $mondator->getConfigClasses());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetConfigClassNotExists()
    {
        $mondator = new Mondator();
        $mondator->getConfigClass('Article');
    }

    public function testExtensions()
    {
        $extension1 = new ExtensionTesting(array('required' => 'value'));
        $extension2 = new ExtensionTesting(array('required' => 'value'));
        $extension3 = new ExtensionTesting(array('required' => 'value'));
        $extension4 = new ExtensionTesting(array('required' => 'value'));

        $mondator = new Mondator();

        $mondator->addExtension($extension1);
        $mondator->addExtension($extension2);
        $this->assertSame(array($extension1, $extension2), $mondator->getExtensions());

        $mondator->setExtensions($extensions = array($extension3, $extension4));
        $this->assertSame($extensions, $mondator->getExtensions());
    }

    public function testGenerateContainers()
    {
        $mondator = new Mondator();
        $mondator->setConfigClasses(array(
            'Article' => array(
                'name' => 'foo',
            ),
            'Category' => array(
                'name' => 'bar',
            ),
        ));
        $mondator->setExtensions(array(
            new \Mondongo\Tests\Mondator\Fixtures\Extension\Name(),
            new \Mondongo\Tests\Mondator\Fixtures\Extension\InitDefinition(array(
                'definition_name' => 'myclass',
                'class_name'      => 'MiClase',
            )),
            new \Mondongo\Tests\Mondator\Fixtures\Extension\AddProperty(array(
                'definition' => 'myclass',
                'visibility' => 'public',
                'name'       => 'MiPropiedad',
                'value'      => 'foobar',
            )),
        ));

        $containers = $mondator->generateContainers();

        $this->assertSame(2, count($containers));
        $this->assertTrue(isset($containers['Article']));
        $this->assertTrue(isset($containers['Category']));
        $this->assertInstanceOf('Mondongo\Mondator\Container', $containers['Article']);
        $this->assertInstanceOf('Mondongo\Mondator\Container', $containers['Category']);

        $definitions = $containers['Article']->getDefinitions();
        $this->assertSame(2, count($definitions->getDefinitions()));
        $this->assertTrue(isset($definitions['name']));
        $this->assertTrue(isset($definitions['myclass']));
        $this->assertSame('foo', $definitions['name']->getClassName());

        $definitions = $containers['Category']->getDefinitions();
        $this->assertSame(2, count($definitions->getDefinitions()));
        $this->assertTrue(isset($definitions['name']));
        $this->assertTrue(isset($definitions['myclass']));
        $this->assertSame('bar', $definitions['name']->getClassName());
    }

    public function testGenerateContainersNewConfigClasses()
    {
        $mondator = new Mondator();
        $mondator->setConfigClasses(array(
            'Article' => array(
                'name' => 'MyArticle',
                'extensions' => array(
                    array(
                        'class'   => 'Mondongo\Tests\Mondator\Fixtures\Extension\NewConfigClass',
                        'options' => array(
                            'suffix'   => 'Translation',
                            'name'     => 'translation',
                            'multiple' => true,
                            'multiple_suffix' => 'Multiple',
                            'multiple_name'   => 'multiplex',
                        ),
                    ),
                ),
            ),
            'Category' => array(
                'name' => 'MyCategory',
            ),
        ));
        $mondator->setExtensions(array(
            new \Mondongo\Tests\Mondator\Fixtures\Extension\Name(),
            new \Mondongo\Tests\Mondator\Fixtures\Extension\ProcessOthersFromArray(),
        ));

        $containers = $mondator->generateContainers();

        $this->assertSame(4, count($containers));
        $this->assertTrue(isset($containers['Article']));
        $this->assertTrue(isset($containers['ArticleTranslation']));
        $this->assertTrue(isset($containers['ArticleTranslationMultiple']));
        $this->assertTrue(isset($containers['Category']));
    }
}
