<?php


class TagsTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Tags');
    }

    /**
     * 自动完成标签
     * @param <String> $query
     * @return Array
     * @author ward
     * @final 2010-08-27 18:26
     */
    public function auto_complete($query)
    {
        $ret = $this->createQuery()->select('name')
                    ->where('name like ?', $query . '%')
                    ->orderBy('id desc')
                    ->offset(0)
                    ->limit(10)
                    ->execute();
        return $ret;;
    }
}