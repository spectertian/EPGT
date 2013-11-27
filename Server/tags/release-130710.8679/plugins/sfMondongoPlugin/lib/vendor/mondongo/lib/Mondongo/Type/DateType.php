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
 * DateType.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class DateType extends Type
{
    /**
     * @inheritdoc
     */
    public function toMongo($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        } elseif (is_string($value)) {
            $value = strtotime($value);
        }

        return new \MongoDate($value);
    }

    /**
     * @inheritdoc
     */
    public function toPHP($value)
    {
        $date = new \DateTime();
        $date->setTimestamp($value->sec);

        return $date;
    }

    /**
     * @inheritdoc
     */
    public function toMongoInString()
    {
        return 'if (%from% instanceof \DateTime) { %from% = %from%->getTimestamp(); } elseif (is_string(%from%)) { %from% = strtotime(%from%); } %to% = new \MongoDate(%from%);';
    }

    /**
     * @inheritdoc
     */
    public function toPHPInString()
    {
        return '$date = new \DateTime(); $date->setTimestamp(%from%->sec); %to% = $date;';
    }
}
