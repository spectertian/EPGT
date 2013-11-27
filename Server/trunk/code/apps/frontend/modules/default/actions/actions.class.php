<?php
/**
 * default actions.
 *
 * @package    epg
 * @subpackage default
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {    
        $mongo = $this->getMondongo();
        $wikiPlayRepos = $mongo->getRepository('WikiPlay');      
        $wiki_repository = $mongo->getRepository('Wiki');
        $recommend_repository = $mongo->getRepository('Recommend');
        $this->recommends = $recommend_repository->getRecommendByScene('index', 20);
      
        $this->allProvince = Province::getProvince();
        if (null == $this->location || !in_array($this->location, $this->allProvince)) {
            $this->location = $this->allProvince[$this->getUser()->getUserProvince()];
        }
        $this->datestamp = date('Y-m-d', time());
        $this->wikiplays = $wikiPlayRepos->getWikiPlays('all', $this->datestamp, $this->location, 0,32);
    }

    public function executeYingshi(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Page');
        $this->page = $repository->getNewestPageByName('影视');
    }

    public function executeZongyi(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Page');
        $this->page = $repository->getNewestPageByName('综艺');
    }

    /**
    * 社科频道面面
    * @param sfWebRequest $request 
    */
    public function executeSheke(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Page');
        $this->page = $repository->getNewestPageByName('社科');
    }
  
    /**
    * 404 错误页面
    */
    public function executeError404(sfWebRequest $request) {
      
    }
    
    /**
    * 500 错误页面
    */
    public function executeError500(sfWebRequest $request) {
      
    }
}
