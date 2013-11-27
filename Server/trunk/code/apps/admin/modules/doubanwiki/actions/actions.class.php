<?php

/**
 * doubanwiki actions.
 *
 * @package    epg2.0
 * @subpackage tvsoumatch_wiki
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class doubanwikiActions extends sfActions
{
	/**
	* Get mongodb handler
	* @return mongo | object
	*/
	public static $mdb = null;
	public function getMdb()
	{
		if(null == self::$mdb)
		{
			$mongo = $this->getMondongo();
			return self::$mdb = $mongo->getRepository("DoubanMovie");
		}else
		{
			return self::$mdb;
    	}
	}
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function executeIndex(sfWebRequest $request)
	{
	    $this->page = $request->getParameter('page', 1);
	    $this->douban_status = $request->getParameter('douban_status','');
	    $this->title = $request->getParameter('title','');
	    if($request->isMethod("POST")){
	        $wiki_id = $request->getParameter('wiki_id');
	        $douban_id = intval($request->getParameter('douban_id'));
	        $query = array('query'=>array('douban_id'=>$douban_id));
	        $doubanMovie = self::getMdb()->findOne($query);
	        if($doubanMovie){
	            $doubanMovie->setWikiId($wiki_id);
	            $doubanMovie->setSynStatus(2);
	            $doubanMovie->save();
	            if($wiki_id){
	                $wiki_mongo = $this->getMondongo()->getRepository("Wiki");
	                $wiki = $wiki_mongo->findOneById(new MongoId($wiki_id));
	                if($wiki){
	                    if($wiki->getDoubanId() != $douban_id){
	                        $wiki->setDoubanId($douban_id);
	                        $wiki->save();
	                    }
	                }
	            }
	        }
	        //$this->redirect($request->getReferer());
	        exit;
	    }
	    
	    
    	$this->pageTitle = '豆瓣匹配维基列表';
	    $this->pager = new sfMondongoPager('DoubanMovie', 20);
	    
	    $querys=array("syn_status" => array('$exists' => true));
		$sort=array('updated_at' => -1,'created_at' => -1);
		if($this->douban_status == '1'){
		    $querys['syn_status'] = 0;
		}elseif($this->douban_status == '2'){
		    $querys['syn_status'] = 1;
		}elseif($this->douban_status == '3'){
		    $querys['syn_status'] = 2;
		}
		if($this->title){
		    $querys['title'] = new MongoRegex("/$this->title/i");
		}
		$this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
	    $this->pager->setPage($this->page);
	    $this->pager->init();
	}
	
	/**
	 * 编辑确认
	 */
	public function executeSave(sfWebRequest $request){
	    $ids = $request->getParameter('checks');
	    if($ids){
	        $ids = substr($ids,0,-1);
	        $idArr = explode(',',$ids);
	        foreach($idArr as $v){
	            $doubanWiki = self::getMdb()->findOneById(new MongoId($v));
	            if($doubanWiki){
	                $doubanWiki->setSynStatus(2);
	                $doubanWiki->save();
	            }
	        }
	    }
	    exit;
	}
	
	/**
     * 按照名称返回wiki
     *
     * @param sfRequest $request A request object
     * @return
     * @todo 给为从xunsearch里面检索
     */
    public function executeLoadWiki(sfWebRequest $request)
    {
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");
        $total = NULL;
        $condition = 'title:"'.$query.'"';
        $this->wikis = $wiki_mongo->xun_search($condition,$total,0,50,null,4);
    }
    
	//删除wiki关联 Modify by tianzhongsheng-ex@huan.tv 2013-06-18 12:26:00
	public function executeBatchDelete(sfWebRequest $request)
	{
		$ids = $request->getParameter('id');
		foreach($ids as $v)
		{
			$this->doubanMovies = self::getMdb()->findOneByID(new MongoId($v));
			if($this->doubanMovies)
			{
				$mongo =  $this->getMondongo();
				$wikiId = $this->doubanMovies->getWikiId();
				if(!$wikiId)
				{
					continue;
				}
				$wiki_mongo = $mongo->getRepository("Wiki");
				$wikis = $wiki_mongo->findOneByID(new MongoId($wikiId));
				$doubanId = $wikis->getDoubanId();
				if($doubanId)
				{
					$doubanId = $wikis->setDoubanId();
					$wikis->save();
				}
				
				$this->doubanMovies->setWikiId();
				$this->doubanMovies->save();
			}
		}
		$this->getUser()->setFlash("notice",'删除wiki关联成功!');
		$this->redirect($this->generateUrl('',array('module'=>'doubanwiki','action'=>'index')));
	}
}
