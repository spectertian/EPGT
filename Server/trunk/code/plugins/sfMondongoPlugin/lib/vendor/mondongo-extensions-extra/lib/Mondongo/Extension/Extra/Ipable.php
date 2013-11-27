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
 * Ipable.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Ipable extends Extension
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->addOptions(array(
            'created_enabled' => true,
            'created_field'   => 'created_from',
            'updated_enabled' => true,
            'updated_field'   => 'updated_from',
            'get_ip_callable' => array('\Mondongo\Extension\Extra\Ipable', 'getIp'),
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
            $this->configClass['fields'][$field] = 'string';

            // event
            $fieldSetter   = 'set'.Inflector::camelize($field);
            $getIpCallable = var_export($this->getOption('get_ip_callable'), true);

            $method = new Method('protected', 'updateIpableCreated', '', <<<EOF
        \$this->$fieldSetter(call_user_func($getIpCallable));
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
            $this->configClass['fields'][$field] = 'string';

            // event
            $fieldSetter   = 'set'.Inflector::camelize($field);
            $getIpCallable = var_export($this->getOption('get_ip_callable'), true);

            $method = new Method('protected', 'updateIpableUpdated', '', <<<EOF
        \$this->$fieldSetter(call_user_func($getIpCallable));
EOF
            );
            $this->definitions['document_base']->addMethod($method);

            $this->configClass['extensions_events']['preUpdate'][] = $method->getName();
        }
    }

    /**
     * Returns the IP from $_SERVER['REMOTE_ADDR'] if exists, or 127.0.0.1 if it does not exists.
     *
     * @return string The IP.
     */
    static public function getIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }
}
