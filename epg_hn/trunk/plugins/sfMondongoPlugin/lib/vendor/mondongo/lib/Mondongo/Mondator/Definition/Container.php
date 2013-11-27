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

namespace Mondongo\Mondator\Definition;

/**
 * Container of definitions.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Container implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $definitions = array();

    /**
     * Returns if a definition name exists.
     *
     * @param string $name The definition name.
     *
     * @return bool Returns if the definition name exists.
     */
    public function hasDefinition($name)
    {
        return isset($this->definitions[$name]);
    }

    /**
     * Set a definition.
     *
     * @param string                                  $name       The definition name.
     * @param Mondongo\Mondator\Definition\Definition $definition The definition.
     *
     * @return void
     */
    public function setDefinition($name, Definition $definition)
    {
        $this->definitions[$name] = $definition;
    }

    /**
     * Set the definitions.
     *
     * @param array $definitions An array of definitions.
     *
     * @return void
     */
    public function setDefinitions(array $definitions)
    {
        $this->definitions = array();
        foreach ($definitions as $name => $definition) {
            $this->setDefinition($name, $definition);
        }
    }

    /**
     * Returns a definition by name.
     *
     * @param string $name The definition name.
     *
     * @return Mondongo\Mondator\Definition\Definition The definition.
     *
     * @throws \InvalidArgumentException If the definition does not exists.
     */
    public function getDefinition($name)
    {
        if (!$this->hasDefinition($name)) {
            throw new \InvalidArgumentException(sprintf('The definition "%s" does not exists.', $name));
        }

        return $this->definitions[$name];
    }

    /**
     * Returns the definitions.
     *
     * @return arary The definitions.
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Remove a definition
     *
     * @param string $name The definition name
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the definition does not exists.
     */
    public function removeDefinition($name)
    {
        if (!$this->hasDefinition($name)) {
            throw new \InvalidArgumentException(sprintf('The definition "%s" does not exists.', $name));
        }

        unset($this->definitions[$name]);
    }

    /**
     * Clear the definitions.
     *
     * @return void
     */
    public function clearDefinitions()
    {
        $this->definitions = array();
    }

    /*
     * \ArrayAccess interface.
     */
    public function offsetExists($name)
    {
        return $this->hasDefinition($name);
    }

    public function offsetSet($name, $definition)
    {
        $this->setDefinition($name, $definition);
    }

    public function offsetGet($name)
    {
        return $this->getDefinition($name);
    }

    public function offsetUnset($name)
    {
        $this->removeDefinition($name);
    }

    /**
     * Returns the number of definitions (implements the \Countable interface).
     *
     * @return integer The number of definitions.
     */
    public function count()
    {
        return count($this->definitions);
    }

    /**
     * Returns an \ArrayIterator with the definitions (implements \IteratorAggregate interface).
     *
     * @return \ArrayIterator An \ArrayIterator with the definitions.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->definitions);
    }
}
