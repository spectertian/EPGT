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
        //$memcache = tvCache::getInstance(); 
        //$this->wikis=$memcache->get("theme_wikis_".$this->tid);
        //if(!$this->wikis){
            $this->wikis=Doctrine_Core::getTable('ThemeItem')->getWikis($this->tid,1,100);//取所有wiki
            //$memcache->set("theme_wikis_".$this->tid,$this->wikis,3600*24);  //24小时
        //}
        $this->wikiTop=$this->wikis[0];
    }    
}
