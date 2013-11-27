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

namespace Mondongo\Mondator;

use Mondongo\Mondator\Definition\Definition;

/**
 * The Mondator Dumper.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Dumper
{
    protected $definition;

    /**
     * Constructor.
     *
     * @param Mondongo\Mondator\Definition\Definition $definition The definition.
     *
     * @return void
     */
    public function __construct(Definition $definition)
    {
        $this->setDefinition($definition);
    }

    /**
     * Set the definition.
     *
     * @param Mondongo\Mondator\Definition\Definition $definition The definition.
     *
     * @return void
     */
    public function setDefinition(Definition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * Returns the definition
     *
     * @return Mondongo\Mondator\Definition\Definition The definition.
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Dump the definition.
     *
     * @return string The PHP code of the definition.
     */
    public function dump()
    {
        return
            $this->startFile().
            $this->addNamespace().
            $this->startClass().
            $this->addProperties().
            $this->addMethods().
            $this->endClass()
        ;
    }

    /**
     * Export an array.
     *
     * Based on Symfony\Component\DependencyInjection\Dumper\PhpDumper::exportParameters
     * http://github.com/symfony/symfony
     *
     * @param array $array  The array.
     * @param int   $indent The indent.
     *
     * @return string The array exported.
     */
    static public function exportArray(array $array, $indent)
    {
        $code = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = self::exportArray($value, $indent + 4);
            } else {
                $value = null === $value ? 'null' : var_export($value, true);
            }

            $code[] = sprintf('%s%s => %s,', str_repeat(' ', $indent), var_export($key, true), $value);
        }

        return sprintf("array(\n%s\n%s)", implode("\n", $code), str_repeat(' ', $indent - 4));
    }

    protected function startFile()
    {
        return <<<EOF
<?php
EOF;
    }

    protected function addNamespace()
    {
        if (!$namespace = $this->definition->getNamespace()) {
            return '';
        }

        return <<<EOF


namespace $namespace;
EOF;
    }

    protected function startClass()
    {
        // doc comment
        $docComment = $this->definition->getDocComment();

        /*
         * declaration
         */
        $declaration = '';

        // abstract
        if ($this->definition->getIsAbstract()) {
            $declaration .= 'abstract ';
        }

        // class
        $declaration .= 'class '.$this->definition->getClassName();

        // parent class
        if ($parentClass = $this->definition->getParentClass()) {
            $declaration .= ' extends '.$parentClass;
        }

        // interfaces
        if ($interfaces = $this->definition->getInterfaces()) {
            $declaration .= ' implements '.implode(', ', $interfaces);
        }

        return <<<EOF


$docComment
$declaration
{
EOF;
    }

    protected function addProperties()
    {
        $code = '';

        foreach ($this->definition->getProperties() as $property) {
            $docComment = $property->getDocComment();
            $isStatic   = $property->getIsStatic() ? 'static ' : '';
            $value      = is_array($property->getValue()) ? self::exportArray($property->getValue(), 8) : var_export($property->getValue(), true);

            $code .= <<<EOF


$docComment
    $isStatic{$property->getVisibility()} \${$property->getName()} = $value;
EOF;
        }

        return $code;
    }

    protected function addMethods()
    {
        $code = '';

        foreach ($this->definition->getMethods() as $method) {
            // doc comment
            $docComment = $method->getDocComment();

            // isFinal
            $isFinal = $method->getIsFinal() ? 'final ' : '';

            // isStatic
            $isStatic = $method->getIsStatic() ? 'static ' : '';

            // abstract
            if ($method->getIsAbstract()) {
                $code .= <<<EOF


$docComment
    abstract $isStatic{$method->getVisibility()} function {$method->getName()}({$method->getArguments()});
EOF;
            } else {
                $code .= <<<EOF


$docComment
    $isFinal$isStatic{$method->getVisibility()} function {$method->getName()}({$method->getArguments()})
    {
{$method->getCode()}
    }
EOF;
        }
            }

        return $code;
    }

    protected function endClass()
    {
        return <<<EOF

}
EOF;
    }
}
