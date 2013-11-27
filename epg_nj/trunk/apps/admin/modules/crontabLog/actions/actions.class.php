<?php

/**
 * crontabLog actions.
 *
 * @package    epg2.0
 * @subpackage crontabLog
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crontabLogActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $query_arr=array();
        $this->crontabNames=array(
            'GetProgramsWeek'=>'获取节目',
            'GetCpg'=>'获取回看',
            'CpgToProgram'=>'回看匹配',
            'GetPrograms'=>'获取节目',
            'GetWikis'=>'获取维基',
            'GetAttachments'=>'获取图片',
            'GetWikisNoCover'=>'获取没有的图片',
            'GetChannelRecommend'=>'获取频道推荐',
            'GetContentImport'=>'获取cms内容匹配',
            'CdiToVideo'=>'上下线',
            'wikiToXunSearch'=>'更新迅搜',
            'GetChannelHot'=>'获取频道热度',
            'LiveLog'=>'直播点击导入',
            'RecommandFix'=>'获取运营推荐',
            'ExportInject'=>'导出ADI',
            'ExportImport'=>'导出ADI处理数据',
            'DelProgramCpg'=>'删除早期节目',
            'EpgAdiDays'=>'发送ADI',
            'EpgAdi'=>'发送ADI',
            'tmpEpgAdi'=>'发送ADI到新接口',
            'tmpEpgAdiDays'=>'发送ADI到新接口',
            'EpgHd'=>'发送XML',
            'DelHdFtp'=>'删除FTP数据',
            'ExportProgram'=>'发送json',
            'exportEpgAdi'=>'导出ADI到FTP',
            'exportEpgHd'=>'导出xml到FTP',
            'GetNJBCPrograms'=>'获取大网节目',
        );
        $this->title = trim($request->getGetParameter('title', null));
        $this->state = intval($request->getGetParameter('state', -1));
        $this->date = trim($request->getGetParameter('date', date("Y-m-d")));
        $this->date2 = strtotime('+1 day',strtotime($this->date));
        $startTime =new MongoDate(strtotime($this->date));
        $endTime =new MongoDate($this->date2);
        if($this->date){
            $query_arr['start_time']=array('$gte'=>$startTime,'$lt'=>$endTime);
        }
        if($this->title){
            $query_arr['title']=$this->title;
        }
        if($this->state!=-1){
            $query_arr['state']=$this->state;
        }
        $this->pager = new sfMondongoPager('CrontabLog', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('_id' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    public function executeDel(sfWebRequest $request)
    {
       if($request->isMethod("POST")){
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0){
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的日志！');
           }else{
               $mongo = $this->getMondongo();
               $reps = $mongo->getRepository("CrontabLog");
               foreach($ids as $id){
                   $rs = $reps->findOneById(new MongoId($id));
                   $rs -> delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'crontabLog','action'=>'index')));
    } 
}
