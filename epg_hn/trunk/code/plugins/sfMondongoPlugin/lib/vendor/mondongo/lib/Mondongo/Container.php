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
 * Container of Mondongo's.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Container
{
    static protected $default;

    static protected $mondongos = array();

    /**
     * Set the default Mondongo.
     *
     * @param Mondongo\Mondongo $mondongo A Mondongo.
     *
     * @return void
     */
    static public function setDefault(Mondongo $mondongo)
    {
        self::$default = $mondongo;
    }

    /**
     * Returns if exists the default Mondongo.
     *
     * @return boolean Returns if exists the default Mondongo.
     */
    static public function hasDefault()
    {
        return null !== self::$default;
    }

    /**
     * Returns the default Mondongo.
     *
     * @return Mondongo\Mondongo The default Mondongo.
     *
     * @throws \RuntimeException If the default Mondongo does not exists.
     */
    static public function getDefault()
    {
        if (!self::hasDefault()) {
            throw new \RuntimeException('The default Mondongo does not exists.');
        }

        return self::$default;
    }

    /**
     * Clear the default Mondongo.
     *
     * @return void
     */
    static public function clearDefault()
    {
        self::$default = null;
    }

    /**
     * Set the Mondongo for a document class.
     *
     * @param string            $documentClass The document class.
     * @param Mondongo\Mondongo $mondongo      The Mondongo.
     *
     * @return void
     */
    static public function setForDocumentClass($documentClass, Mondongo $mondongo)
    {
        self::$mondongos[$documentClass] = $mondongo;
    }

    /**
     * Returns if exists a Mondongo for a document class.
     *
     * @param string $documentClass The document class.
     *
     * @return boolean Returns if exists the Mondongo.
     */
    static public function hasForDocumentClass($documentClass)
    {
        return isset(self::$mondongos[$documentClass]);
    }

    /**
     * Return the Mondongo for a document class.
     *
     * If not exists the Mondongo for the document class returns the default Mondongo.
     *
     * @param string $documentClass The document class.
     *
     * @return Mondongo\Mondongo The Mondongo.
     *
     * @throws \RuntimeException If does not exists the Mondongo for the document class and the default Mondongo.
     */
    static public function getForDocumentClass($documentClass)
    {
        if (!isset(self::$mondongos[$documentClass])) {
            if (!self::hasDefault()) {
                throw new \RuntimeException(sprintf('The Mondongo for document class "%s" does not exists.', $documentClass));
            }

            return self::$default;
        }

        return self::$mondongos[$documentClass];
    }

    /**
     * Remove the Mondongo for a document class.
     *
     * @param string $documentClass The document class.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If does not exists the Mondongo for the document class.
     */
    static public function removeForDocumentClass($documentClass)
    {
        if (!self::hasForDocumentClass($documentClass)) {
            throw new \InvalidArgumentException(sprintf('The Mondongo for document class "%s" does not exists.', $documentClass));
        }

        unset(self::$mondongos[$documentClass]);
    }

    /**
     * Clear the Mondongos for the document classes.
     *
     * @return void
     */
    static public function clearForDocumentClasses()
    {
        self::$mondongos = array();
    }
}
