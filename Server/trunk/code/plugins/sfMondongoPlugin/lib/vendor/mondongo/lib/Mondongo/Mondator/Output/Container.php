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

namespace Mondongo\Mondator\Output;

/**
 * Container of outputs.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Container implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $outputs = array();

    /**
     * Returns if a output name exists.
     *
     * @param string $name The output name.
     *
     * @return bool Returns if the output name exists.
     */
    public function hasOutput($name)
    {
        return isset($this->outputs[$name]);
    }

    /**
     * Set a output.
     *
     * @param string                          $name   The output name.
     * @param Mondongo\Mondator\Output\Output $output The output.
     *
     * @return void
     */
    public function setOutput($name, Output $output)
    {
        $this->outputs[$name] = $output;
    }

    /**
     * Set the outputs.
     *
     * @param array $outputs An array of outputs.
     *
     * @return void
     */
    public function setOutputs(array $outputs)
    {
        $this->outputs = array();
        foreach ($outputs as $name => $output) {
            $this->setOutput($name, $output);
        }
    }

    /**
     * Returns a output by name.
     *
     * @param string $name The output name.
     *
     * @return Mondongo\Mondator\Output\Output The output.
     *
     * @throws \InvalidArgumentException If the output does not exists.
     */
    public function getOutput($name)
    {
        if (!$this->hasOutput($name)) {
            throw new \InvalidArgumentException(sprintf('The output "%s" does not exists.', $name));
        }

        return $this->outputs[$name];
    }

    /**
     * Returns the outputs.
     *
     * @return arary The outputs.
     */
    public function getOutputs()
    {
        return $this->outputs;
    }

    /**
     * Remove a output
     *
     * @param string $name The output name
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the output does not exists.
     */
    public function removeOutput($name)
    {
        if (!$this->hasOutput($name)) {
            throw new \InvalidArgumentException(sprintf('The output "%s" does not exists.', $name));
        }

        unset($this->outputs[$name]);
    }

    /**
     * Clear the outputs.
     *
     * @return void
     */
    public function clearOutputs()
    {
        $this->outputs = array();
    }

    /*
     * \ArrayAccess interface.
     */
    public function offsetExists($name)
    {
        return $this->hasOutput($name);
    }

    public function offsetSet($name, $output)
    {
        $this->setOutput($name, $output);
    }

    public function offsetGet($name)
    {
        return $this->getOutput($name);
    }

    public function offsetUnset($name)
    {
        $this->removeOutput($name);
    }

    /**
     * Returns the number of outputs (implements the \Countable interface).
     *
     * @return integer The number of outputs.
     */
    public function count()
    {
        return count($this->outputs);
    }

    /**
     * Returns an \ArrayIterator with the outputs (implements \IteratorAggregate interface).
     *
     * @return \ArrayIterator An \ArrayIterator with the outputs.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->outputs);
    }
}
