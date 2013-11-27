<?php

/**
 * default actions.
 *
 * @package    epg2.0
 * @subpackage default
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('default', 'module');
    }
  
    public function executeSecure(sfWebRequest $request)
    {
      $this->url = $request->getReferer();
      $this->setTemplate('secure');
    }
    
    /**
    * 404 ´íÎóÒ³Ãæ
    */
    public function executeError404(sfWebRequest $request) 
    {
      
    }
    
    /**
    * 500 ´íÎóÒ³Ãæ
    */
    public function executeError500(sfWebRequest $request) 
    {
      
    }
}
