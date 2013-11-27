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

namespace Mondongo;

/**
 * Group.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Group implements \Countable, \IteratorAggregate
{
    protected $elements = array();

    protected $originalElements = array();

    protected $changeCallback;

    /**
     * Constructor.
     *
     * @param array $elements An array of elements.
     *
     * @return void
     */
    public function __construct(array $elements = array())
    {
        $this->setElements($elements);
    }

    /**
     * Set the elements.
     *
     * @param array $elements An array of elements.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If some element already exists.
     */
    public function setElements(array $elements)
    {
        $this->elements = array();
        foreach ($elements as $element) {
            if (in_array($element, $this->elements, true)) {
                throw new \InvalidArgumentException('Some element already exists.');
            }
            $this->elements[] = $element;
        }
    }

    /**
     * Returns the elements.
     *
     * @return array The elements.
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Save the original elements.
     *
     * @return void
     */
    public function saveOriginalElements()
    {
        $this->originalElements = $this->elements;
    }

    /**
     * Returns the original elements.
     *
     * @return array The original elements.
     */
    public function getOriginalElements()
    {
        return $this->originalElements;
    }

    /**
     * Set the change callback.
     *
     * @param mixed $changeCallback A callback.
     *
     * @return void
     */
    public function setChangeCallback($changeCallback)
    {
        $this->changeCallback = $changeCallback;
    }

    /**
     * Returns the change callback.
     *
     * @return mixed The change callback.
     */
    public function getChangeCallback()
    {
        return $this->changeCallback;
    }

    protected function changeCallback()
    {
        if ($this->changeCallback) {
            call_user_func($this->changeCallback, $this);
        }
    }

    /**
     * Returns if exists an element.
     *
     * @param mixed $element An element.
     *
     * @return bool Returns if exists an element.
     */
    public function exists($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * Add an elements.
     *
     * @param mixed $element An element.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the element already exists.
     */
    public function add($element)
    {
        if ($this->exists($element)) {
            throw new \InvalidArgumentException('The element already exists.');
        }

        $this->elements[] = $element;

        $this->changeCallback();
    }

    /**
     * Remove an elements.
     *
     * @param mixed $element An element.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the element does not exists.
     */
    public function remove($element)
    {
        if (false === $key = array_search($element, $this->elements, true)) {
            throw new \InvalidArgumentException('The element does not exists.');
        }

        unset($this->elements[$key]);

        $this->changeCallback();
    }

    /**
     * Returns if exists a key.
     *
     * @param mixed $key The key.
     *
     * @return bool Returns if exists a key.
     */
    public function existsByKey($key)
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * Returns an element by key.
     *
     * @param mixed $key The element key.
     *
     * @return mixed The element if exists.
     *
     * @throws \InvalidArgumentException If the key does not exists.
     */
    public function getByKey($key)
    {
        if (!$this->existsByKey($key)) {
            throw new \InvalidArgumentException(sprintf('The key "%s" does not exists.', $key));
        }

        return $this->elements[$key];
    }

    /**
     * Remove an element by key.
     *
     * @param mixed $key The key.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the key does not exists.
     */
    public function removeByKey($key)
    {
        if (!$this->existsByKey($key)) {
            throw new \InvalidArgumentException(sprintf('The key "%s" does not exists.', $key));
        }

        unset($this->elements[$key]);

        $this->changeCallback();
    }

    /**
     * Clear the group.
     *
     * @return void
     */
    public function clear()
    {
        $this->elements = array();

        $this->changeCallback();
    }

    /**
     * Returns the number of elements (implements the \Countable interface).
     *
     * @return integer The number of elements.
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * Returns an \ArrayIterator with the elements (implements \IteratorAggregate interface).
     *
     * @return \ArrayIterator An \ArrayIterator with the elements.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }
}
