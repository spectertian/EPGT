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
 * ArrayType.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class ArrayType extends Type
{
    /**
     * @inheritdoc
     */
    public function toMongo($value)
    {
        return serialize($value);
    }

    /**
     * @inheritdoc
     */
    public function toPHP($value)
    {
        return unserialize($value);
    }

    /**
     * @inheritdoc
     */
    public function toMongoInString()
    {
        return '%to% = serialize(%from%);';
    }

    /**
     * @inheritdoc
     */
    public function toPHPInString()
    {
        return '%to% = unserialize(%from%);';
    }
}