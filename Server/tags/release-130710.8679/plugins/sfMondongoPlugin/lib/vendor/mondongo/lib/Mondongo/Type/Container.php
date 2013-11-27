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

namespace Mondongo\Type;

/**
 * Container of types.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Container
{
    static protected $map = array(
        'array'          => 'Mondongo\\Type\\ArrayType',
        'bin_data'       => 'Mondongo\\Type\\BinDataType',
        'boolean'        => 'Mondongo\\Type\\BooleanType',
        'date'           => 'Mondongo\\Type\\DateType',
        'float'          => 'Mondongo\\Type\\FloatType',
        'integer'        => 'Mondongo\\Type\\IntegerType',
        'raw'            => 'Mondongo\\Type\\RawType',
        'reference_one'  => 'Mondongo\\Type\\ReferenceOneType',
        'reference_many' => 'Mondongo\\Type\\ReferenceManyType',
        'string'         => 'Mondongo\\Type\\StringType',
    );

    static protected $types = array();

    /**
     * Returns if exists a type by name.
     *
     * @param string $name The type name.
     *
     * @return bool Returns if the type exists.
     */
    static public function hasType($name)
    {
        return isset(self::$map[$name]);
    }

    /**
     * Add a type.
     *
     * @param string $name  The type name.
     * @param string $class The type class.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the type already exists.
     * @throws \InvalidArgumentException If the class is not a subclass of \Mondongo\Type\Type.
     */
    static public function addType($name, $class)
    {
        if (self::hasType($name)) {
            throw new \InvalidArgumentException(sprintf('The type "%s" already exists.', $name));
        }

        $r = new \ReflectionClass($class);
        if (!$r->isSubclassOf('\\Mondongo\\Type\\Type')) {
            throw new \InvalidArgumentException(sprintf('The class "%s" is not a subclass of \\Mondongo\\Type\\Type.', $class));
        }

        self::$map[$name] = $class;
    }

    /**
     * Returns a type.
     *
     * @param string $name The type name.
     *
     * @return \Mondongo\Type\Type The type.
     *
     * @throws \InvalidArgumentException If the type does not exists.
     */
    static public function getType($name)
    {
        if (!isset(self::$types[$name])) {
            if (!self::hasType($name)) {
                throw new \InvalidArgumentException(sprintf('The type "%s" does not exists.', $name));
            }

            self::$types[$name] = new self::$map[$name];
        }

        return self::$types[$name];
    }

    /**
     * Remove a type.
     *
     * @param string $name The type name.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the type does not exists.
     */
    public function removeType($name)
    {
        if (!self::hasType($name)) {
            throw new \InvalidArgumentException(sprintf('The type "%s" does not exists.', $name));
        }

        unset(self::$map[$name], self::$types[$name]);
    }

    /**
     * Reset the types.
     *
     * @return void
     */
    static public function resetTypes()
    {
        self::$map = array(
            'array'          => 'Mondongo\\Type\\ArrayType',
            'bin_data'       => 'Mondongo\\Type\\BinDataType',
            'boolean'        => 'Mondongo\\Type\\BooleanType',
            'date'           => 'Mondongo\\Type\\DateType',
            'float'          => 'Mondongo\\Type\\FloatType',
            'integer'        => 'Mondongo\\Type\\IntegerType',
            'raw'            => 'Mondongo\\Type\\RawType',
            'reference_one'  => 'Mondongo\\Type\\ReferenceOneType',
            'reference_many' => 'Mondongo\\Type\\ReferenceManyType',
            'string'         => 'Mondongo\\Type\\StringType',
        );

        self::$types = array();
    }
}
