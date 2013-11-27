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
use Mondongo\Mondator\Extension;

class NewConfigClass extends Extension
{
    protected function setUp()
    {
        $this->addRequiredOptions(array(
            'suffix',
            'name',
        ));

        $this->addOptions(array(
            'multiple'        => false,
            'multiple_suffix' => null,
            'multiple_name'   => null,
        ));
    }

    protected function doProcess()
    {
        $newClassName = $this->class.$this->getOption('suffix');

        $configClass = array(
            'name' => $this->getOption('name'),
        );

        if ($this->getOption('multiple')) {
            $configClass['extensions'][] = array(
                'class'   => 'Mondongo\Tests\Mondator\Fixtures\Extension\NewConfigClass',
                'options' => array(
                    'suffix' => $this->getOption('multiple_suffix'),
                    'name'   => $this->getOption('multiple_name'),
                ),
            );
        }

        $this->newConfigClasses[$newClassName] = $configClass;
    }
}
