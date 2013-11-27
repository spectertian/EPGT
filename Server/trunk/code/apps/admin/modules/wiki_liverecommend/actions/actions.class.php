<?php

/**
 * wiki_recommend actions.
 *
 * @package    epg
 * @subpackage wiki_recommend
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wiki_liverecommendActions extends sfActions
{
/**
  * 推荐维基列表
  * @author luren
  */
	public function executeIndex(sfWebRequest $request)
	{
        $this->m = $request->getGetParameter('m', 'all');
        $this->j = $request->getGetParameter('j', 'all');
        $this->pageTitle = '直播维基推荐列表';
        $this->pager = new sfMondongoPager('WikiLiveRecommend', 20);
        $this->pager->setFindOptions(array('query' => array(), 'sort' => array('created_at' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
	}

	/**
	* 批量删除已推荐维基
	*  @author luren
	*/
	public function executeBatch(sfWebRequest $request)
	{

		$ids = $request->getParameter('id');
		if(count($ids) > 0)
		{
			$mongo = $this->getMondongo();
			$repository = $mongo->getRepository('WikiLiverecommend');
			foreach ($ids as $id)
			{
				$wikirecommend = $repository->findOneById(new MongoId($id));
				if (!is_null($wikirecommend))
				{
					$wikirecommend->delete();
				}
			}
			$this->getUser()->setFlash('notice', '已删除选择项！');
		} else {
			$this->getUser()->setFlash('error', '请选择操作项目！');
		}

		$this->redirect('wiki_liverecommend');
	}

	/**
	*
	* @param sfWebRequest $request
	* @author    ly
	* @date      2011-06-21
	*/
	public function executeDelete(sfWebRequest $request)
	{
		$id = $request->getParameter('id');
		$mongo = $this->getMondongo();
		$repository = $mongo->getRepository('WikiLiverecommend');
		$wikirecommend = $repository->findOneById(new MongoId($id));
		if (!is_null($wikirecommend))
		{
			$wikirecommend->delete();
		}
		$this->getUser()->setFlash('notice', '已取消选择项！');
		$this->redirect('wiki_liverecommend');
	}
	/**
     * @date 2013-07-31 16:45
     * @param sfWebRequest $request
     * @author tianzhongsheng-ex@huan.tv
     */
    public function  executeLiverecommend(sfWebRequest $request) 
    {
		$id = $request->getParameter('id');
//		echo $id;exit;
        $mongo = $this->getMondongo();
        $wikiLiverecommends = $mongo->getRepository('WikiLiverecommend');
		if (! $wikiLiverecommends->findOne(array('query' => array('wiki_id'=> $id))))
		{
			$wikiLiveRecommend = new WikiLiverecommend();
			$wikiLiveRecommend->setWikiId($id);
			$wikiLiveRecommend->save();
			$this->getUser()->setFlash('notice', '直播维基推荐成功!');
		}else{
			$this->getUser()->setFlash('error', '该维基已经被推荐！');
		}
        $this->redirect($request->getReferer());
	}

}
