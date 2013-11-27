<?php

/**
 * videos_zhui actions.
 *
 * @package    epg2.0
 * @subpackage videos_zhui
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class videos_zhuiActions extends sfActions
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
			return self::$mdb = $mongo->getRepository("VideosZhui");
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
		$this->wikiId = $request->getParameter('wikiid');
		$this->wikiName = $request->getParameter('wikiname');
		$this->state = $request->getParameter('state');
		
      	
    	$this->pageTitle = '追剧';
	    $this->pager = new sfMondongoPager('VideosZhui', 20);
	    
	    $querys=array();
		$sort=array('updated_at' => -1,'created_at' => -1);
 		if($this->wikiId != '')
 		{
 			$querys['wiki_id'] = $this->wikiId;
 		}
    	if($this->wikiName != '')
 		{
 			$wikiName = "/.*".$this->wikiName.".*/i";
			$wikiName = new MongoRegex($wikiName);
 			$querys['wiki_name'] = $wikiName;
 		}
		if($this->state == 1)
		{
			$querys['state'] = array('$in' => array(0,1));
		}
		if($this->state == 2)
		{
			$querys['state'] = 2;
		}
		$this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
	    $this->pager->setPage($request->getParameter('page', 1));
	    $this->pager->init();
	}
	
	public function executeEdit(sfWebRequest $request)
	{
		$this->id = strval($request->getParameter('id'));
		$this->videos_zhuis = self::getMdb()->findOneByID(new MongoId($this->id));
		$this->videoClass = array(
					'qiyi'=>'奇艺',
				);
      	if(!$this->videos_zhuis)  $this->redirect($request->getReferer());
      	$this->pageTitle = '编辑 :'.$this->videos_zhuis->getWikiName()."--追剧";
      	
      	
        					
		if($request->isMethod("POST"))
		{
			$total = intval($request->getPostParameter('total'));
			$state = intval($request->getPostParameter('state'));
			$qiyiUrl = $request->getPostParameter('qiyi_url');
			$source = array('qiyi'=>array('url'=>$qiyiUrl));
			
			if($total < 1)
			{
				$this->getUser()->setFlash("error","总集数不能小于零，修改失败!");
				$this->redirect($request->getReferer());	
			}
			$this->videos_zhuis->setTotal($total);
			$this->videos_zhuis->setSource($source);
			$this->videos_zhuis->setState($state);
			$this->videos_zhuis->save();
			
			$this->getUser()->setFlash('notice', '修改所选项成功');
		}
	}
	
	public function executeAdd(sfWebRequest $request)
	{
		$this->pageTitle = "添加追剧节目";	
		$this->videoClass = array(
					'qiyi'=>'奇艺',
				);	
		if($request->isMethod("POST"))
		{
			$this->wikiId = strval($request->getParameter('wiki_id'));
			$this->videos_zhuis = self::getMdb()->findOne(array('query' => array('wiki_id'=>$this->wikiId)));
			
			if($this->videos_zhuis || empty($this->wikiId))
			{
				$this->getUser()->setFlash("error","wiki_id : {$this->tvsouId} 重复或者为空，添加失败!");
				$this->redirect($request->getReferer());	
			}
			$this->VideosZhuis = new VideosZhui();
			$wikiName= $request->getPostParameter('wiki_name');
			$total= $request->getPostParameter('total');
			$qiyiUrl= $request->getPostParameter('qiyi');
			if(empty($qiyiUrl))
			{
				$this->getUser()->setFlash("error","抓取地址不能为空，添加失败!");
				$this->redirect($request->getReferer());	
			}
			$source = array('qiyi'=>array('url'=>$qiyiUrl));
			
			$this->VideosZhuis->setWikiId($this->wikiId);
			$this->VideosZhuis->setWikiName($wikiName);
			$this->VideosZhuis->setTotal($total);
			$this->VideosZhuis->setLocal(0);
			$this->VideosZhuis->setState(0);
			$this->VideosZhuis->setSource($source);
			$this->VideosZhuis->save();
			
			$this->getUser()->setFlash("notice",'添加成功!');
			$this->redirect($this->generateUrl('',array('module'=>'videos_zhui','action'=>'index')));
		}
	}
	
	public function executeList(sfWebRequest $request)
	{
		$this->id = strval($request->getParameter('id'));
		$this->videos_zhuis = self::getMdb()->findOneByID(new MongoId($this->id));
		$this->videoClass = array(
					'qiyi'=>'奇艺',
				);
      	if(!$this->videos_zhuis)  $this->redirect($request->getReferer());
      	$this->pageTitle = '状态查看 :'.$this->videos_zhuis->getWikiName()."--追剧";
      	$this->source = $this->videos_zhuis->getSource();
      	$this->total = $this->videos_zhuis->getTotal();
      	$this->createdAt = $this->videos_zhuis->getCreatedAt()->format("Y-m-d H:i:s");
      	
      	
	}

	public function executePublishoff(sfWebRequest $request)
	{
	    $id = $request->getParameter('id');
	    $sm = self::getMdb()->findOneByID(new MongoId($id));
	    if($sm) {
	      $sm->setState(2);
	      $sm->save();
	    }
	    $this->getUser()->setFlash("notice",'暂停抓取成功!');
	    $this->redirect($this->generateUrl('',array('module'=>'videos_zhui','action'=>'index')));
		}

	public function executePublishon(sfWebRequest $request)
	{
	    $id = $request->getParameter('id');
	    $sm = self::getMdb()->findOneByID(new MongoId($id));
	    if($sm) {
	      $sm->setState(1);
	      $sm->save();
	    }
	    $this->getUser()->setFlash("notice",'重新开启抓取成功!');
	    $this->redirect($this->generateUrl('',array('module'=>'videos_zhui','action'=>'index')));
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
			$this->videosZhuis = self::getMdb()->findOneByID(new MongoId($v));
			if($this->videosZhuis)
			{
				$this->videosZhuis->delete();
			}
		}
		$this->getUser()->setFlash("notice",'删除成功!');
		$this->redirect($this->generateUrl('',array('module'=>'videos_zhui','action'=>'index')));
	}
	
	//删除单个数据
	public function executeDelete(sfWebRequest $request)
	{
		$id = strval($request->getParameter('id'));
		$this->videosZhuis = self::getMdb()->findOneByID(new MongoId($id));
		//删除原来的奇艺视频
//		$mongo = $this->getMondongo();
//		$PlayListRepository = $mongo->getRepository('VideoPlaylist');
//		$PlayListRepository->deleteVideos((string) $wiki->getId(), 'qiyi');
		if($this->videosZhuis)
		{
			if(!$this->videosZhuis->delete())
				$this->getUser()->setFlash("notice",'删除成功!');
			else
				$this->getUser()->setFlash("error",'删除失败!');
			}else{
				$this->getUser()->setFlash("error",'该记录不存在!');
				$this->forwardUnless($this->videosZhuis, 'videos_zhui', 'index');
		}
		$this->redirect($this->generateUrl('',array('module'=>'videos_zhui','action'=>'index')));
	}
}

