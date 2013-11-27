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

namespace Mondongo\Extension;

use Mondongo\Inflector;
use Mondongo\Mondator\Definition\Method;
use Mondongo\Mondator\Extension;

/**
 * The Mondongo DocumentFromToArray extension.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class DocumentFromToArray extends Extension
{
    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        $this->processFromArrayMethod();
        $this->processToArrayMethod();
    }

    /*
     * "fromArray" method.
     */
    public function processFromArrayMethod()
    {
        $code = '';

        // fields
        foreach ($this->configClass['fields'] as $name => $field) {
            $setter = 'set'.Inflector::camelize($name);
            $code .= <<<EOF
        if (isset(\$array['$name'])) {
            \$this->$setter(\$array['$name']);
        }

EOF;
        }

        // references
        foreach ($this->configClass['references'] as $name => $reference) {
            $setter = 'set'.Inflector::camelize($name);

            if ('one' == $reference['type']) {
                $code .= <<<EOF
        if (isset(\$array['$name'])) {
            \$this->$setter(\$array['$name']);
        }

EOF;
            } else {
                $code .= <<<EOF
        if (isset(\$array['$name'])) {
            \$reference = \$array['$name'];
            if (is_array(\$reference)) {
                \$reference = new \Mondongo\Group(\$reference);
            }
            \$this->$setter(\$reference);
        }

EOF;
            }
        }

        // embeddeds
        foreach ($this->configClass['embeddeds'] as $name => $embed) {
            $setter = 'set'.Inflector::camelize($name);
            $getter = 'get'.Inflector::camelize($name);

            if ('one' == $embed['type']) {
                $typeCode = <<<EOF
                \$embed->fromArray(\$array['$name']);
EOF;
            } else {
                $typeCode = <<<EOF
                foreach (\$array['$name'] as \$a) {
                    if (is_array(\$a)) {
                        \$e = new \\{$embed['class']}();
                        \$e->fromArray(\$a);
                    } else {
                        \$e = \$a;
                    }
                    \$embed->add(\$e);
                }
EOF;
            }

            $code .= <<<EOF
        if (isset(\$array['$name'])) {
            if (is_array(\$array['$name'])) {
                \$embed = \$this->$getter();
$typeCode
            } else {
                \$this->$setter(\$array['$name']);
            }
        }

EOF;
        }

        $method = new Method('public', 'fromArray', '$array', $code);
        $method->setDocComment(<<<EOF
    /**
     * Import data from an array.
     *
     * @param array \$array An array.
     *
     * @return void
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }

    /*
     * "toArray" method
     */
    protected function processToArrayMethod()
    {
        // fields
        $fieldsCode = '';
        foreach ($this->configClass['fields'] as $name => $field) {
            $fieldsCode .= <<<EOF
        if (null !== \$this->data['fields']['$name']) {
            \$array['$name'] = \$this->data['fields']['$name'];
        }

EOF;
        }

        // embeddeds
        $embeddedsCode = '';
        foreach ($this->configClass['embeddeds'] as $name => $embed) {
            if ('one' == $embed['type']) {
                $typeCode = <<<EOF
                \$array['$name'] = \$this->data['embeddeds']['$name']->toArray();
EOF;
            } else {
                $typeCode = <<<EOF
                foreach (\$this->data['embeddeds']['$name'] as \$embed) {
                    \$array['$name'][] = \$embed->toArray();
                }
EOF;
            }

            $embeddedsCode .= <<<EOF
            if (null !== \$this->data['embeddeds']['$name']) {
$typeCode
            }

EOF;
        }

        $method = new Method('public', 'toArray', '$withEmbeddeds = true', <<<EOF
        \$array = array();

$fieldsCode

        if (\$withEmbeddeds) {
$embeddedsCode
        }

        return \$array;
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Export the document data to array.
     *
     * @param bool \$withEmbeddeds If export embeddeds or not.
     *
     * @return array An array with the document data.
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }
}
