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
 * sfMondongoPager.
 *
 * Based in sf|Propel/Doctrine|Pager.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoPager extends sfPager
{
  protected $findOptions = array();

  /**
   * @see sfPager
   */
  public function init()
  {
    $this->resetIterator();

    $count = $this->getRepository()->count(isset($this->findOptions['query']) ? $this->findOptions['query'] : array());
    $this->setNbResults($count);

    if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults())
    {
      $this->setLastPage(0);
    }
    else
    {
      $offset = ($this->getPage() - 1) * $this->getMaxPerPage();

      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

      $this->findOptions['skip']  = $offset;
      $this->findOptions['limit'] = $this->getMaxPerPage();
    }
  }

  /**
   * @see sfPager
   */
  public function getResults()
  {
    return (array) $this->getRepository()->find($this->findOptions);
  }

  /**
   * @see sfPager
   */
  public function retrieveObject($offset)
  {
    return $this->getRepository()->findOne(array_merge($this->findOptions, array('skip' => $offset - 1)));
  }

  /**
   * Set the find options.
   *
   * @param array $findOptions The find options.
   *
   * @return void
   */
  public function setFindOptions(array $findOptions)
  {
    $this->findOptions = $findOptions;
  }

  /**
   * Returns the find options.
   *
   * @return array The find options
   */
  public function getFindOptions()
  {
    return $this->findOptions;
  }

  /**
   * Returns the repository of the pager class.
   *
   * @return Mondongo\Repository The repository of the pager class.
   */
  protected function getRepository()
  {
    return sfContext::getInstance()->get('mondongo')->getRepository($this->getClass());
  }
}
