<?php


class AttachmentCategorysTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('AttachmentCategorys');
    }

    public function getSelectCategorys()
    {
        $categorys = $this->findAll()->toArray();
        $result_categorys = array(
                                0 => '请选择分类',
                            );

        foreach($categorys as $category)
        {
            $result_categorys[$category['id']] = $category['name'];
        }
        
        return $result_categorys;
    }

    public function getCategoryName($id)
    {
        $category = $this->createQuery()->where('id = ?',$id)->fetchOne();
        if($category)
        {
            return $category->getName();
        }else{
            return;
        }
    }

}