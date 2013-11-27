<?php
/**
 * 回看EPG actions.
 *
 * @package    epg2.0
 * @subpackage cpg
 * @author     superwen
 * @modify     2012-12-20 
 */
class cpgActions extends sfActions
{
    /**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
		return $this->renderText("...");
	}
    
	/**
	 * Executes show action
	 * @author superwen
	 */
	public function executeShow(sfWebRequest $request)
	{
        $clientid = $request->getParameter('clientid',"01006608470056014");
        $playtype = $request->getparameter('playtype',0);
        $contented = $request->getparameter('contented');
        $backurl = $request->getReferer() ? $request->getReferer() : sfConfig::get("app_base_url");
        if(!$contented) {
            return $this->renderText("参数错误！");            
        }        
        $submit_url = sfConfig::get("app_cpg_portal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=n&contented=".$contented."&backurl=".urlencode($backurl); 
        $curl = curl_init();          
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_USERPWD, sfConfig::get("app_cpg_portal_username").":".sfConfig::get("app_cpg_portal_password")); 
        curl_setopt($curl, CURLOPT_URL, $submit_url); 
        $data = curl_exec($curl);
        curl_close($curl); 
        $xmls = simplexml_load_string($data);
        if(isset($xmls->url)) {
            //return $this->renderText($xmls->url);
            $this->redirect(strval($xmls->url));
            //header("Location: ".strval($xmls->url));
            exit;
        }else{
            return $this->renderText("接口错误！");
        }
	}	
}
