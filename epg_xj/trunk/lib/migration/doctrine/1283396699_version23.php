<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version23 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('wiki_tag', 'wiki_tag_wiki_id_wiki_id', array(
             'name' => 'wiki_tag_wiki_id_wiki_id',
             'local' => 'wiki_id',
             'foreign' => 'id',
             'foreignTable' => 'wiki',
             ));
        $this->createForeignKey('wiki_tag', 'wiki_tag_tag_id_tags_id', array(
             'name' => 'wiki_tag_tag_id_tags_id',
             'local' => 'tag_id',
             'foreign' => 'id',
             'foreignTable' => 'tags',
             ));
        $this->addIndex('wiki_tag', 'wiki_tag_wiki_id', array(
             'fields' => 
             array(
              0 => 'wiki_id',
             ),
             ));
        $this->addIndex('wiki_tag', 'wiki_tag_tag_id', array(
             'fields' => 
             array(
              0 => 'tag_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('wiki_tag', 'wiki_tag_wiki_id_wiki_id');
        $this->dropForeignKey('wiki_tag', 'wiki_tag_tag_id_tags_id');
        $this->removeIndex('wiki_tag', 'wiki_tag_wiki_id', array(
             'fields' => 
             array(
              0 => 'wiki_id',
             ),
             ));
        $this->removeIndex('wiki_tag', 'wiki_tag_tag_id', array(
             'fields' => 
             array(
              0 => 'tag_id',
             ),
             ));
    }
}