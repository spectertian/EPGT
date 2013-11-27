<?php

require_once dirname(__FILE__) . '/../lib/programGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/programGeneratorHelper.class.php';

/**
 * program actions.
 *
 * @package    epg
 * @subpackage program
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class programActions extends autoProgramActions {

    public function executeIndex(sfWebRequest $request) {
        $datas = $this->getUser()->getAttribute('datas');
        $reset  = $request->getParameter('do');
        $status = 0;
        $date   = $request->getParameter('date', date('Y-m-d'));
        $limit  = array('from' => $date, 'to' => $date);

        if ($request->getParameter('channel_id') || !$datas || $request->getParameter('date')) {
            $channel_id = $request->getParameter('channel_id', 1);
            $status = 1;
        } else {
            $channel_id = $datas['channel_id'];
            $date = $datas['date'];
        }
        $week = date('w', strtotime($date));
        $channel = Doctrine::getTable('Channel')->findOneById($channel_id);
        $this->getUser()->setAttribute('datas', array('channel_id' => $channel_id, 'date' => $date, 'week' => $week, 'channel' => $channel ? $channel->getName() : ''));

        if ($status) {
            $this->setFilters(array('channel_id' => $channel_id, 'date' => array('from' => $date, 'to' => $date)));
        }
        $this->from = $this->getFilters('from');
        parent::executeIndex($request);
    }

    public function executeFilter(sfWebRequest $request) {
        $program_filters = $request->getParameter('program_filters');
        $date = $program_filters['date'];
        $date_from = $date['from'];
        $date_to = $date['to'];
        $channel_id = $program_filters['channel_id'];
        if ($channel_id && ($date_from || $date_to)) {
            $day = $date_from ? $date_from : $date_to;
            $week = date('w', strtotime($day));
            $this->getUser()->setAttribute('datas', array('channel_id' => $channel_id, 'date' => $day, 'week' => $week));
        }
         $this->setFilters(array('channel_id' => $program_filters['channel_id'], 'date' => $program_filters['date'] , 'publish' => $program_filters['publish']));
        parent::executeFilter($request);
    }

    public function executeBatch_add(sfWebRequest $request) {
    }


    public function executeBatchPublish(sfWebRequest $request) {
        $ids = $request->getParameter('ids');

        $records = Doctrine_Query::create()
                        ->from('Program')
                        ->whereIn('id', $ids)
                        ->execute();

        foreach ($records as $record) {
            $record->setPublish(1);
            $record->save();
        }

        $this->getUser()->setFlash('notice', '发布成功。');
        $this->redirect('@program');
    }


    public function executeBatchUnPublish(sfWebRequest $request) {
        $ids = $request->getParameter('ids');

        $records = Doctrine_Query::create()
                        ->from('Program')
                        ->whereIn('id', $ids)
                        ->execute();

        foreach ($records as $record) {
            $record->setPublish(0);
            $record->save();
        }

        $this->getUser()->setFlash('notice', '取消发布。');
        $this->redirect('@program');
    }

    public function executeAjax_update(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $this->getResponse()->setContentType('application/x-json');
            $id = $request->getParameter('id');
            $name = $request->getParameter('name');
            $value = trim($request->getParameter('value'));

            $program = Doctrine::getTable('Program')->findOneById($id);
            if (!$program) {
                return $this->renderText(json_encode(array('status' => 'error', 'message' => '请选择一个ID')));
            }

            if ($program->hasRelation($name) && strlen($value)) {

                if ($name == 'time') {
                    if (!strtotime($value)) {
                        return $this->renderText(json_encode(array('status' => 'error', 'message' => '请输入一个正确的 H:m 小时:分钟 格式')));
                    }
                }

                $program->set($name, $value);
                $program->save();

                return $this->renderText(json_encode(array('status' => 'ok', 'message' => '编辑成功')));
            }
        }

        return $this->renderText('失败');
    }

    public function executeAjax_add(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $this->getResponse()->setContentType('application/x-json');
            $name = $request->getParameter('name');
            $channel_id = $request->getParameter('channel_id');
            $time = $request->getParameter('time');
            $publish = $request->getParameter('publish');
            $date = $request->getParameter('date');
            $wiki_id = $request->getParameter('wiki_id');

            $program = new Program();
            $program->setName($name);
            $program->setChannelId($channel_id);
            $program->setWikiId($wiki_id);
            $program->setTime($time);
            $program->setDate($date);
            $program->save();
            
            return $this->renderText(json_encode(array('id' => $program->getId(), 'status' => 'ok', 'message' => '添加成功')));
        }

        return $this->renderText(json_encode(array('status' => 'error', 'message' => '添加失败')));
    }

    /**
     * 插入节目
     * @param sfWebRequest $request
     * @return <type>
     * @author ward
     */
    public function executeAjax_program_insert(sfWebRequest $request) {
        $name    = $request->getParameter('name');
        $time    = $request->getParameter('time');
        $filter  = $this->getFilters();
        $wiki_id = NULL;
        //print_r($filter);

        //未设定日期
        if(!array_key_exists('date', $filter)) {
            return $this->renderText(json_encode(array('code' => 0, 'msg' => '请选择节目日期')));
        }
        

        if(!array_key_exists('from', $filter['date'])) {
            return $this->renderText(json_encode(array('code' => 0, 'msg' => '请选择节目日期')));
        }

        //未设定频道ID
        if(!array_key_exists('channel_id', $filter)) {
            return $this->renderText(json_encode(array('code' => 0, 'msg' => '请选择频道')));
        }

        $channel    = Doctrine::getTable('Channel')->findOneById($filter['channel_id']);
        
        if(!$channel) {
            return $this->renderText(json_encode(array('code' => 0, 'msg' => '目标频道不存在')));
        }
        
        $program    = ProgramTable::save_data($name, $time, $filter['channel_id'], $filter['date']['from'], $wiki_id);
        $program['channel'] = $channel->getName();
        $program['date']    = $filter['date']['from'];
        
        return $this->renderText(json_encode($program));
    }

    /**
     * 更新program表字段值
     * @param sfWebRequest $request
     * @return <type>
     * @author ward
     */
    public function executeAjax_program_update(sfWebRequest $request)
    {
        $name   = $request->getParameter('name');
        $value  = $request->getParameter('value');
        $id     = $request->getParameter('id');
        $return = ProgramTable::update_data($id, $name, $value);
        
        return $this->renderText(json_encode($return));
    }

    /**
     * 节目状态改变
     * @param sfWebRequest $request
     * @return <type>
     * @author ward
     */
    public function executeAjax_program_publish(sfWebRequest $request)
    {
        $id     = $request->getParameter('id',0);
        $return = Doctrine::getTable('Program')->findOneById($id);

        //无记录
        if(!$return)
        {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '记录不存在')));
        }
        
        if($return->getPublish() == 1)
        {
            $msg    = 0;
            $content    = '【隐藏成功】';
            $return->setPublish(0);
        }
        else
        {
            $msg    = 1;
            $content    = '【发布成功】';
            $return->setPublish(1);
        }

        $return->save();

        return $this->renderText(json_encode(array('code'=>1,'msg'=>$msg,'content'=>$content)));
    }
    
    /**
     * 节目单存为模板
     * @param sfWebRequest $request
     * @author ward
     */
    public function executeBatchSave(sfWebRequest $request)
    {
        $ids    = $request->getParameter('ids');
        $filter  = $this->getFilters();
        $channel_id = $filter['channel_id'];
        if(empty($channel_id))
        {
            $this->getUser()->setFlash('error', '未设定频道');
            $this->redirect('@program');
        }
        
        $save   = ProgramTemplateTable::program_to_template($ids, $channel_id);
        $this->getUser()->setFlash('notice', $save);
    }

    public function executeAuto_complete_set(sfWebRequest $request) {

        $arr    = array('code' => 0, 'msg' => '更新失败');

        $this->id        = $request->getParameter('id');
        $this->wiki_id   = $request->getParameter('wiki_id');
        
        $program         = Doctrine::getTable('Program')->findOneById($this->id);
        if ($program) {
            $program->setWikiId($this->wiki_id);
            $program->save();
            $arr    = array('code' => 1, 'msg' => '更新成功');
        }else{
            $arr    = array('code' => 0, 'msg' => '记录不存在');
        }
        return $this->renderText(json_encode($arr));
    }

    /**
     * 自动完成节目名称
     * @param sfWebRequest $req
     * @return <type>
     * @author ward
     */
    public function executeAuto_complete_name(sfWebRequest $req) {

        $this->query = $req->getParameter('query', 'index');
        $program    = Doctrine::getTable('Program')->auto_complete($this->query);
        return $this->renderText($program);
    }
    
    /**
     * 推荐<top> 热门<hot> 新节目<new>
     * @param sfWebRequest $req
     * @author ward
     */
    public function executeAjax_ext(sfWebRequest $req) {
        $this->id   = $req->getParameter('id', 0);
        $this->style = $req->getParameter('style', 'new');
        $lang        = array('new'=>'【新节目】', 'top'=>'【推荐】', 'hot'=>'【热播】');
        $exist       = key_exists($this->style, $lang);
        if($exist) {
            $return = Doctrine::getTable('ProgramExt')->change_ext($this->id, $this->style);
            if($return == 0){
                $arr    = array('code'=> $return, 'msg' => '取消'.$lang[$this->style].'成功');
            }else{
                $arr    = array('code'=> $return, 'msg' => '添加'.$lang[$this->style].'成功');
            }
        }else{
            $arr    = array('code' => 3 ,'msg' => '非法操作');
        }
        return $this->renderText(json_encode($arr));
    }


     /**
      * 添加节目标签
      * @param sfWebRequest $request
      * @return <type>
      */
     public function executeTag_add(sfWebRequest $request) {
         $this->id      = $request->getParameter('id');
         $this->value   = trim($request->getParameter('value'));
         $this->value   = str_replace('，', ',', $this->value);
         if(!empty ($this->value)){
             $this->value = explode(',', $this->value);
         }
         $length      = count($this->value);
         if (count($this->value) > 0) {
             $exist       = Doctrine::getTable('Program')->findOneById($this->id);

             if(!$exist) {
                $arr   = array('msg'=> '节目不存在', 'id' => 0, 'code' => 0);
                return $this->renderText(json_encode($arr));
             }elseif(empty($this->value)){
                $arr   = array('msg'=> '标签不能为空', 'id' => 0, 'code' => 0);
                return $this->renderText(json_encode($arr));
             }
             $save  = $exist->setTags($this->value);
             $arr   = array('msg'=> '添加成功', 'id' => $save, 'post' => $this->value,'code' => 1);
             return $this->renderText(json_encode($arr));
         }

         $arr   = array('msg'=> '添加失败', 'id' => 0 ,'code' => 0);
         return $this->renderText(json_encode($arr));
     }

    /**
     * 删除节目标签
     * @param sfWebRequest $request
     * @return <type>
     */
     public function executeTag_del(sfWebRequest $request) {
         $this->id  = $request->getParameter('id');
         $exist     = Doctrine::getTable('ProgramTag')->findOneById($this->id);
         if ($exist) {
            $exist->delete();
         }
         return $this->renderText('请求已执行');
     }

     public function executeWiki_del(sfWebRequest $request) {
         $this->id  = $request->getParameter('id');
         $program   = Doctrine::getTable('Program')->findOneByid($this->id);
         if ($program) {
             $program->setWikiId(0);
             $program->save();
         }
         return $this->renderText('删除关联成功');;
         
     }
}
