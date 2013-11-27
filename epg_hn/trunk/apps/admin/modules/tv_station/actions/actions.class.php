<?php

require_once dirname(__FILE__).'/../lib/tv_stationGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/tv_stationGeneratorHelper.class.php';

/**
 * tv_station actions.
 *
 * @package    epg
 * @subpackage tv_station
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tv_stationActions extends autoTv_stationActions
{
   private $id        = '';
   public function executeIndex(sfWebRequest $request) {

       $id  = $request->getParameter('id');
       //过滤查询
       if (is_numeric($id)) {
             $this->setFilters(array('parent_id'=>$id));
       }
       $filters = $this->getFilters();
       if(key_exists('parent_id', $filters)) {
           $this->id           = $filters['parent_id'];
           $this->buildQuery();
       }
       parent::executeIndex($request);
    }
    
    protected function buildQuery()
    {
        $tableMethod = $this->configuration->getTableMethod();
        if (null === $this->filters)
        {
          $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);

        $filter = $this->getFilters();
        $query  = $this->filters->buildQuery($filter);
        if (!empty($this->id)) {
        	if($filter['publish']!=0||$filter['publish']==''){
            	$query->orWhere('id = ?', $this->id);
        	}
            $query->addOrderBy('parent_id desc,sort asc');
        }
        $this->addSortQuery($query);
        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();
        return $query;
    }
    /**
     * 批量发布
     * @param sfWebRequest $request
     */
    public function executeBatchPublish(sfWebRequest $request) {
        $ids = $request->getParameter('ids');

        $records = Doctrine_Query::create()
            ->from('TvStation')
            ->whereIn('id', $ids)
            ->execute();

        foreach ($records as $record) {
            $record->setPublish(1);
            $record->save();
        }

        $this->getUser()->setFlash('notice', '发布成功。');
        $this->redirect('@tv_station');
    }

    /**
     * 批量隐藏
     * @param sfWebRequest $request
     */
    public function executeBatchUnPublish(sfWebRequest $request) {
        $ids = $request->getParameter('ids');

        $records = Doctrine_Query::create()
            ->from('TvStation')
            ->whereIn('id', $ids)
            ->execute();

        foreach ($records as $record) {
            $record->setPublish(0);
            $record->save();
        }

        $this->getUser()->setFlash('notice', '取消发布。');
        $this->redirect('@tv_station');
    }


    /**
     * 发布状态
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeAjax_program_publish(sfWebRequest $request)
    {
        $id     = $request->getParameter('id',0);
        $return = Doctrine::getTable('TvStation')->findOneById($id);

        //无记录
        if(!$return) {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '记录不存在')));
        }

        if($return->getPublish() == 1) {
            $msg    = 0;
            $content = '【隐藏】成功';
            $return->setPublish(0);
        }else{
            $msg    = 1;
            $content = '【发布】成功';
            $return->setPublish(1);
        }

        $return->save();

        return $this->renderText(json_encode(array('code'=>1,'msg'=>$msg ,'content' => $content)));
    }

    /**
     * 根据字段名称设置字段值
     * @param sfWebRequest $request
     * @return <type>
     */
     public function executeAjax_program_update(sfWebRequest $request) {
         $id        = $request->getParameter('id',0);
         $key       = $request->getParameter('key',0);
         $value     = $request->getParameter('value',0);
         $allow     = array('sort');

         if(!in_array($key, $allow))  {
             return $this->renderText(json_encode(array('code' => 0, 'msg' => '非法字段')));
         }
         
         $return    = TvStationTable::update_data($id,$key,$value);

         return $this->renderText(json_encode($return));
     }

     function executeDelete(sfWebRequest $request){
         $id = $request->getParameter('id');
         $tv_station = Doctrine::getTable('TvStation')->findOneById($id);
         $tv_stations = Doctrine::getTable('TvStation')->findByParentId($tv_station->getId());
         if(!empty ($tv_stations)){
             foreach ($tv_stations as $one){
                 $one->delete();
             }
         }
         $tv_station->delete();
         $this->getUser()->setFlash('notice', '已删除所选择的电视台!');
         $this->redirect('tv_station/index');
     }

    public function executeNew(sfWebRequest $request){
        $this->form = $this->configuration->getForm();
        $this->tv_station = $this->form->getObject();      
        $this->setTemplate("edit");
  }

    public function executeCreate(sfWebRequest $request){
        $this->executeNew($request);
        $arr = $request->getParameter($this->form->getName());
        //$vals = $this->form->getValues();
        /*
        $records = Doctrine_Query::create()
            ->from('TvStation')
            ->where('name=?', $arr['name'])
            ->execute();
        */    
        $records = Doctrine::getTable('TvStation')->findOneByName($arr['name']);
        if($records){
            $this->getUser()->setFlash('notice', '该电视台名称已存在!');
        }else{            
            $this->processForm($request, $this->form);
        }
  }

    public function executeUpdate(sfWebRequest $request){
        $this->tv_station = $this->getRoute()->getObject();
        $this->form = $this->configuration->getForm($this->tv_station);
//        echo $this->form["parent_id"]->render();
//        exit;
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
  }

}
