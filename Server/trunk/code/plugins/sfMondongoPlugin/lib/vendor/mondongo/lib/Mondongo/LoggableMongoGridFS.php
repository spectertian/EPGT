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

namespace Mondongo;

/**
 * A loggable MongoGridFS.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class LoggableMongoGridFS extends \MongoGridFS
{
    protected $mongo;

    protected $loggerCallable;

    protected $connectionName;

    /**
     * Constructor.
     *
     * @param \Mongo   $mongo          The mongo connection object.
     * @param \MongoDB $db             The mongo database object.
     * @param string   $collectionName The collection name.
     *
     * @return void
     */
    public function __construct(\Mongo $mongo, \MongoDB $db, $collectionName)
    {
        $this->mongo = $mongo;

        parent::__construct($db, $collectionName);
    }

    /**
     * Returns the mongo connection object.
     *
     * @return \Mongo The mongo connection object.
     */
    public function getMongo()
    {
        return $this->mongo;
    }

    /**
     * Set the logger callable.
     *
     * @param mixed $loggerCallable A PHP callable.
     *
     * @return void
     */
    public function setLoggerCallable($loggerCallable)
    {
        $this->loggerCallable = $loggerCallable;
    }

    /**
     * Returns the logger callable.
     *
     * @return mixed The logger callable.
     */
    public function getLoggerCallable()
    {
        return $this->loggerCallable;
    }

    /**
     * Set the connection name (for log).
     *
     * @param string $connectionName The connection name.
     *
     * @return void
     */
    public function setConnectionName($connectionName)
    {
        $this->connectionName = $connectionName;
    }

    /**
     * Returns the connection name.
     *
     * @return string The connection name.
     */
    public function getConnectionName()
    {
        return $this->connectionName;
    }

    /*
     * log.
     */
    protected function log(array $log)
    {
        if ($this->loggerCallable) {
            call_user_func($this->loggerCallable, array_merge(array(
                'connection' => $this->connectionName,
                'database'   => $this->db->__toString(),
                'collection' => $this->getName(),
                'gridfs'     => 1,
            ), $log));
        }
    }

    /*
     * storeBytes.
     */
    public function storeBytes($bytes, array $extra, array $options = array())
    {
        $this->log(array(
            'storeBytes' => 1,
            'bytes_sha1' => sha1($bytes),
            'extra'      => $extra,
            'options'    => $options,
        ));

        return parent::storeBytes($bytes, $extra, $options);
    }

    /*
     * storeFile.
     */
    public function storeFile($filename, array $extra, array $options = array())
    {
        $this->log(array(
            'storeFile' => 1,
            'filename'  => $filename,
            'extra'     => $extra,
            'options'   => $options,
        ));

        return parent::storeFile($filename, $extra, $options);
    }

    /*
     * count.
     */
    public function count(array $query = array(), $limit = 0, $skip = 0)
    {
        $this->log(array(
            'count' => 1,
            'query' => $query,
            'limit' => $limit,
            'skip'  => $skip,
        ));

        return parent::count($query, $limit, $skip);
    }

    /*
     * find.
     */
    public function find(array $query = array(), array $fields = array())
    {
        $this->log(array(
            'find'   => 1,
            'query'  => $query,
            'fields' => $fields,
        ));

        $cursor = new LoggableMongoGridFSCursor($this, $this->mongo, $this->db->__toString().'.'.$this->getName(), $query, $fields);
        $cursor->setLoggerCallable($this->loggerCallable);
        $cursor->setConnectionName($this->connectionName);

        return $cursor;
    }

    /*
     * findOne.
     */
    public function findOne(array $query = array(), array $fields = array())
    {
        $this->log(array(
            'findOne' => 1,
            'query'   => $query,
            'fields'  => $fields,
        ));

        return parent::findOne($query, $fields);
    }

    /*
     * insert.
     */
    public function insert(array $a, array $options = array())
    {
        $this->log(array(
            'insert'  => 1,
            'a'       => $a,
            'options' => $options,
        ));

        return parent::insert($a, $options);
    }

    /*
     * remove.
     */
    public function remove(array $criteria = array(), array $options = array())
    {
        $this->log(array(
            'remove'   => 1,
            'criteria' => $criteria,
            'options'  => $options,
        ));

        return parent::remove($criteria, $options);
    }
}
