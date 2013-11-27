<?php
/**
 * inject actions.
 *
 * @package    epg2.0
 * @subpackage inject
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class injectActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        
    }
  
   /**
    * 接收content_inject数据
    * @author lifucang 2013-06-04
    */
    public function executeInject(sfWebRequest $request)
    {
        if ($request->getMethod() == 'POST') { 
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
    		$result = json_decode($jsonstr,true); 
            $injects=$result['injects']?$result['injects']:array();
            if(count($injects)>0){
                $mongo = $this->getMondongo();
                foreach($injects as $inject){
                       $injectExists = $mongo->getRepository("ContentInject")->findOneById(new MongoId($inject['id']));
                       if(!$injectExists){
                            $injectLocal = new ContentInjectNew();
                            $injectLocal -> setId(new MongoId($inject['id']));
                            $injectLocal -> setContent($inject['content']);
                            $injectLocal -> setFrom($inject['from']);
                            $injectLocal -> setState($inject['state']);
                            $injectLocal -> save();
                       }
                }    
                return $this->renderText('ok');  
            }else{
                return $this->renderText('false');  
            }
        }
    }
    
   /**
    * 接收content_import数据
    * @author lifucang 2013-06-04
    */
    public function executeImport(sfWebRequest $request)
    {
        if ($request->getMethod() == 'POST') { 
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
    		$result = json_decode($jsonstr,true); 
            $imports=$result['imports']?$result['imports']:array(); 
            if(count($imports)>0){
                $mongo = $this->getMondongo();
                foreach($imports as $import){
                       $importExists = $mongo->getRepository("ContentImport")->findOneById(new MongoId($import['id']));
                       if(!$importExists){
                            $importLocal = new ContentImportNew();
                            $importLocal -> setId(new MongoId($import['id']));
                            $importLocal -> setInjectId($import['inject_id']);
                            $importLocal -> setFrom($import['from']);
                            $importLocal -> setFromId($import['from_id']);
                            $importLocal -> setFromTitle($import['from_title']);
                            $importLocal -> setProviderId($import['provider_id']);
                            $importLocal -> setFromType($import['from_type']);
                            $importLocal -> setState($import['state']);
                            $importLocal -> setChildrenId($import['children_id']);
                            if($import['wiki_id'])
                                $importLocal -> setWikiId($import['wiki_id']);
                            $importLocal -> setStateEdit($import['state_edit']);
                            $importLocal -> save();
                       }
                }
                return $this->renderText('ok');   
            }else{
                return $this->renderText('false');  
            }
        }
    }  
}
