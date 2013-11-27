<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version10 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('user', 'username', 'string', '20', array(
             'notnull' => '1',
             ));
        $this->addColumn('user', 'email', 'string', '50', array(
             'notnull' => '1',
             ));
        $this->addColumn('user', 'password', 'string', '32', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('user', 'username');
        $this->removeColumn('user', 'email');
        $this->removeColumn('user', 'password');
    }
}