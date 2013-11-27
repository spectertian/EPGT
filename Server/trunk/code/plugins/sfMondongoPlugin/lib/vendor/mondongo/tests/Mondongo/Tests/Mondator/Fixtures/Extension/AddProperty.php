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

namespace Mondongo\Tests\Mondator\Fixtures\Extension;

use Mondongo\Mondator\Definition\Definition;
use Mondongo\Mondator\Definition\Property;
use Mondongo\Mondator\Extension;

class AddProperty extends Extension
{
    protected $options = array(
        'definition' => null,
        'visibility' => null,
        'name'       => null,
        'value'      => null,
    );

    protected function setUp()
    {
        $this->addRequiredOptions(array(
            'definition',
            'visibility',
            'name',
            'value',
        ));
    }

    protected function doProcess()
    {
        $property = new Property($this->getOption('visibility'), $this->getOption('name'), $this->getOption('value'));

        $this->definitions[$this->getOption('definition')]->addProperty($property);
    }
}
