<?php


class ProgramTemplateTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ProgramTemplate');
    }

    //频道是否存在模板
    public static function isTemplate($index_id)
    {
        $exist  = Doctrine::getTable('ProgramTemplate')-> findOneByPId($index_id);
        return $exist;
    }

    /**
     * 返回模板列表
     * @param <Int> $index_id
     * @return <Array>
     * @author ward
     */
    public static function getTemplateList($index_id) {
        $tem    = self::isTemplate($index_id);
        if($tem)
        {
            //$tem    = Doctrine::getTable('ProgramTemplate')->findByPId($index_id);
            $tem    = Doctrine::getTable('ProgramTemplate')->createQuery()
                    ->where('p_id=?', $index_id)
                    ->addOrderBy('time asc')
                    ->execute();
            return array('code' => 1, 'msg' => $tem);
        }
        return array('code' => '0', 'msg' => '没有记录');
    }

    /**
     * 批量添加
     * @param <String> $name
     * @param <String> $time
     * @param <String> $pid
     */
    public static function Tem_add($name,$time,$pid) {
        foreach ($name as $key => $value)
        {
            if(!empty($value) && !empty($time[$key]))
            {
                $tem    = new ProgramTemplate();
                $tem->setPId($pid);
                $tem->setName($value);
                $tem->setTime($time[$key]);
                $tem->save();
            }
        }
    }

    public static function ajaxUpdate($id, $name, $value) {
        $return = array('code'=>0, 'msg'=>'位置错误');

        if(is_numeric($id)) {
            $rs    = Doctrine::getTable('ProgramTemplate')->findOneById($id);
            if($rs) {
                $rs->set($name, $value);
                $rs->save();
                $return = array('code'=>1, 'msg'=>'更新成功');
            }else{
                $return['msg']  = '记录不存在!';
                return $return;
            }
        }else{
            return $return;
        }
        return  $return;
    }

    public static function delByPid($pid){
        Doctrine::getTable('ProgramTemplate')->createQuery()->delete()
          ->where('p_id=?',$pid)
          ->execute();
    }


    //添加数据
    public static  function save_data($pid, $name, $time){
        $data['id']     = 0;
        $pro    = Doctrine::getTable('ProgramIndex')->findOneById($pid);
        if($pro){
            $tem    = new ProgramTemplate();
            $tem->setWikiId(1);
            $tem->setPId(trim($pid));
            $tem->setName(trim($name));
            $tem->setTime(trim($time));
            $tem->save();
            $data['id'] = $tem->getId();
            $data['title']  = $pro->getTitle();
        }
        return $data;
    }

    /**
     * 模板存为节目单
     * @param <Int> $channel_id
     * @param <Int> $wiki_id
     * @param <Int> $time
     * @param <Int> $name
     * @param <Int> $date
     * @return string
     */
    public static function template_to_program($channel_id, $wiki_id, $time, $name, $date){
        $return = array('code' => 0);
        if(empty($channel_id)) {
            $return['msg']  = '频道ID不存在';
            return $return;
        }
        
        if(empty($wiki_id)) {
            $return['msg']  = 'wiki ID不存在';
            return $return;
        }

        if(empty($date)) {
            $return['msg']  = '节目单日期未设定';
            return $return;
        }

        //节目单模板存未节目单
        foreach ($time as $key => $value) {
            $program    = new Program();

            $program->setPublish(0);
            $program->setChannelId($channel_id);
            $program->setWikiId($wiki_id[$key]);
            $program->setName($name[$key]);
            $program->setTime($value);
            $program->setDate($date);
            $program->save();
        }
        $return['msg']  = '节目单模板导入成功';
        $return['code']  = 0;
        return $return;
    }

    public static function program_to_template($ids, $channel_id) {
        $pro    = Doctrine::getTable('Program')->createQuery()
          ->whereIn('id', $ids)->execute();
      $title    = date('m-d H:i:s模板');
      $tem  = new ProgramIndex();
      $tem->setChannelId($channel_id);
      $tem->setTitle($title);
      $tem->save();

      $id   = $tem->getId();

      if($id > 0) {
          foreach ($pro as $rs) {
              $template = new ProgramTemplate();
              $template->setPId($id);
              $template->setName($rs->getName());
              $template->setWikiId($rs->getWikiId());
              $template->setTime($rs->getTime());
              $template->save();
          }
          return '导入成功,请查看 【'.$title .'】 节目单模板';
      }
      return '导入失败';
    }
}