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

use Mondongo\Mondator\Definition\Method;
use Mondongo\Mondator\Definition\Property;
use Mondongo\Mondator\Extension;

/**
 * Add the document data map to documents.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class DocumentDataMap extends Extension
{
    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        $dataMap = array();

        // fields
        $dataMap['fields'] = $this->configClass['fields'];

        // references
        $dataMap['references'] = $this->configClass['references'];

        // embeddeds
        $dataMap['embeddeds'] = $this->configClass['embeddeds'];

        // relations
        if (!$this->configClass['is_embedded']) {
            $dataMap['relations'] = $this->configClass['relations'];
        }

        $dataMap = \Mondongo\Mondator\Dumper::exportArray($dataMap, 12);

        $method = new Method('public', 'getDataMap', '', <<<EOF
        return $dataMap;
EOF
        );
        $method->setIsStatic(true);
        $method->setDocComment(<<<EOF
    /**
     * Returns the data map.
     *
     * @return array The data map.
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }
}
