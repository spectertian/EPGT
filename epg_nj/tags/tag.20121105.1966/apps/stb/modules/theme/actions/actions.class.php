<?php
/**
 * theme actions.
 * @package    epg2.0
 * @subpackage list
 * @author     Huan lifucang
 * @version    1.0
 */
class themeActions extends sfActions
{
    /**
    * Executes index action
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        
    }
    public function executeShow(sfWebRequest $request)
    {
        $this->tid = $request->getParameter("tid");
        $this->page = $request->getParameter("page", 1);
        $this->wikis=Doctrine_Core::getTable('ThemeItem')->getWikis($this->tid,1,9999);//取所有wiki
        $this->wikiTop=$this->wikis[0];
    }    
}
