<?php

/**
 * MigrationVersionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MigrationVersionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object MigrationVersionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('MigrationVersion');
    }
}