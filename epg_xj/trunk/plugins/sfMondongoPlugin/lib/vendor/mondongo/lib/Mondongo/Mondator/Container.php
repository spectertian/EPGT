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

use Mondongo\Mondator\Definition\Container as DefinitionContainer;
use Mondongo\Mondator\Output\Container as OutputContainer;

/**
 * The Mondator Container.
 *
 * The container store definitions and outputs.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Container
{
    protected $definitions;

    protected $outputs;

    /**
     * Constructor.
     *
     * @param Mondongo\Mondator\Definition\Container $definitions A definition container (optional, null by default).
     * @param Mondongo\Mondator\Output\Container     $outputs     A output container (optional, null by default).
     *
     * @return void
     */
    public function __construct(DefinitionContainer $definitions = null, OutputContainer $outputs = null)
    {
        $this->definitions = $definitions ? $definitions : new DefinitionContainer();
        $this->outputs     = $outputs     ? $outputs     : new OutputContainer();
    }

    /**
     * Set the definitions.
     *
     * @param Mondongo\Mondator\Definition\Container $definitions A definition container.
     *
     * @return void
     */
    public function setDefinitions(DefinitionContainer $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * Returns the definitions.
     *
     * @return \ArrayObject The definitions.
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Set the outputs.
     *
     * @param Mondongo\Mondator\Output\Container $outputs A output container.
     *
     * @return void
     */
    public function setOutputs(OutputContainer $outputs)
    {
        $this->outputs = $outputs;
    }

    /**
     * Returns the outputs.
     *
     * @return \ArrayObject The outptus.
     */
    public function getOutputs()
    {
        return $this->outputs;
    }
}
