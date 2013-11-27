<?php

/**
 * tvsoumatch_wiki actions.
 *
 * @package    epg2.0
 * @subpackage tvsoumatch_wiki
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tvsoumatch_wikiActions extends sfActions
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
			return self::$mdb = $mongo->getRepository("TvsouMatchWiki");
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
		$this->tvsouId = $request->getParameter('tvsouid');
		$this->wikiTitle = $request->getParameter('wikititle');
		$this->compare = $request->getParameter('compare');
		
      	
    	$this->pageTitle = 'Tvsou匹配维基列表';
	    $this->pager = new sfMondongoPager('TvsouMatchWiki', 20);
	    
	    $querys=array();
		$sort=array('updated_at' => -1,'created_at' => -1);
 		if($this->tvsouId != '')
 		{
 			$querys['tvsou_id'] = $this->tvsouId;
 		}
    	if($this->wikiTitle != '')
 		{
 			$wikiTitle = "/.*".$this->wikiTitle.".*/i";
			$wikiTitle = new MongoRegex($wikiTitle);
 			$querys['wiki_title'] = $wikiTitle;
 		}
		if($this->compare == 2)
		{
			$querys['compare'] = true;
		}
		if($this->compare == 3)
		{
			$querys['compare'] = false;
		}
		$this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
	    $this->pager->setPage($request->getParameter('page', 1));
	    $this->pager->init();
	}
	
	public function executeEdit(sfWebRequest $request)
	{
		$this->id = strval($request->getParameter('id'));
		$this->tvsoumatch_wikis = self::getMdb()->findOneByID(new MongoId($this->id));
      	if(!$this->tvsoumatch_wikis)  $this->redirect($request->getReferer());
      	$this->tvsouTitle = $this->tvsoumatch_wikis->getTvsouTitle();
      	$this->pageTitle = '编辑 :'.$this->tvsoumatch_wikis->getTvsouId();
      	
      	
        					
		if($request->isMethod("POST"))
		{
			$userId = $this->context->getUser()->getAttribute('adminid');
			$userName = $this->context->getUser()->getAttribute('username');
			$author = array('user_id'=>$userId,'user_name'=>$userName);
			$wikiId= $request->getPostParameter('wiki_id');
			$wikiTitle= $request->getPostParameter('wiki_title');

			$this->tvsoumatch_wikis->setWikiId($wikiId);
			$this->tvsoumatch_wikis->setWikiTitle($wikiTitle);
			$this->tvsoumatch_wikis->setAuthor($author);
			if($this->tvsouTitle == $wikiTitle)
			{
				$this->tvsoumatch_wikis->setCompare(true);
			}else{
				$this->tvsoumatch_wikis->setCompare(false);
			}
			$this->tvsoumatch_wikis->save();
			
			$this->getUser()->setFlash('notice', '修改所选项成功');
		}
	}
	
	public function executeAdd(sfWebRequest $request)
	{
		$this->pageTitle = "添加tv搜数据";				
		if($request->isMethod("POST"))
		{
			$this->tvsouId = strval($request->getParameter('tvsou_id'));
			$this->tvsoumatch_wikis = self::getMdb()->findOne(array('query' => array('tvsou_id'=>$this->tvsouId)));
			if($this->tvsoumatch_wikis || empty($this->tvsouId))
			{
				$this->getUser()->setFlash("error","tvos_id : {$this->tvsouId} 重复或者为空，添加失败!");
				$this->redirect($request->getReferer());	
			}
			$this->tvsoumatchwikis = new TvsouMatchWiki();
			$userId = $this->context->getUser()->getAttribute('adminid');
			$userName = $this->context->getUser()->getAttribute('username');
			$author = array('user_id'=>$userId,'user_name'=>$userName);
			$wikiId= $request->getPostParameter('wiki_id');
			$wikiTitle= $request->getPostParameter('wiki_title');
			
			$this->tvsouTitle = Common::getTvsouTitleByID($this->tvsouId);
			
			$this->tvsoumatchwikis->setTvsouId($this->tvsouId);
			$this->tvsoumatchwikis->setTvsouTitle($this->tvsouTitle);
			$this->tvsoumatchwikis->setWikiId($wikiId);
			$this->tvsoumatchwikis->setWikiTitle($wikiTitle);
			$this->tvsoumatchwikis->setAuthor($author);
			
			if($this->tvsouTitle == $wikiTitle)
			{
				$this->tvsoumatchwikis->setCompare(true);
			}else{
				$this->tvsoumatchwikis->setCompare(false);
			}
			$this->tvsoumatchwikis->save();
			
			$this->getUser()->setFlash("notice",'添加成功!');
			$this->redirect($this->generateUrl('',array('module'=>'tvsoumatch_wiki','action'=>'index')));
		}
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
	
	//删除多个数据
	public function executeBatchDelete(sfWebRequest $request)
	{
		$ids = $request->getParameter('id');
		foreach($ids as $v)
		{
			$this->ad = self::getMdb()->findOneByID(new MongoId($v));
			if($this->ad)
			{
				$this->ad->delete();
			}
		}
		$this->getUser()->setFlash("notice",'删除成功!');
		$this->redirect($this->generateUrl('',array('module'=>'tvsoumatch_wiki','action'=>'index')));
	}
	
	//删除单个数据
	public function executeDelete(sfWebRequest $request)
	{
		$id = strval($request->getParameter('id'));
		$this->ad = self::getMdb()->findOneByID(new MongoId($id));
		if($this->ad)
		{
			if(!$this->ad->delete())
				$this->getUser()->setFlash("notice",'删除成功!');
			else
				$this->getUser()->setFlash("error",'删除失败!');
			}else{
				$this->getUser()->setFlash("error",'该记录不存在!');
				$this->forwardUnless($this->ad, 'tvsoumatch_wiki', 'index');
		}
		$this->redirect($this->generateUrl('',array('module'=>'tvsoumatch_wiki','action'=>'index')));
	}
}
