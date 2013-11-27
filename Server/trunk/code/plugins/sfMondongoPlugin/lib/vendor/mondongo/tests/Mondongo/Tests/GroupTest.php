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

use Mondongo\Group;

class GroupTest extends \PHPUnit_Framework_TestCase
{
    protected $group;

    protected $elements = array(
        'foo',
        'bar',
        'foobar',
        'barfoo',
    );

    protected $changeCallback;

    protected $changeValue;

    public function setUp()
    {
        parent::setUp();

        $this->changeCallback = array($this, 'changeCallback');
        $this->changeValue    = false;
    }

    public function changeCallback($group)
    {
        $this->assertSame($this->group, $group);
        $this->changeValue = true;
    }

    public function testConstructor()
    {
        $this->group = new Group();
        $this->assertSame(array(), $this->group->getElements());

        $this->group = new Group($this->elements);
        $this->assertSame($this->elements, $this->group->getElements());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorElementsRepeated()
    {
        new Group(array('foo', 'bar', 'foo'));
    }

    public function testSetGetElements()
    {
        $this->group = new Group();
        $this->group->setElements($this->elements);
        $this->assertSame($this->elements, $this->group->getElements());
    }

    public function testOriginalElements()
    {
        $group = new Group();
        $this->assertSame(array(), $group->getOriginalElements());
        $group->setElements($this->elements);
        $this->assertSame(array(), $group->getOriginalElements());
        $group->saveOriginalElements();
        $this->assertSame($this->elements, $group->getOriginalElements());
        $group->remove('foobar');
        $this->assertSame($this->elements, $group->getOriginalElements());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetElementsRepeated()
    {
        $this->group = new Group();
        $this->group->setElements(array('foo', 'bar', 'foo'));
    }

    public function testChangeCallback()
    {
        $this->group = new Group();
        $this->group->setChangeCallback($this->changeCallback);
        $this->assertSame($this->changeCallback, $this->group->getChangeCallback());
    }

    public function testExists()
    {
        $this->group = new Group($this->elements);
        $this->assertTrue($this->group->exists('foo'));
        $this->assertFalse($this->group->exists('foofoo'));
    }

    public function testAdd()
    {
        $this->group = new Group($this->elements);
        $this->group->add('foofoo');
        $this->assertSame(array_merge($this->elements, array('foofoo')), $this->group->getElements());

        $this->assertFalse($this->changeValue);
        $this->group->setChangeCallback($this->changeCallback);
        $this->group->add('barbar');
        $this->assertTrue($this->changeValue);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddRepeated()
    {
        $this->group = new Group($this->elements);
        $this->group->add('foo');
    }

    public function testRemove()
    {
        $this->group = new Group($this->elements);
        $this->group->remove('bar');

        $elements = $this->elements;
        unset($elements[array_search('bar', $elements)]);
        $this->assertSame($elements, $this->group->getElements());

        $this->assertFalse($this->changeValue);
        $this->group->setChangeCallback($this->changeCallback);
        $this->group->remove('foo');
        $this->assertTrue($this->changeValue);
    }

    public function testExistsByKey()
    {
        $group = new Group($this->elements);
        $this->assertTrue($group->existsByKey(2));
        $this->assertFalse($group->existsByKey(4));
    }

    public function testGetByKey()
    {
        $group = new Group($this->elements);
        $this->assertSame('foo', $group->getByKey(0));
        $this->assertSame('foobar', $group->getByKey(2));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetByKeyNotExists()
    {
        $group = new Group($this->elements);
        $group->getByKey(4);
    }

    public function testRemoveByKey()
    {
        $this->group = new Group($this->elements);
        $this->group->removeByKey(1);

        $elements = $this->elements;
        unset($elements[1]);
        $this->assertSame($elements, $this->group->getElements());

        $this->assertFalse($this->changeValue);
        $this->group->setChangeCallback($this->changeCallback);
        $this->group->removeByKey(0);
        $this->assertTrue($this->changeValue);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveByKeyNotExists()
    {
        $group = new Group($this->elements);
        $group->removeByKey(4);
    }

    public function testClear()
    {
        $this->group = new Group($this->elements);
        $this->group->clear();
        $this->assertSame(array(), $this->group->getElements());

        $this->assertFalse($this->changeValue);
        $this->group->setChangeCallback($this->changeCallback);
        $this->group->clear();
        $this->assertTrue($this->changeValue);
    }

    public function testCount()
    {
        $this->group = new Group($this->elements);
        $this->assertSame(4, $this->group->count());
    }

    public function testCountableInterface()
    {
        $this->group = new Group($this->elements);
        $this->assertSame(4, count($this->group));
    }

    public function testGetIterator()
    {
        $this->group = new Group($this->elements);
        $this->assertEquals(new \ArrayIterator($this->elements), $this->group->getIterator());
    }
}
