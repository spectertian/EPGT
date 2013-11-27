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
 * IdentifierAutoIncrement.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class IdentifierAutoIncrement extends Extension
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->addOptions(array(
            'field' => 'identifier',
        ));
    }

    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        $field = $this->getOption('field');

        // field
        $this->configClass['fields'][$field] = 'integer';

        // index
        $this->configClass['indexes'][] = array(
            'keys'    => array($field => 1),
            'options' => array('unique' => 1),
        );

        // event
        $setter = 'set'.Inflector::camelize($field);

        $method = new Method('protected', 'updateIdentifierAutoIncrement', '', <<<EOF
        \$last = \$this->getRepository()
            ->getCollection()
            ->find(array(), array('$field' => 1))
            ->sort(array('$field' => -1))
            ->limit(1)
            ->getNext()
        ;

        \$identifier = null !== \$last ? \$last['$field'] + 1 : 1;

        \$this->$setter(\$identifier);
EOF
        );
        $this->definitions['document_base']->addMethod($method);

        $this->configClass['extensions_events']['preInsert'][] = $method->getName();
    }
}
