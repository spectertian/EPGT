<?php

/**
 * program actions.
 *
 * @package    epg
 * @subpackage mongo_program
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class video_crawlerActions extends sfActions {


	public function executeIndex(sfWebRequest $request) 
	{
        $this->wiki  = $request->getGetParameter('wiki', '');
        $this->field = $request->getGetParameter('field', '');
        $this->text  = trim($request->getGetParameter('text', ''));
        $this->model = $request->getGetParameter('model', 'all');
        $this->state = $request->getGetParameter('state', 'all');
        $query_arr=array();
        if($this->field!=''){
        	if(trim($this->text) != '')
        	{
	            if($this->field == 1)
		            $query_arr['title']=new MongoRegex("/.*$this->text.*/i");
	            if($this->field == 2)
		            $query_arr['url']=new MongoRegex("/.*$this->text.*/i");
        	}
        }
        if($this->wiki!='')
        {
            if($this->wiki == 1)
            	$query_arr['wiki_id']=array('$exists'=>true,'$ne'=>'');
            else
            	$query_arr['wiki_id']=array('$exists'=>false);
        }
        if($this->model != 'all')
        	$query_arr['model'] = $this->model;
        	
		if($this->state != 'all')
		{
			$query_arr['state'] = (int)$this->state;
		}
		
        $query = array('query'=>$query_arr,'sort' => array('_id' => -1));
        $page    = $request->getParameter('page', 1);
        $this->vcs = new sfMondongoPager('VideoCrawler', 20);
        $this->vcs->setFindOptions($query);
        $this->vcs->setPage($page);
        $this->vcs->init();
        $this->pageTitle    = '视频抓取'; 
    }


	public function executeDelete(sfWebRequest $request)
    {
    	//Modify by tianzhongsheng-ex 2013-11-12 12:52:00  删除功能修改
		$id = $request->getParameter('id');
		if(!$id)
		{
			$this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
			$this->redirect($this->generateUrl('',array('module'=>'video_crawler','action'=>'index')));
		}
		$mongo = $this->getMondongo();
		$vcm_mongo = $mongo->getRepository("videocrawler");
		$video = $mongo->getRepository('Video');
		$vpl   = $mongo->getRepository('VideoPlaylist');

		$vc = $vcm_mongo->findOneById(new MongoId($id));
		$vcid = strval($vc->getId());
		$vc->delete();//videocrawler
		$vplone = $vpl->find(array('query'=>array('vc_id'=>$vcid)));
		if($vplone)
		{
			foreach($vplone as $value)
			{
			$value->delete();//VideoPlaylist
			}
		}
		$videos = $video->find(array('query'=>array('vc_id'=>$vcid)));
		if($videos)
		{
			foreach($videos as $value)
			{
				$value->delete();//Video
			}
		}
		$this->getUser()->setFlash("notice",'删除成功!');
		$this->redirect($this->generateUrl('',array('module'=>'video_crawler','action'=>'index')));
    }

    public function executeDeleteAjax(sfWebRequest $request)
    {
       if ($request->isMethod("POST") && $request->isXmlHttpRequest())
       {
           $ids = $request->getParameter('ids');
           $ids =rtrim($ids,',');
           $ids=explode(',',$ids);
           //return $this->renderText(implode(',',$ids));
           if(count($ids)==0)
           {
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
               return $this->renderText(0);
           }else{
               $mongo = $this->getMondongo();
               $vc_mongo = $mongo->getRepository("videocrawler");
               $video = $mongo->getRepository('Video');
               $vpl   = $mongo->getRepository('VideoPlaylist');
               foreach($ids as $id){
               $vc = $vc_mongo->findOneById(new MongoId($id));
                   $vcid = strval($vc->getId());
                   $vc->delete();//videocrawler
                   $vplone = $vpl->find(array('query'=>array('vc_id'=>$vcid)));
                   //print_r($vplone);exit;
                   if($vplone){
                       foreach($vplone as $value){
                           $value->delete();//VideoPlaylist
                       }
                   }
                   $videos = $video->find(array('query'=>array('vc_id'=>$vcid)));
                   if($videos){
                       foreach($videos as $value){
                           $value->delete();//Video
                       }
                   }
               }
               $this->getUser()->setFlash("notice",'删除成功!');
               return $this->renderText(1);
           }
       }
    }
    

    public function executeSave(sfWebRequest $request)
    {
        if($request->isMethod("POST") && $request->isXmlHttpRequest())
        {
            $this->getResponse()->setHttpHeader("Content-type",'application/json;charset=UTF-8');
            $id = $request->getParameter('id', 0);
            $title = trim($request->getParameter('name',''));
            $wiki_id = trim($request->getParameter('wiki_id',''));

            $return_status = array('program_id'=> false);
            $mongo =  $this->getMondongo();

            $vc_mongo = $mongo->getRepository("videocrawler");
            if ($id == 0)
               $vc = new videocrawler();
			else
               $vc = $vc_mongo->findOneById(new MongoId($id));

            $vc->setTitle($title);
            $vc->setWikiId($wiki_id);

            //$admin = $this->getUser()->getAttribute("username");
            //$vc->setAdmin($admin);
            
            $vc->save();
            if($wiki_id!='')
            {
            	$vc->deleteVideo();
				$baiduTM = BaiduvideoToMongo::getInstance();
				$baiduTM ->setMongo($mongo) ;
				$baiduTM ->setOutput(false) ;
				$baiduTM ->setObject($vc);
            }
            $this->getUser()->setFlash("notice",'操作成功!');
            $return_status['program_id'] = (string)$vc->getId();
            return $this->renderText(json_encode($return_status));
        }
    }

    public function executeLoadWiki(sfWebRequest $request)
    {
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");
        $this->wikis = $wiki_mongo->likeWikiName($query);
    }

    public function executeDefault(sfWebRequest $request)
    {
        $this->getUser()->setAttribute('channel_id', '');
        $this->getUser()->setAttribute('tv_station_id', '');
        $this->getUser()->setAttribute('date','');
        $this->redirect('program/index');
    }
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
        $allow  = array('name','time');
        if(!in_array($name, $allow)) {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '非法字段')));
        }
                $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
        $return = $repository->ajaxUpdate($id, $name, $value);
        return $this->renderText(json_encode($return));
    }
    /**
     * 根据字段名称，修改字段值
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_del(sfWebRequest $req) {
        $return = array('code'=>0, '非法请求');
        $id     = $req->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
        $program = $repository->findOneById(new mongoId($id));
        if($program)
        {
        	$program->delete();
        	$return = array('code'=>1, 'msg'=>'删除成功');
        }
        return $this->renderText(json_encode($return));
    }
  
}
