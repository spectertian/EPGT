<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version35 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('attachments', 'source_name', 'string', '500', array());
    }

    public function down()
    {
        $this->removeColumn('attachments', 'source_name');
    }
}