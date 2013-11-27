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
        $page    = $request->getParameter('page', 1);
        
        $this->query = array('query'=> array(),'sort' => array('_id' => -1));
        $this->imports = new sfMondongoPager('ContentImport', 20);
        $this->imports->setFindOptions($this->query);
        $this->imports->setPage($page);
        $this->imports->init();
        $this->pageTitle    = '内容处理'; 
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
    
  	public function executeImportDelete(sfWebRequest $request)
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
                   $contentMongo = $mongo->getRepository("ContentImport");
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
