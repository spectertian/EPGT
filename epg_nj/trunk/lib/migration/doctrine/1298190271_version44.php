<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version44 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('channel', 'live', 'enum', '1', array(
             'values' => 
             array(
              0 => '0',
              1 => '1',
             ),
             'default' => '0',
             ));
        $this->addColumn('channel', 'live_config', 'string', '4000', array(
             'notnull' => '',
             ));
    }

    public function down()
    {
        $this->removeColumn('channel', 'live');
        $this->removeColumn('channel', 'live_config');
    }
}