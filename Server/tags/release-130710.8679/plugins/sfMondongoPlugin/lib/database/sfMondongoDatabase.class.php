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
 * sfMondongoDatabase
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoDatabase extends sfDatabase
{
  protected $mondongoConnection;

  /**
   * @see sfDatabase
   */
  public function initialize($parameters = array())
  {
    parent::initialize($parameters);

    // server
    if (!$this->hasParameter('server'))
    {
      throw new RuntimeException(sprintf('Connection "%s" without server".', $this->getParameter('name')));
    }
    $server = $this->getParameter('server');

    // database
    if (!$this->hasParameter('database'))
    {
      throw new RuntimeException(sprintf('Connection "%s" without database.', $this->getParameter('name')));
    }
    $database = $this->getParameter('database');

    // options
    $options = array();
    if ($this->hasParameter('persist'))
    {
      $options['persist'] = $this->getParameter('persist');
    }
    if ($this->hasParameter('connect'))
    {
      $options['connect'] = $this->getParameter('connect');
    }
    if ($this->hasParameter('replicaSet'))
    {
      $options['replicaSet'] = $this->getParameter('replicaSet');
    }

    $this->mondongoConnection = new Mondongo\Connection($server, $database, $options);
    
    if ($this->hasParameter('slaveOk'))
    {
      //$this->mondongoConnection->getMongo()->setSlaveOkay((bool)$this->getParameter('slaveOk'));
      $this->mondongoConnection->getMongo()->setReadPreference(Mongo::RP_SECONDARY_PREFERRED);
    }
  }

  /**
   * Returns the mondongo connection.
   *
   * @return MondongoConnection The mondongo connection.
   */
  public function getMondongoConnection()
  {
    return $this->mondongoConnection;
  }

  /**
   * @see sfDatabase
   */
  public function connect()
  {
  }

  /**
   * @see sfDatabase
   */
  public function shutdown()
  {
  }
}
