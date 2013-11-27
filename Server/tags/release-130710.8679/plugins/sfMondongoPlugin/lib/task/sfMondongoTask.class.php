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

use Mondongo\Container;
use Mondongo\Mondongo;

/**
 * Base task for Mondongo tasks.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
abstract class sfMondongoTask extends sfBaseTask
{
  protected $mondongo;

  protected $repositories;

  /**
   * Returns the Mondongo.
   *
   * @return Mondongo The Mondongo.
   */
  protected function getMondongo()
  {
    if (null === $this->mondongo)
    {
      $this->mondongo = new Mondongo();

      $databaseManager = new sfDatabaseManager($this->configuration);
      foreach ($databaseManager->getNames() as $name)
      {
        $database = $databaseManager->getDatabase($name);
        if ($database instanceof sfMondongoDatabase)
        {
          $this->mondongo->setConnection($name, $database->getMondongoConnection());
        }
      }

      Container::setDefault($this->mondongo);
    }

    return $this->mondongo;
  }

  /**
   * Returns the repositories of the project.
   *
   * @return array The repositories.
   */
  protected function getRepositories()
  {
    $mondongo = $this->getMondongo();

    if (null === $this->repositories)
    {
      $this->repositories = array();
      foreach (sfFinder::type('file')->name('*Repository.php')->prune('Base')->in(sfConfig::get('sf_lib_dir').'/model/mondongo') as $file)
      {
        $this->repositories[] = $mondongo->getRepository(str_replace('Repository.php', '', basename($file)));
      }
    }

    return $this->repositories;
  }
}
