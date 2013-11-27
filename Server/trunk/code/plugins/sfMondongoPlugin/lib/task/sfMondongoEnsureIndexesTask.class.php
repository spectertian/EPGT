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

/**
 * Ensure the indexes.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoEnsureIndexesTask extends sfMondongoTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace = 'mondongo';
    $this->name = 'ensure-indexes';
    $this->briefDescription = 'Ensure the indexes';

    $this->detailedDescription = <<<EOF
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection('mondongo', 'ensuring indexes');

    foreach ($this->getRepositories() as $repository)
    {
      $repository->ensureIndexes();
    }
  }
}
