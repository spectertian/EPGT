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

namespace Mondongo\Extension\Extra;

use Mondongo\Mondator\Definition\Method;
use Mondongo\Mondator\Extension;
use Mondongo\Inflector;

/**
 * Timestampable.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Timestampable extends Extension
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->addOptions(array(
            'created_enabled' => true,
            'created_field'   => 'created_at',
            'updated_enabled' => true,
            'updated_field'   => 'updated_at',
        ));
    }

    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        /*
         * Created.
         */
        if ($this->getOption('created_enabled')) {
            // field
            $field = $this->getOption('created_field');
            $this->configClass['fields'][$field] = 'date';

            // event
            $fieldSetter = 'set'.Inflector::camelize($field);

            $method = new Method('protected', 'updateTimestampableCreated', '', <<<EOF
        \$this->$fieldSetter(new \DateTime());
EOF
            );
            $this->definitions['document_base']->addMethod($method);

            $this->configClass['extensions_events']['preInsert'][] = $method->getName();
        }

        /*
         * Updated.
         */
        if ($this->getOption('updated_enabled')) {
            // field
            $field = $this->getOption('updated_field');
            $this->configClass['fields'][$field] = 'date';

            // event
            $fieldSetter = 'set'.Inflector::camelize($field);

            $method = new Method('protected', 'updateTimestampableUpdated', '', <<<EOF
        \$this->$fieldSetter(new \DateTime());
EOF
            );
            $this->definitions['document_base']->addMethod($method);

            $this->configClass['extensions_events']['preUpdate'][] = $method->getName();
        }
    }
}
