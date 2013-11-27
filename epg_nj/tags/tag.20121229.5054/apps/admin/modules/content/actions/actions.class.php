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
        
        $this->query = array('query'=> array(),'sort' => array('_id' => -1));
        $this->injects = new sfMondongoPager('ContentInject', 20);
        $this->injects->setFindOptions($this->query);
        $this->injects->setPage($page);
        $this->injects->init();
        $this->pageTitle    = '内容导入';        
    }
    
    public function executeImport(sfWebRequest $request) {
        $deletedstatus = intval($request->getParameter('deleted', 0));
        $page  = $request->getParameter('page', 1);
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
        
        if($deletedstatus==1){//传参查看已删除数据。state = -1
          $query['state']  = -1;
          $this->pageTitle = '已删除内容处理';
          $this->query     = array('query'=> $query,'sort' => array('_id' => -1));
          $this->imports   = new sfMondongoPager('ContentImport', 20);
          $this->imports->setFindOptions($this->query);
          $this->imports->setPage($page);
          $this->imports->init();
          $this->deleted = 1;
          $this->setTemplate("deleted");
        }else{
          $query['state']  = array('$ne'=>-1);
          $this->query   = array('query'=> $query,'sort' => array('_id' => -1));
          $this->imports = new sfMondongoPager('ContentImport', 20);
          $this->imports->setFindOptions($this->query);
          $this->imports->setPage($page);
          $this->imports->init();
          $this->deleted = 0;
          $this->pageTitle = '内容处理';
        }
        
        
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
    			$content->setWikiId($wikiId);
    			$content->save();
    			$this->getUser()->setFlash("notice",'保存成功!');
    			$return_status['contentId'] = (string)$content->getId();
    		}
    		return $this->renderText(json_encode($return_status));
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
             print_r($reduction);
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
    
}
