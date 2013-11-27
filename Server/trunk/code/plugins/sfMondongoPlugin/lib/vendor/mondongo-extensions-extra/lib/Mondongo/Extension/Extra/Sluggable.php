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
 * Sluggable.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Sluggable extends Extension
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->addRequiredOption('from_field');

        $this->addOptions(array(
            'slug_field' => 'slug',
            'unique'     => true,
            'update'     => false,
            'builder'    => array('\Mondongo\Extension\Extra\Sluggable', 'slugify'),
        ));
    }

    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        // field
        $slugField = $this->getOption('slug_field');
        $this->configClass['fields'][$slugField] = 'string';

        // index
        if ($this->getOption('unique')) {
            $this->configClass['indexes'][] = array(
                'keys'    => array($slugField => 1),
                'options' => array('unique' => 1),
            );
        }

        // update slug
        $fromField = $this->getOption('from_field');
        $fromFieldCamelized = Inflector::camelize($fromField);
        $slugFieldCamelized = Inflector::camelize($slugField);
        $builder = var_export($this->getOption('builder'), true);

        $uniqueCode = '';
        if ($this->getOption('unique')) {
            $uniqueCode = <<<EOF
        \$similarSlugs = array();
        foreach (\$this->getRepository()
            ->getCollection()
            ->find(array('$slugField' => new \MongoRegex('/^'.\$slug.'/')))
        as \$result) {
            \$similarSlugs[] = \$result['$slugField'];
        }

        \$i = 1;
        while (in_array(\$slug, \$similarSlugs)) {
            \$slug = \$proposal.'-'.++\$i;
        }
EOF;
        }

        $method = new Method('protected', 'updateSluggableSlug', '', <<<EOF
        \$slug = \$proposal = call_user_func($builder, \$this->get$fromFieldCamelized());

$uniqueCode

        \$this->set$slugFieldCamelized(\$slug);
EOF
        );
        $this->definitions['document_base']->addMethod($method);

        // event
        $this->configClass['extensions_events']['preInsert'][] = $method->getName();
        if ($this->getOption('update')) {
            $this->configClass['extensions_events']['preUpdate'][] = $method->getName();
        }
    }

    /**
     * Slugify a text.
     *
     * @param string $text The text.
     *
     * @return string The text slugified.
     */
    static public function slugify($text)
    {
        // replace all non letters or digits by -
        $text = preg_replace('/\W+/', '-', $text);

        // trim and lowercase
        $text = strtolower(trim($text, '-'));

        return $text;
    }
}
