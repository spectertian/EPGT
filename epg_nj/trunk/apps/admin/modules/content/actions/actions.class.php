<?php

/**
 * program actions.
 *
 * @package    epg
 * @subpackage mongo_program
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contentActions extends sfActions {

    /**
     * 
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        
    }

    
    public function executeInject(sfWebRequest $request) {
        $page    = $request->getParameter('page', 1);
        $this->keyword    = $request->getParameter('keyword', '');
        if($this->keyword!=''){
            $query=array('content' => new MongoRegex("/".trim($this->keyword)."/"));
        }else{
            $query=array();
        }
        $this->query = array('query'=> $query,'sort' => array('_id' => -1));
        $this->injects = new sfMondongoPager('ContentInject', 20);
        $this->injects->setFindOptions($this->query);
        $this->injects->setPage($page);
        $this->injects->init();
        $this->pageTitle    = '内容导入';        
    }
    
    public function executeView(sfWebRequest $request) {
        $this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('ContentInject');
        $injects=$repository->findOneById(new MongoId($id));
        if($injects){
            $content = $injects->getContent();
        }
        //echo $content;
        return $this->renderText($content);
        exit;  
    }
    public function executeViewImport(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('ContentImport');
        $import=$repository->findOneById(new MongoId($id));
        if($import){
            $content = $import->getChildrenId();
            echo '<pre>';
            print_r($content);
            echo 'inject_id:',$import->getInjectId();
        }
        return sfView::NONE;
    }
    public function executeViewCdi(sfWebRequest $request) {
        $this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('ContentCdi');
        $cdi=$repository->findOneById(new MongoId($id));
        if($cdi){
            $content = $cdi->getContent();
        }
        //echo $content;
        return $this->renderText($content);
        exit;  
    }
    
    public function executeImport(sfWebRequest $request) {
        $page  = $request->getParameter('page', 1);
        $query = array();
        
        $this->wikistatus = intval($request->getParameter('searchwiki', '0'));
        $this->line = intval($request->getParameter('line', '-1'));
        $this->check = intval($request->getParameter('check', '-1'));
        $this->error = intval($request->getParameter('error', '-1'));
        $this->match = intval($request->getParameter('match', '0'));
        if($this->wikistatus){
          $values = array(
                1=>true,
                2=>false
              );
          $query['wiki_id'] = array('$exists'=>$values[$this->wikistatus],'$ne'=>'');
        }
        if($this->line!=-1){
            $query['state'] = $this->line;
        }
        if($this->check!=-1){
            if($this->check==1){
                $query['state_check'] = 1;
            }else{
                $query['state_check'] = array('$ne'=>1);
            }
        }
        if($this->error!=-1){
            if($this->error==1){
                $query['state_error'] = 1;
            }else{
                $query['state_error'] = array('$ne'=>1);
            }
        }
        if($this->match){
            $query['state_match'] = $this->match;
        }
        $this->typestatus = intval($request->getParameter('searchtype', 0));
        $this->searchtext = $request->getParameter('searchtext', null);
        if($this->typestatus && $this->searchtext){
          $fileds = array(
                1=>'from_id',
                2=>'from_title',
                3=>'children_id.ID'
              );
          //$query[$fileds[$this->typestatus]] = new MongoRegex("/.*".$this->searchtext.".*/i");
          $query[$fileds[$this->typestatus]] = $this->searchtext;
        }
        
        $this->query   = array('query'=> $query,'sort' => array('_id' => -1));
        $this->imports = new sfMondongoPager('ContentImport', 20);
        $this->imports->setFindOptions($this->query);
        $this->imports->setPage($page);
        $this->imports->init();
        $this->deleted = 0;
        $this->pageTitle = '内容处理';
        
        foreach ($this->imports->getResults() as $import){
            $inject_content=$import->getInject();
            $content=@simplexml_load_string(trim($inject_content));
            $asset_md = $this->getMetadata($content->Asset->Metadata);
            $asset_asset_md = $this->getMetadata($content->Asset->Asset->Metadata);
            $injects[]=array(
                'Director'=>$asset_asset_md['Director'],
                'Actors'=>$asset_asset_md['Actors'],
                'Year'=>$asset_md['Year'],
                'Description'=>$asset_md['Description'],
            );
            unset($asset_md);
            unset($asset_asset_md);
        }
        $this->injects=$injects;
    }
	/**
	 * 上下线信息管理
	 * @author lifucang
	 * @date 2013-07-01
	 */
    public function executeCdi(sfWebRequest $request) {
        $page  = $request->getParameter('page', 1);
        $query = array();

        $this->type = $request->getParameter('type','');
        $this->subid = $request->getParameter('subid', null);
        $this->pageid = $request->getParameter('pageid', null);
        
        if($this->type!=''){
          $query['command'] = $this->type;
        }
        if($this->subid){
          $query['subcontent_id'] = $this->subid;
        }
        if($this->pageid){
          $query['page_id'] = $this->pageid;
        }
        $this->query   = array('query'=> $query,'sort' => array('_id' => -1));
        $this->pager = new sfMondongoPager('ContentCdi', 20);
        $this->pager->setFindOptions($this->query);
        $this->pager->setPage($page);
        $this->pager->init();
        $this->pageTitle = '上下线信息查看';


        $mongo =  $this->getMondongo();
        $importImport_repo = $mongo->getRepository("ContentImport");  
        foreach ($this->pager->getResults() as $cdi){
            $title=null;
            $command=$cdi->getCommand();
            $children_id=$cdi->getSubContentId();
            $page_id=$cdi->getPageId();
            if($children_id){
                $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id)));
                if($importImport){
                    //$title=$importImport->getFromTitle();
                    $childrenids = $importImport->getChildrenId();
                    foreach($childrenids as $childrenid){
                        if($childrenid['ID']==$children_id){
                            $title=$childrenid['Title'];
                        }
                    } 
                }
            }
            $cdis[]=array(
                'command'=>$command,
                'subcontent_id'=>$children_id,
                'page_id'=>$page_id,
                'title'=>$title,
            );
        }
        $this->cdis=$cdis;
    }
	/*
	 * 上下线信息管理,已不用（上下线信息content_cdi未记录subcontent_id和page_id之前）
	 * @author lifucang
	 * @date 2013-07-01
	 */
    public function executeCdiBak(sfWebRequest $request) {
        $page  = $request->getParameter('page', 1);
        $query = array();

        $this->type = $request->getParameter('type','');
        $this->keyword = $request->getParameter('keyword', null);
        
        if($this->type!=''){
          $query['content'] = new MongoRegex("/.*".$this->type.".*/i");
        }
        if($this->keyword){
          $query['content'] = new MongoRegex("/.*".$this->keyword.".*/i");
        }
        
        $this->query   = array('query'=> $query,'sort' => array('_id' => -1));
        $this->pager = new sfMondongoPager('ContentCdi', 20);
        $this->pager->setFindOptions($this->query);
        $this->pager->setPage($page);
        $this->pager->init();
        $this->pageTitle = '上下线信息查看';

        $arr=array('ONLINE_TASK_DONE'=>'上线','CONTENT_OFFLINE'=>'下线','DELIVERY_TASK_DONE'=>'其他');
        $mongo =  $this->getMondongo();
        $importImport_repo = $mongo->getRepository("ContentImport");  
        foreach ($this->pager->getResults() as $cdi){
            $children_id=null;
            $page_id=null;
            $title=null;
            $cdi_content=$cdi->getContent();
            $content=@simplexml_load_string(trim($cdi_content));
            $header=$content->header->attributes();
            //上线
            if($header['command']=='ONLINE_TASK_DONE'){
                foreach($content->body->tasks->task as $val){
                    $attr=$val->attributes();
                    $play_url=$val->play_url;
                    if($play_url){
                        $page_id=$play_url;
                    }else{
                        $page_id=(string)$attr['page-id'];
                    }
                    $children_id=(string)$attr['subcontent-id'];
                    break;
                }
            }elseif($header['command']=='CONTENT_OFFLINE'){
                foreach($content->body->contents->content as $val){
                    $attr=$val->attributes();
                    $children_id=(string)$attr['subcontent-id'];
                    break;
                }
            }
            if($children_id){
                $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id)));
                if($importImport){
                    //$title=$importImport->getFromTitle();
                    $childrenids = $importImport->getChildrenId();
                    foreach($childrenids as $childrenid){
                        if($childrenid['ID']==$children_id){
                            $title=$childrenid['Title'];
                        }
                    } 
                }
            }
            $cdis[]=array(
                'command'=>$header['command'],
                'subcontent_id'=>$children_id,
                'page_id'=>$page_id,
                'title'=>$title,
            );
        }
        $this->cdis=$cdis;
    }
    
    public function executeTemp(sfWebRequest $request) {
      $page    = $request->getParameter('page', 1);
      $query = array();
      
      $this->wikistatus = intval($request->getParameter('searchwiki', '0'));
      if($this->wikistatus){
        $values = array(
            1=>true,
            2=>false
        );
        $query['wiki_id'] = array('$exists'=>$values[$this->wikistatus]);
      }
      
      $this->typestatus = intval($request->getParameter('searchtype', 0));
      $this->searchtext = $request->getParameter('searchtext', null);
      if($this->typestatus && $this->searchtext){
        $fileds = array(
            1=>'from_id',
            2=>'from_title'
        );
        $query[$fileds[$this->typestatus]] = new MongoRegex("/.*".$this->searchtext.".*/i");
      }
      
      $this->query = array('query'=> $query,'sort' => array('_id' => -1));
      $this->imports = new sfMondongoPager('ContentTemp', 20);
      $this->imports->setFindOptions($this->query);
      $this->imports->setPage($page);
      $this->imports->init();
      $this->pageTitle    = '临时内容处理';
    }
    
	public function executeLoadWiki(sfWebRequest $request)
    {
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");
        $this->wikis = $wiki_mongo->likeWikiName($query);
    }
    
    public function executeSave(sfWebRequest $request)
    {	
    	if ($request->isMethod("POST") && $request->isXmlHttpRequest()){
    		$wikiId = trim($request->getParameter('wiki_id',''));
    		$id = $request->getParameter('id', 0);
    		//$title = $request->getParameter('title', 0);
    		$return_status = array('contentId'=> false);
    		if ($id){
    			$mongo = $this->getMondongo();
    			$contentMongo = $mongo->getRepository("contentImport");
    			$content = $contentMongo->findOneById(new MongoId($id));
                $wikiIdBefore = $content->getWikiId();
                $state = $content->getState(); //是否上线
    			$content->setWikiId($wikiId);
    			$content->save();
                //如果wiki_id变化
                if($state==1&&$wikiId!=''&&$wikiIdBefore!=$wikiId){
                    $this->videoUpdate($wikiIdBefore,$wikiId);
                }
    			$this->getUser()->setFlash("notice",'保存成功!');
    			$return_status['contentId'] = (string)$content->getId();
    		}
    		return $this->renderText(json_encode($return_status));
    	}
    }
    //executeSave调用的方法
    private function videoUpdate($wikiIdBefore,$wikiIdAfter)
    {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        $wikiRes = $mongo->getRepository('wiki');
        //更新video
        $videos=$video_repo->find(array('query'=>array('wiki_id'=>$wikiIdBefore)));
        if($videos){
            foreach($videos as $video){
                $video->setWikiId($wikiIdAfter);
                $video->save(); //保存前已自动把相关wiki的has_video设置了，所以不用设置$wikiAfter的has_video值
            }
        }
        //更新videoPlayList
        $videoPlayLists=$videoplaylist_repo->find(array('query'=>array('wiki_id'=>$wikiIdBefore)));
        if($videoPlayLists){
            foreach($videoPlayLists as $videoPlayList){
                $videoPlayList->setWikiId($wikiIdAfter);
                $videoPlayList->save();
            }
        }
        //把之前的wiki的has_video值设为0并更新迅搜
        $wikiBefore = $wikiRes->findOneById(new MongoId($wikiIdBefore));
        if($wikiBefore){
            $wikiBefore->setHasVideo(0);
            $wikiBefore->save();
            $wikiBefore->updateXunSearchDocument();
        }
        //更新迅搜
        $wikiAfter = $wikiRes->findOneById(new MongoId($wikiIdAfter));
        if($wikiAfter){
            $wikiAfter->updateXunSearchDocument();
        }
    }
    public function executeTempSave(sfWebRequest $request)
    {
      if ($request->isMethod("POST") && $request->isXmlHttpRequest()){
        $wikiId = trim($request->getParameter('wiki_id',''));
        $id = $request->getParameter('id', 0);
        //$title = $request->getParameter('title', 0);
        $return_status = array('contentId'=> false);
        if ($id){
          $mongo = $this->getMondongo();
          $contentMongo = $mongo->getRepository("contentTemp");
          $content = $contentMongo->findOneById(new MongoId($id));
          $content->setWikiId($wikiId);
          $content->save();
          $this->getUser()->setFlash("notice",'保存成功!');
          $return_status['contentId'] = (string)$content->getId();
        }
        return $this->renderText(json_encode($return_status));
      }
    }
    
  	public function executeImportDelete(sfWebRequest $request)
    {
       if($request->isMethod("POST"))
       {   
           $reduction = $request->getParameter('type','');
           $ids = $request->getParameter('ids');
           $ids =rtrim($ids,',');
           $ids=explode(',',$ids);
           if(count($ids)==0)
           {
               if($reduction=='reduction'){//传参进行还原操作
                 $this->getUser()->setFlash("error",'还原失败！请选择需要还原的节目！');
               }else{
                 $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
               }
               return $this->renderText(0);
           }else{
               //print_r($reduction);
               if($reduction=='reduction'){//传参进行还原操作
                 foreach($ids as $id){
                   $mongo = $this->getMondongo();
                   $contentMongo = $mongo->getRepository("ContentImport");
                   $program = $contentMongo->findOneById(new MongoId($id));
                   $program->setState(0);
                   $program->save();
                 }
                 $this->getUser()->setFlash("notice",'还原成功!');
               }else{//否则进行删除操作
                 foreach($ids as $id){
                   $mongo = $this->getMondongo();
                   $contentMongo = $mongo->getRepository("ContentImport");
                   $program = $contentMongo->findOneById(new MongoId($id));
                   $program->setState(-1);
                   $program->save();
                 }
                 $this->getUser()->setFlash("notice",'删除成功!');
               }
               
               return $this->renderText(1);
           }
       }
       //$this->redirect($this->generateUrl('',array('module'=>'content','action'=>'import')));
    }
	/**
	 * ADI内容校对
	 * @author lifucang
	 * @date 2013-08-15
	 */
    public function executeImportCheck(sfWebRequest $request)
    {
       if($request->isMethod("POST")){
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0){
               $this->getUser()->setFlash("error",'操作失败！请选择已校对的记录！');
           }else{
               $mongo = $this->getMondongo();
               $reps = $mongo->getRepository("ContentImport");
               foreach($ids as $id){
                   $rs = $reps->findOneById(new MongoId($id));
                   $rs -> setStateCheck(1);
                   $rs -> save();
               }
               $this->getUser()->setFlash("notice",'操作成功!');
           }
       }
       $this->redirect($request->getReferer());
    } 
	/**
	 * ADI内容纠错
	 * @author lifucang
	 * @date 2013-08-15
	 */
    public function executeImportError(sfWebRequest $request)
    {
       if($request->isMethod("POST")){
           $error = $request->getParameter('error',0);
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0){
               $this->getUser()->setFlash("error",'操作失败！请选择有错误的记录！');
           }else{
               $mongo = $this->getMondongo();
               $reps = $mongo->getRepository("ContentImport");
               foreach($ids as $id){
                   $rs = $reps->findOneById(new MongoId($id));
                   $rs -> setStateError($error);
                   $rs -> setStateCheck(1);
                   $rs -> save();
               }
               $this->getUser()->setFlash("notice",'操作成功!');
           }
       }
       $this->redirect($request->getReferer());
    }
	/**
	 * ADI内容删除
	 * @author lifucang
	 * @date 2013-08-28
	 */
    public function executeImportDel(sfWebRequest $request)
    {
       if($request->isMethod("POST")){
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0){
               $this->getUser()->setFlash("error",'操作失败！请选择要删除的记录！');
           }else{
               $mongo = $this->getMondongo();
               $reps = $mongo->getRepository("ContentImport");
               foreach($ids as $id){
                   $rs = $reps->findOneById(new MongoId($id));
                   $rs -> delete();
               }
               $this->getUser()->setFlash("notice",'操作成功!');
           }
       }
       $this->redirect($request->getReferer());
    }  
    
    public function executeTempDelete(sfWebRequest $request)
    {
      if($request->isMethod("POST"))
      {
        $ids = $request->getParameter('ids');
        $ids =rtrim($ids,',');
        $ids=explode(',',$ids);
        if(count($ids)==0)
        {
          $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
          return $this->renderText(0);
        }else{
          foreach($ids as $id){
            $mongo = $this->getMondongo();
            $contentMongo = $mongo->getRepository("ContentTemp");
            $program = $contentMongo->findOneById(new MongoId($id));
            $program->delete();
          }
          $this->getUser()->setFlash("notice",'删除成功!');
          return $this->renderText(1);
        }
      }
      //$this->redirect($this->generateUrl('',array('module'=>'content','action'=>'import')));
    }
    
    //获取Meta信息
    private function getMetadata($Metadata) {
        $p = array();
        if(isset($Metadata)){
            $p = $this->getAttrs($Metadata->AMS);
            if(isset($Metadata->App_Data)){
                foreach($Metadata->App_Data as $key => $val) {
                    list($name,$value) = $this->getArrayByAttrs($val);
                    if(isset($p[$name])){
                        $p[$name] = $p[$name].','.$value;  //防止有多个重名的情况，例如多个Actors
                    }else{
                        $p[$name] = $value;
                    }
                }  
            }
        }
        return $p;
    }
    //将属性写到数组中
    private function getArrayByAttrs($s) {
        foreach($s->attributes() as $key => $val) {
            if($key == "Name"){
                $Name = (string)$val;
            }
            if($key == "Value"){
                $Value = (string)$val;
            }
        }
        return array($Name,$Value);
    }

    private function getAttrs($s) {
        $arr=array();
        if(isset($s)){
            foreach($s->attributes() as $key => $val) {
               $arr[$key] = (string)$val;
            }  
        }
        return $arr;
    } 
}
