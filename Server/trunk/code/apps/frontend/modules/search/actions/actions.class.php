<?php

/**
 * search actions.
 *
 * @package    epg
 * @subpackage search
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
 
        $this->page = $request->getParameter("page", 1);
        $this->style = $request->getParameter("style","list");
        $this->q = $request->getParameter("q");
        
        if(!$this->q){
            $this->forward("search", "info");
        }
        $this->sort = $request->getParameter("sort",1);
        $this->gcurrent = $request->getParameter("gcurrent","0");
        $this->getResponse()->setTitle($this->q.' - 搜索');
        $mogo = $this->getMondongo();
        $wiki = $mogo->getRepository("wiki");
        $this->type = array('0'=>'','1'=>"type:video",'2'=>"type:television",'3'=>"type:actor");
        $this->wikimodel = array('actor'=>"艺人","television"=>"栏目","film"=>"电影","teleplay"=>"电视剧");
        $this->count=array();
        $this->searchGroup = array();
        foreach($this->type as $key =>$value){
            $this->count[$key] = $wiki->getXunSearchCount($this->q." ".$this->type[$key]);
        }

        $this->wiki_pager = new XapianPager("Wiki", 12);
        $this->wiki_pager->setSearchText($this->q." ".$this->type[$this->gcurrent]);
        $this->wiki_pager->setWeightKey($this->q);
        $this->wiki_pager->setSort((int)$this->sort);
        $this->wiki_pager->setPage($this->page);
        $this->wiki_pager->init();
    }

    /**
     * 搜索查询
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeSearch(sfWebRequest $request) {
        $page = $request->getParameter("page", 1);
//        $limit = 20;
//        $offset = ($page - 1) * $limit;
        
        $this->q = $request->getParameter("q");
        $this->getResponse()->setTitle($this->q.' - 搜索');

        $this->wiki_pager = new XapianPager("Wiki", 10);
        $this->wiki_pager->setSearchText($this->q);
        $this->wiki_pager->setPage($page);
        $this->wiki_pager->init();
        
//        $mongo = $this->getMondongo();
//        $wiki_repo = $mongo->getRepository("Wiki");
//        $this->total = null;
//        $this->wikis = $wiki_repo->search($this->q, $this->total, $offset, $limit);
//
//        $url = sprintf("%s?q=%s&amp;page=", $this->getController()->genUrl("search/search"), $this->q);
//        $this->pager = new Pager($url, $this->total, $limit);
//        $this->pager->SetCurPage($page);
    }

    public function executeInfo(sfWebRequest $request){
        $this->gcurrent = $request->getParameter("gcurrent","0");
        $this->sort = $request->getParameter("sort",1);
        $this->getResponse()->setTitle('搜索');
    }
}
