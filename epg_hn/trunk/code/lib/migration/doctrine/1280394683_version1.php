<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version1 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('admin', 'name', 'string', '30', array(
             ));
        $this->addColumn('admin', 'phone', 'string', '20', array(
             ));
        $this->addColumn('admin', 'status', 'boolean', '25', array(
             'notnull' => '1',
             'default' => '0',
             ));
        $this->addColumn('admin', 'last_login_ip', 'string', '20', array(
             'notnull' => '1',
             ));
        $this->addColumn('admin', 'last_login_at', 'timestamp', '25', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('admin', 'name');
        $this->removeColumn('admin', 'phone');
        $this->removeColumn('admin', 'status');
        $this->removeColumn('admin', 'last_login_ip');
        $this->removeColumn('admin', 'last_login_at');
    }
}