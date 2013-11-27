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

/**
 * Translation.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Translation extends Extension
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->addRequiredOption('fields');
    }

    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        $this->processTranslationClass();
        $this->processTranslationEmbedded();
        $this->processTranslationMethod();
    }

    protected function processTranslationClass()
    {
        $translationConfigClass = array(
            'is_embedded' => true,
            'fields' => array(
                'locale' => 'string',
            ),
        );

        $configClassFields = $this->configClass['fields'];
        foreach ($this->getOption('fields') as $field) {
            if (!isset($configClassFields[$field])) {
                throw new \RuntimeException(sprintf('The field "%s" of the class "%s" does not exists.', $field, $this->class));
            }
            $translationConfigClass['fields'][$field] = $configClassFields[$field];

            unset($configClassFields[$field]);
        }
        $this->configClass['fields'] = $configClassFields;

        $this->newConfigClasses[$this->class.'Translation'] = $translationConfigClass;
    }

    protected function processTranslationEmbedded()
    {
        $this->configClass['embeddeds']['translations'] = array(
            'class' => $this->definitions['document']->getClass().'Translation',
            'type'  => 'many',
        );
    }

    protected function processTranslationMethod()
    {
        $method = new Method('public', 'translation', '$locale', <<<EOF
        foreach (\$this->getTranslations() as \$translation) {
            if (\$translation->getLocale() == \$locale) {
                return \$translation;
            }
        }

        \$translation = new \\{$this->class}Translation();
        \$translation->setLocale(\$locale);

        \$this->getTranslations()->add(\$translation);

        return \$translation;
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Returns a translation document embedded by locale.
     *
     * @param string \$locale The locale.
     *
     * @return {$this->class}Translation The translation document embedded.
     */
EOF
        );
        $this->definitions['document_base']->addMethod($method);
    }
}
