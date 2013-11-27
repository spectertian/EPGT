<?php

/**
 * api actions.
 *
 * @package    epg
 * @subpackage api
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
     * @author ward
     * @final 2010-08-31 11:04
    */
    public function executeIndex(sfWebRequest $request) {
        $this->getResponse()->setContentType('text/plain');
        return $this->renderText('XML-RPC server accepts POST requests only.');
    }

    /*
     * 欢网接口入口
     * @param sfWebReqeust $request
     * @author guoqiang.zhang
     */
     public function executeInterface(sfWebRequest $request){
        //$HTTP_RAW_POST_DATA = file_get_contents('php://input');  
        if ($request->getMethod() == 'POST') {
            //$data = simplexml_load_string($post);                  
            $post = $request->getPostParameter("xmlString");
            $this->getResponse()->setContentType('text/xml');
	        $xml = new Simple($post);
            return $this->renderText($xml->response);
        }else{
           
        }
     }
}    
?>
