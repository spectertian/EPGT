<?php

/**
 * json actions.
 *
 * @package    epg
 * @subpackage json
 * @author     superwen
 */

class injectActions extends sfActions
{		
    /**
    * Executes index action
    *
    * @param sfRequest $request
    * @author superwen
    * @final 2012-07-09
    */
    public function executeIndex(sfWebRequest $request) 
	{
        if ($request->getMethod() == 'POST') {
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
            file_put_contents("../log/inject.".date("YmdHis").".txt", $jsonstr, FILE_APPEND);
            
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText("{'stats' : 'ok'}");            
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }     
    }
    
    public function executeEpg(sfWebRequest $request) 
	{
         if ($request->getMethod() == 'POST') {
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
            file_put_contents("../log/inject.".date("YmdHis").".txt",$jsonstr);
            
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText("{'stats' : 'ok'}");            
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }     
    }
    
    /**
     * Executes vod action
     *
     * @param sfRequest $request
     * @author superwen
     * @final 2012-07-09
     */
    public function executeVod(sfWebRequest $request) 
	{
        if ($request->getMethod() == 'POST') { 
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
            //$mongo = $this->getMondongo();
            $inject = new ContentInject();
            $inject->setFrom("cms");
            $inject->setState(0);
            $inject->setContent($jsonstr);            
            
            $this->getResponse()->setContentType('text/plain');
            if($inject->save()){						
                return $this->renderText("{'stats' : 'ok'}");
            }else{
                return $this->renderText("{'stats' : 'error'}");
            }
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }      
    }

    /**
     * Executes vod action
     *
     * @param sfRequest $request
     * @author superwen
     * @final 2012-07-09
     */
    public function executeTest(sfWebRequest $request) 
	{
        if ($request->getMethod() == 'POST') { 
			$jsonstr = $request->getPostParameter('xmlString');
			
            $content = file_get_contents($jsonstr);	
            return $this->renderText($content);
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }      
    }  
}    
?>
