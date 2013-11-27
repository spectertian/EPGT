<?php

/**
 * BaseAttachmentsPre
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $file_name
 * @property integer $verify
 * @property integer $isdel
 * 
 * @method string         getFileName()  Returns the current record's "file_name" value
 * @method integer        getVerify()    Returns the current record's "verify" value
 * @method integer        getIsdel()     Returns the current record's "isdel" value
 * @method AttachmentsPre setFileName()  Sets the current record's "file_name" value
 * @method AttachmentsPre setVerify()    Sets the current record's "verify" value
 * @method AttachmentsPre setIsdel()     Sets the current record's "isdel" value
 * 
 * @package    epg2.0
 * @subpackage model
 * @author     Huan Tek
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAttachmentsPre extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('attachments_pre');
        $this->hasColumn('file_name', 'string', 250, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 250,
             ));
        $this->hasColumn('verify', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('isdel', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));

        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}