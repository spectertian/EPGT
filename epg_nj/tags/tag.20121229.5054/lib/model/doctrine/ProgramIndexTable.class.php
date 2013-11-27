<?php


class ProgramIndexTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ProgramIndex');
    }
    /**
     * 频道是否存在模板
     */
    public static function isTemplate($index_id)
    {
        $exist  = Doctrine::getTable('ProgramIndex')-> findOneByChannelId($index_id);
        return $exist;
    }

    /**
     * 返回频道模板
     * @param <type> $index_id
     * @return <type>
     */
    public static function getTemplateList($index_id) {
        $tem        = self::isTemplate($index_id);
        $arr_return = array();
        if($tem)
        {
            $tem    = Doctrine::getTable('ProgramIndex')-> findByChannelId($index_id);
            $i=0;
            foreach ($tem as $key => $value)
            {
                //$arr_return[$i]['wiki_id']         = $value->getWikiId();
                //$arr_return[$i]['wiki_name']       = $value->getWiki()->getTitle();;
                $arr_return[$i]['channel_name']    = $value->getChannel()->getName();;
                $arr_return[$i]['channel_id']      = $value->getChannelId();
                $arr_return[$i]['id']              = $value->getId();
                $arr_return[$i]['title']           = $value->getTitle();
                $i++;
            }
            return array('code' => 1, 'msg' => $arr_return);
        }
        return array('code' => '0', 'msg' => '没有记录');
    }

    /**
     * 根据字段设置字段值
     * @param <type> $id
     * @param <type> $name
     * @param <type> $value
     * @return string
     */
    public static function ajaxUpdate($id, $name, $value) {
        $return = array('code'=>0, 'msg'=>'未知错误');
        
        if(!is_numeric($id)) {
            return $return;
        }
        $tem    = Doctrine::getTable('ProgramIndex')->findOneById($id);

        //记录不存在
        if(!$tem) {
            $return['msg']  = '记录不存在';
            return $return;
        }
        
        //字段不存在
        if($tem->hasColumn($name)) {
            $return['msg']  = '字段不存在';
            return  $return;
        }
        
        $tem->set($name, $value);
        $tem->save();
        
        $return = array('code'=>1, 'msg'=>'更新成功');

        return  $return;
    }

    /**
     * 删除模板
     * @param <type> $id
     */
    public static  function ajaxDel($id) {
        ProgramTemplateTable::delByPid($id);
        Doctrine::getTable('ProgramIndex')->createQuery()->delete()
          ->where('id=?',$id)
          ->execute();
    }
}