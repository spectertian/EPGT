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
use Mondongo\Mondator\Definition\Property;
use Mondongo\Mondator\Extension;

/**
 * Add the data CamelCase map to documents.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class DocumentDataCamelCaseMap extends Extension
{
    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        $this->processDocumentDataCamelCaseMapProperty();
        $this->processDocumentGetDataCamelCaseMapMethod();
    }

    /*
     * Document "dataCamelCaseMap" property.
     */
    protected function processDocumentDataCamelCaseMapProperty()
    {
        $dataCamelCaseMap = array();

        // fields
        foreach ($this->configClass['fields'] as $name => $field) {
            $dataCamelCaseMap[$name] = Inflector::camelize($name);
        }

        // references
        foreach ($this->configClass['references'] as $name => $reference) {
            $dataCamelCaseMap[$name] = Inflector::camelize($name);
        }

        // embeddeds
        foreach ($this->configClass['embeddeds'] as $name => $embed) {
            $dataCamelCaseMap[$name] = Inflector::camelize($name);
        }

        // relations
        if (!$this->configClass['is_embedded']) {
            foreach ($this->configClass['relations'] as $name => $relation) {
                $dataCamelCaseMap[$name] = Inflector::camelize($name);
            }
        }

        $property = new Property('protected', 'dataCamelCaseMap', $dataCamelCaseMap);
        $property->setIsStatic(true);

        $this->definitions['document_base']->addProperty($property);
    }

    /*
     * Document "getDataCamelCaseMap" method.
     */
    public function processDocumentGetDataCamelCaseMapMethod()
    {
        $method = new Method('public', 'getDataCamelCaseMap', '', <<<EOF
        return self::\$dataCamelCaseMap;
EOF
        );
        $method->setIsStatic(true);
        $method->setDocComment(<<<EOF
    /**
     * Returns the data CamelCase map.
     *
     * @return array The data CamelCase map.
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }
}
