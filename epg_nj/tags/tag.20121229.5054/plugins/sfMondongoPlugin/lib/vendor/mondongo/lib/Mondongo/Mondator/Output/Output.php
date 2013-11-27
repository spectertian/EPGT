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

namespace Mondongo\Mondator\Output;

/**
 * Represents a output for a definition type.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Output
{
    protected $dir;

    protected $override;

    /**
     * Constructor.
     *
     * @param string $dir      The dir.
     * @param bool   $override The override. It indicate if override files (optional, false by).
     *
     * @return void
     */
    public function __construct($dir, $override = false)
    {
        $this->setDir($dir);
        $this->setOverride($override);
    }

    /**
     * Set the dir.
     *
     * @param $string $dir The dir.
     *
     * @return void
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Returns the dir.
     *
     * @return string The dir.
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Set the override. It indicate if override files.
     *
     * @param bool $override The override.
     *
     * @return void
     */
    public function setOverride($override)
    {
        $this->override = (bool) $override;
    }

    /**
     * Returns the override.
     *
     * @return bool The override.
     */
    public function getOverride()
    {
        return $this->override;
    }
}
