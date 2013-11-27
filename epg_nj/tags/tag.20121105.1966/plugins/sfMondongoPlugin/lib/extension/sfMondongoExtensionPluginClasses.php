<?php

/*
 * Copyright 2010 Pablo Díez Pascual <pablodip@gmail.com>
 *
 * This file is part of sfMondongoPlugin.
 *
 * sfMondongoPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sfMondongoPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with sfMondongoPlugin. If not, see <http://www.gnu.org/licenses/>.
 */

use Mondongo\Mondator\Definition\Definition;
use Mondongo\Mondator\Extension;
use Mondongo\Mondator\Output\Output;

/**
 * sfMondongoExtensionPluginClasses.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoExtensionPluginClasses extends Extension
{
  protected function doProcess()
  {
    if (isset($this->configClass['plugin_name']) && isset($this->configClass['plugin_dir']))
    {
      /*
       * Document.
       */
      // definitions
      $this->definitions['document']->setParentClass('Plugin'.$this->class);

      $this->definitions['document_plugin'] = $definition = new Definition('Plugin'.$this->class);
      $definition->setParentClass('Base'.$this->class);
      $definition->setIsAbstract(true);
      $definition->setDocComment(<<<EOF
/**
 * {$this->class} Plugin Document.
 */
EOF
      );

      // outputs
      $this->outputs['document']->setDir($this->outputs['document']->getDir().'/'.$this->configClass['plugin_name']);

      $this->outputs['document_base']->setDir($this->outputs['document']->getDir().'/Base');

      $this->outputs['document_plugin'] = new Output($this->configClass['plugin_dir'].'/lib/model/mondongo');

      /*
       * Repository.
       */
      // definitions
      $this->definitions['repository']->setParentClass('Plugin'.$this->class.'Repository');

      $this->definitions['repository_plugin'] = $definition = new Definition('Plugin'.$this->class.'Repository');
      $definition->setParentClass('Base'.$this->class.'Repository');
      $definition->setIsAbstract(true);
      $definition->setDocComment(<<<EOF
/**
 * {$this->class} Plugin Repository.
 */
EOF
      );

      // outputs
      $this->outputs['repository']->setDir($this->outputs['repository']->getDir().'/'.$this->configClass['plugin_name']);

      $this->outputs['repository_base']->setDir($this->outputs['repository']->getDir().'/Base');

      $this->outputs['repository_plugin'] = new Output($this->configClass['plugin_dir'].'/lib/model/mondongo');
    }
  }
}
