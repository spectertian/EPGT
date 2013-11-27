<?php

require_once dirname(__FILE__).'/../lib/channelGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/channelGeneratorHelper.class.php';

/**
 * channel actions.
 *
 * @package    epg
 * @subpackage channel
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelActions extends autoChannelActions {

    private $station_id  = '';
    /**
     * 覆盖父类过滤器
     * @param sfWebRequest $request
     * @author ward
     * @final 2010-09-09 11:26
     */
    public function executeFilter(sfWebRequest $request) {
        $channel_filters = $request->getParameter('channel_filters');
        $this->station_id  = Doctrine::getTable('TvStation')->getChannelByTvStationId($channel_filters['tv_station_id']);
        parent::executeFilter($request);
    }

    protected function buildQuery()
    {
        $tableMethod = $this->configuration->getTableMethod();
        if (null === $this->filters)
        {
          $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);

        $query  = $this->filters->buildQuery($this->getFilters());
        if (!empty($this->station_id)) {
            foreach($this->station_id as $station){
                $query->orWhereIn('tv_station_id',$station->getId());
            }
        }        
        $this->addSortQuery($query);
        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();
        return $query;
    }
    
/*    public function executeIndex(sfWebRequest $request) {

       $id  = $request->getParameter('id');
       //过滤查询
       if (is_numeric($id)) {
             $this->setFilters(array('tv_station_id'=>$id));
       }
       $filters = $this->getFilters();
       if(key_exists('tv_station_id', $filters)) {
           $this->station_id    = Doctrine::getTable('TvStation')->getChannelByTvStationId($filters['tv_station_id']);
           $this->buildQuery();
       }
       
       parent::executeIndex($request);
    }*/

    /**
     * 根据字段名称，修改字段值
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_update(sfWebRequest $req) {
        $return = array('code'=>0, '非法请求');
        $name   = $req->getParameter('key');
        $value  = $req->getParameter('value');
        $id     = $req->getParameter('id');
        $allow  = array('sort_id');
        if(!in_array($name, $allow)) {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '非法字段')));
        }
        $return = ChannelTable::ajaxUpdate($id, $name, $value);
        return $this->renderText(json_encode($return));
    }

    //设置发布状态
    public function executeHide(sfWebRequest $req) {
        $ids        = $req->getParameter('ids', 'a');
        $is_show    = $req-> getParameter('is_show', 1);
        
        if((is_numeric($id) || is_array($ids)) && is_numeric($is_show))
        {
            $return = ChannelTable::setStatus($ids, $is_show);
            return $this->renderText(json_encode($return));
        }
        return sfView::NONE;
    }

    /**
     * 频道批量隐藏
     * @param sfWebRequest $req
     */
    public function executeBatchUnpublish(sfWebRequest $req) {
        $ids    = $req-> getParameter('ids', 'error');
        $this-> _doPublish($ids, 0);
    }

    /**
     * 频道批量显示
     * @param sfWebRequest $req
     */
    public function executeBatchPublish(sfWebRequest $req) {
        $ids    = $req-> getParameter('ids', 'error');
        $this-> _doPublish($ids, 1);
    }

    private function _doPublish($ids, $is_show) {
        if(is_array($ids) || is_numeric($ids))
        {
            ChannelTable::setStatus($ids, $is_show);
            $this->getUser()->setFlash('notice', '操作成功.');
        }else{
            $this->getUser()->setFlash('notice', '非法的参数.');
        }
        $this->redirect('@channel');
    }

    /**
     * 数组判断
     * @param <type> $arr
     * @return int
     */
    private function _isNum($arr) {
        $flag   = 1;
        foreach ($arr as $key => $value)
        {
            if(!is_numeric($value))  return 0;
        }
        return $flag;
    }

    /**
     * 发布状态
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeAjax_program_publish(sfWebRequest $request) {
        $id     = $request->getParameter('id',0);
        $return = Doctrine::getTable('Channel')->findOneById($id);

        //无记录
        if(!$return) {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '记录不存在')));
        }

        if($return->getPublish() == 1) {
            $msg     = 0;
            $content = '【隐藏】成功';
            $return->setPublish(0);
        }else{
            $msg    = 1;
            $content = '【发布】成功';
            $return->setPublish(1);
        }
        $return->save();
        return $this->renderText(json_encode(array('code'=>1,'msg'=>$msg,'content' => $content)));
    }

    public function executebatchDelete(sfWebRequest $request){
        $ids = $request->getParameter('ids');
        foreach ($ids as $id){
            $channel = Doctrine::getTable("Channel")->findOneById($id);
            if(!empty ($channel)) $channel->delete();
        }
        $this->getUser()->setFlash('notice', '已删除选择项!');
    }

    /**
     * 删除记录
     * @param sfWebRequest $request
     * @author ly
     */
    public function executeDelete(sfWebRequest $request) {
        $id = $request->getParameter('id',0);
        $channel = Doctrine::getTable("Channel")->findOneById($id);
        if(!empty($channel)) $channel->delete();
        $this->getUser()->setFlash('notice', '频道已删除!');
        $this->redirect('channel/index');

    }

    public function executeNew(sfWebRequest $request){
        $this->form = $this->configuration->getForm();
        $this->channel = $this->form->getObject();
        //共有模板
        $this->setTemplate("edit");
    }
    public function executeCreate(sfWebRequest $request){
        $this->form = $this->configuration->getForm();
        /*
        $arr = $request->getParameter($this->form->getName());
        echo "<pre>";
        print_r($arr);
        exit;
        */            
        $this->channel = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    /**
     * 获取远程图片到本地
     * @param sfWebRequest $request
     * @author lifucang
     */
    public function executeGetimage(sfWebRequest $request) {
        set_time_limit(0);
        $channels = Doctrine::getTable("Channel")->findAll();
        foreach ($channels as $channel){
            //echo Common::file_url($channel->getLogo());
            //echo "<br/>";
            $img = @file_get_contents(Common::file_url($channel->getLogo())); 
            if($img)
            {
                //$FileName="/www/newepg/web_admin/uploads/".$channel->getLogo();
                $FileName="/www/newepg/web_admin/uploads/".iconv("UTF-8","GBK",$channel->getName()).'.png';
                $fp = @fopen($FileName,"w");
                @fwrite($fp,$img);
                @fclose($fp);
            } 
            
        }
        echo "完成";
        return sfView::NONE;
    }
    /**
     * 显示本地台标
     * @param sfWebRequest $request
     * @author lifucang
     */
    public function executeListimage(sfWebRequest $request) {
        $this->mc = $request->getParameter('mc', '');
        
        $this->page=$request->getParameter('page',1);        //默认第1页
        $this->pager = new sfDoctrinePager('Channel', 20);
        if($this->mc!=''){
            $q=Doctrine::getTable('channel')->createQuery()->where("name like ?","$this->mc%");   
        }else{
            $q=Doctrine::getTable('channel')->createQuery();   
        }
		$this->pager->setQuery($q);  //查询和mongodb不一样
        $this->pager->setPage($this->page);
        $this->pager->init();
    }
}
