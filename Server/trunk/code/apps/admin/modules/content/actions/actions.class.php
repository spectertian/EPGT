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
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('ContentInject');
        $injects=$repository->findOneById(new MongoId($id));
        if($injects){
            $content = $injects->getContent();
        }
        echo $content;
        exit;  
    }
    public function executeViewImport(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('ContentImport');
        $import=$repository->findOneById(new MongoId($id));
        if($import){
            $content = $import->getChildrenId();
        }
        echo '<pre>';
        print_r($content);
        return sfView::NONE;
    }    
    public function executeImport(sfWebRequest $request) {
        
        $page  = $request->getParameter('page', 1);
        $querys = array();

        $this->wikistatus = intval($request->getParameter('searchwiki', -1));
        $this->line = intval($request->getParameter('line', '-1'));
        $this->check = intval($request->getParameter('check', '-1'));
        $this->error = intval($request->getParameter('error', '-1'));
        if($this->wikistatus==1){
            $querys['wiki_id'] = array('$exists'=>true);
        }elseif($this->wikistatus==2){
            $querys['wiki_id'] = array('$exists'=>false);
        }

        $this->searchtext = $request->getParameter('searchtext', '');
        if($this->searchtext!=''){
          $querys['from_title'] = new MongoRegex("/.*".$this->searchtext.".*/i");
        }
        if($this->line!=-1){
            $querys['state'] = $this->line;
        }
        if($this->check!=-1){
            if($this->check==1){
                $querys['state_check'] = 1;
            }else{
                $querys['state_check'] = array('$ne'=>1);
            }
        }
        if($this->error!=-1){
            if($this->error==1){
                $querys['state_error'] = 1;
            }else{
                $querys['state_error'] = array('$ne'=>1);
            }
        }
        $querys['state']  = array('$ne'=>-1);
        $query   = array('query'=> $querys,'sort' => array('_id' => -1));
        $this->imports = new sfMondongoPager('ContentImport', 20);
        $this->imports->setFindOptions($query);
        $this->imports->setPage($page);
        $this->imports->init();
        $this->pageTitle = '内容处理';
        
        $injects=array();
        foreach ($this->imports->getResults() as $import){
            $inject_content=$import->getInject();
            if($inject_content){
                $content=@simplexml_load_string(trim($inject_content));
                unset($asset_md);
                unset($asset_asset_md);
                $asset_md = $this->getMetadata($content->Asset->Metadata);
                $asset_asset_md = $this->getMetadata($content->Asset->Asset->Metadata);
                $director = isset($asset_asset_md['Director'])?$asset_asset_md['Director']:'';
                $actors = isset($asset_asset_md['Actors'])?$asset_asset_md['Actors']:'';
                $year = isset($asset_md['Year'])?$asset_md['Year']:'';
                $description = isset($asset_md['Description'])?$asset_md['Description']:'';
                $injects[]=array(
                    'Director'=>$director,
                    'Actors'=>$actors,
                    'Year'=>$year,
                    'Description'=>$description,
                );                
            }else{
                $injects[]=array(
                    'Director'=>'',
                    'Actors'=>'',
                    'Year'=>'',
                    'Description'=>'',
                );
            }
        }
        $this->injects=$injects;
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
    			$contentMongo = $mongo->getRepository("ContentImport");
    			$import = $contentMongo->findOneById(new MongoId($id));
    			$import->setWikiId($wikiId);
                $import->setStateMatch(1);  //人工匹配
    			$import->save();
    			$this->getUser()->setFlash("notice",'保存成功!');
    			$return_status['contentId'] = (string)$import->getId();
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
