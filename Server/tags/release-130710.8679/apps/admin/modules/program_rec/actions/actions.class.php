<?php

/**
 * program_rec actions.
 *
 * @package    epg2.0
 * @subpackage program_rec
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class program_recActions extends sfActions
{
	
	/**
	* Executes getMdb 
	*
	* @param sfRequest $request A request object
	* @author tianzhongsheng-ex@huan.tv
	*/
	public function getMdb($dateBase = 'ProgramRec')
	{

		$mongo = $this->getMondongo();
		return  $mongo->getRepository($dateBase);

	}
	
	/**
	 * @desc 删除指定memcache的key
	 * @author tianzhongsheng
	 * @time 2013-07-02 16:04:00
	 */
	public function delCache($key)
	{
		if(!$key) return false;
		$memcache = tvCache::getInstance();
		$status = $memcache->delete($key);
		return $status;
	}
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	* @author tianzhongsheng-ex@huan.tv
	*/
	public function executeIndex(sfWebRequest $request)
	{
		$this->timeConfig = sfConfig::get("app_rec_time_area");
		$this->timeBlockConfig = sfConfig::get("app_rec_time_block");
		
		// 获取所有的频道名称 tianzhongsheng 2013-06-19 11:34:00
		$this->channelNmaes = $channelNames = Doctrine::getTable('Channel')->getAllChannelsNames( );


		$this->date = strval($request->getParameter('date',date('Y-m-d')));
		$this->name = strval($request->getParameter('name'));
		$this->timeArea = intval($request->getParameter('timeArea'));
		$this->timeSon = intval($request->getParameter('timeSon','0'));
		$this->pageTitle = ' 节目直播:'.$this->date;
		
		// 默认设置为当前时间
		if(!isset($_GET['timeArea']))
		{
			
			foreach($this->timeConfig as $k => $v)
			{
				$reArr = explode('-',$v);
				if(time() >= strtotime($reArr['0']) && time() <= strtotime($reArr['1']))
				{
					$this->timeArea = $k;
					
				}
			}
			
			
			foreach($this->timeBlockConfig[$this->timeArea] as $k => $v)
			{
				$reArr = explode('-',$v);
				if(time() >= strtotime($reArr['0']) && time() <= strtotime($reArr['1']))
				{
					$this->timeSon = $k;
					
				}
			}
			
		}
		
		
		$timeSonArray = explode('-',$this->timeBlockConfig[$this->timeArea][$this->timeSon]);
 		$this->startTime = $timeSonArray['0'];
 		$this->EndTime = $timeSonArray['1'];
 		
		$this->programSql = self::getMdb('Program')->getProgramsByCodeByDateSql($this->name,$this->startTime, $this->EndTime,$this->date);
 		$this->programRecs = self::getMdb( )->find(array('query' => array("date"=>$this->date), 'sort' => array("start_time" => 1)));
 		
	    $this->pager = new sfMondongoPager('Program', 10);
	    $this->pager->setFindOptions($this->programSql);
	    $this->pager->setPage($request->getParameter('page', 1));
	    $this->pager->init();
 	

	}
	//	保存数据
	public function executeSave(sfWebRequest $request)
    {
		if($request->isMethod("GET"))
		{
	    	$this->id = strval($request->getParameter('id'));
	    	$this->timeArea = intval($request->getParameter('timeArea'));
			$this->programs = self::getMdb('Program')->findOneByID(new MongoId($this->id));
			if(!$this->programs)
			{
				$this->getUser()->setFlash("error",'节目不存在!');
				$this->redirect($request->getReferer());
			}
			
	    	$userId = $this->context->getUser()->getAttribute('adminid');
			$userName = $this->context->getUser()->getAttribute('username');
			$author = array('user_id'=>$userId,'user_name'=>$userName);
			//节目直播
			$programRecs = new ProgramRec();
			$programRecs->setName($this->programs->getName());
			$programRecs->setChannelCode($this->programs->getChannelCode());
			$programRecs->setTags($this->programs->getTags());
			$programRecs->setStartTime($this->programs->getStartTime());
			$programRecs->setEndTime($this->programs->getEndTime());
			$programRecs->setDate($this->programs->getDate());
			$programRecs->setWikiId($this->programs->getWikiId());
			$programRecs->setTimeArea($this->timeArea);
//			$programRecs->setEpisode( );
			$programRecs->setAuthor($author);
			$programRecs->setSort($this->programs->getSort());
			$programRecs->save();
			
			// delete memcache 
			$key = 'Programrec_'.$this->programs->getDate();	//节目直播推荐
			self::delCache($key);


			$this->getUser()->setFlash("notice",'保存成功!');
			$this->redirect($request->getReferer());
				
		}
		$this->getUser()->setFlash("error",'保存不成功!');
		$this->redirect($request->getReferer());
    }
    
	/**
	 * Executes delete action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeDelete(sfWebRequest $request)
	{
		$id = strval($request->getParameter('id'));
		$this->programRecs = self::getMdb( )->findOneByID(new MongoId($id));
		
		// delete memcache 
		$key = 'Programrec_'.$this->programRecs->getDate().'_'.$this->programRecs->getTimeArea();	//节目直播推荐
		self::delCache($key);
			
		if($this->programRecs)
		{
			if(!$this->programRecs->delete())
        		$this->getUser()->setFlash("notice",'删除成功!');
			else
				$this->getUser()->setFlash("error",'删除失败!');
		}else{
			$this->getUser()->setFlash("error",'该记录不存在!');
			$this->redirect($request->getReferer());
		}

		$this->redirect($request->getReferer());
	}
}
