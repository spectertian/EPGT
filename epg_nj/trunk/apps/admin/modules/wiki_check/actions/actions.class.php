<?php
/**
 * 数据检查.
 *
 * @package    epg
 * @author     superwen
 * @modify     2012-12-19
 */
class wiki_checkActions extends sfActions 
{
    /**
     * 维基列表
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request) {
        $page    = $request->getParameter('page', 1);
        
        $this->q = trim($request->getGetParameter('q', ''));
        $this->c = trim($request->getGetParameter('c', ''));
        $this->m = $request->getGetParameter('m', 'all');
        
        $query_arr=array();
        if($this->q!=''){
            $query_arr['$or']=array(array('title'=>new MongoRegex("/.*$this->q.*/i")),array('alias'=>new MongoRegex("/.*$this->q.*/i")));
        }
        if($this->c!='')
            $query_arr['admin_id']=intval($this->c);
        if($this->m!='all')
            $query_arr['model']=$this->m;
              
        $query = array('query'=>$query_arr,'sort' => array('_id' => -1));

        $this->query=$query;
        $this->pageTitle    = '维基列表' ;
        $this->wiki = new sfMondongoPager('Wiki', 20);
        $this->wiki->setFindOptions($query);

        $this->wiki->setPage($page);
        $this->wiki->init();
        $this->users = Doctrine::getTable('admin')->findAll();
    }
}