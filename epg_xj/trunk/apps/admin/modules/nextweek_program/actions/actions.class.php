<?php

/**
 * nextweek_program actions.
 *
 * @package    epg2.0
 * @subpackage nextweek_program
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class nextweek_programActions extends sfActions
{
 /**
   * Get mongodb handler
   * @return mongo | object
   * @author gaobo
   */
  public static $mdb = null;
  public function getMdb()
  {
    if(null == self::$mdb){
      $mongo = $this->getMondongo();
      return self::$mdb = $mongo->getRepository("NextweekProgram");
    }else{
      return self::$mdb;
    }
  }
  
	/**
	 * @desc 删除指定memcache的key
	 * @author tianzhongsheng
	 * @time 2013-06-26 11:04:00
	 */
	public function delCache($key)
	{
		if(!$key) return false;
		$memcache = tvCache::getInstance();
		$status = $memcache->delete($key);
		return $status;
	}
  
  /**
   * 日期计算函数
   *  $date  要处理的日期 
   *  $step  0=本月   ,正负表示得到本月前后的月份日期  
   * @author gaobo
   */
  private function AssignTabMonth($date,$step){  
		$date= date("Y-m-d",strtotime($step." months",strtotime($date)));//得到处理后的日期（得到前后月份的日期）    
		$u_date = strtotime($date);  
		$days=date("t",$u_date);// 得到结果月份的天数
		//月份第一天的日期  
		$first_date=date("Y-m",$u_date).'-01';  
		for($i=0;$i<$days;$i++){  
			$for_day=date("Y-m-d",strtotime($first_date)+($i*3600*24));  
		}
		//return $days;
		$dateArr = array('start'=>$first_date,'end'=>$for_day,'total'=>$days);
		return  $dateArr;
		//echo date('Y-m-d',strtotime('+1 d',strtotime('2009-07-08')));//日期天数相加函数
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  * @author gaobo
  */
  public function executeIndex(sfWebRequest $request)
  {
      $date = $request->getParameter('date');
      if(!$date){
          $date = date('Y-m-d',time());
      }
      //echo $date;exit;
      $date  = self::AssignTabMonth($date,0);
      $mongo = self::getMdb();
      
      for($i=0;$i<$date['total'];$i++){
          $currentDay = date('Y-m-d',strtotime($date['start'])+$i*86400);
          $yProgram = $mongo->findOne(array('query'=>array('date'=>$currentDay)));
          if($yProgram){
              $array[$i+1][] = $yProgram->getId();
              $array[$i+1][] = date('w',strtotime($currentDay));
              $array[$i+1][] = date('Y-m-d',strtotime($currentDay));
          }else{
              $array[$i+1][] = '';
              $array[$i+1][] = date('w',strtotime($currentDay));
              $array[$i+1][] = date('Y-m-d',strtotime($currentDay));
          }
      }
      $week = date('w',strtotime($date['start']));
      while($week>=1){
          $weekArr[$week][]=date('d',strtotime($date['start'])-($week)*86400);
          $weekArr[$week][]=date('Y-m-d',strtotime($date['start'])-($week)*86400);
          $week = $week-1;
      }
      $this->week = $weekArr;
      $this->currentdate = date('Y-m',strtotime($date['start']));
      $this->pre  = date('Y-m-d',strtotime($date['start'])-86400);
      $this->next = date('Y-m-d',strtotime($date['end'])+86400);
      $this->yProgram = $array;
  }
  
	/**
	* Executes add action
	*
	* @param sfRequest $request A request object
	* @author tianzhongsheng-ex@huan.tv
	*/
	public function executeAdd(sfWebRequest $request)
	{
		// 获取所有的频道名称 tianzhongsheng 2013-06-19 11:34:00
		$this->channelNmaes = $channelNames = Doctrine::getTable('Channel')->getAllChannelsNames( );
		$this->condition = strval($request->getParameter('condition'));
		$this->name = strval($request->getParameter('name'));
		$this->date = strval($request->getParameter('date'));
		$this->pageTitle = '下周预告-->节目添加:'.$this->date;
		
		//下周预告的style下拉列表框设置 
		//tianzhongsheng-ex@huan.tv 2013-06-20 10:26:00
		$this->style = array(
        						'230*350' => '230*350',
        						'470*350' => '470*350'
        					);
		
		$querys=array();
		$sort=array('start_time' => 1);
		
		//频道
		if($this->condition == 'channel')
		{
			$name = $this->name;
			$channels = Doctrine::getTable('Channel')->search($name);
			$channel_codes = array();
			foreach($channels as $k => $ids)
			{
				$channel_codes[] = $ids->getCode();
			}
			$querys['channel_code'] = array('$in' => $channel_codes);

		}
		
		
		
		//节目
 		if($this->condition == 'program')
 		{
			$name = "/.*".$this->name.".*/i";
			$name = new MongoRegex($name);
 			$querys['name'] = $name;
 		}
 		
 		if($this->date != '')
 		{
 			$querys['date'] = $this->date;
 		}
 		
 		$querys['channel_type'] = array('$in'=>array('cctv','tv'));	//只搜索央视和卫视的 modify  by tianzhongsheng  2013-07-10 14:27:00

 		if(!$this->name || ($this->condition == 'channel' && count($querys['channel_code']) <1 ))
 		{
			$this->isPager = false;
 		}else {
 			$this->isPager = true;
		    $this->pager = new sfMondongoPager('Program', 20);
		    $this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
		    $this->pager->setPage($request->getParameter('page', 1));
		    $this->pager->init();
 		}

	}
	
	/**
	* Executes edit action
	*
	* @param sfRequest $request A request object
	* @author tianzhongsheng-ex@huan.tv
	*/
	public function executeEdit(sfWebRequest $request)
	{
		$this->id = strval($request->getParameter('id'));
		$this->nextweek_programs = self::getMdb()->findOneByID(new MongoId($this->id));
      	if(!$this->nextweek_programs)  $this->redirect($request->getReferer());
      	$this->pageTitle = '编辑 :'.$this->nextweek_programs->getProgramName();
      	$date = $this->nextweek_programs->getDate();
      	
      	//下周预告的style下拉列表框设置 
		//tianzhongsheng-ex@huan.tv 2013-06-20 10:26:00
		$this->style = array(
        						'230*350' => '230*350',
        						'470*350' => '470*350'
        					);
        					
		if($request->isMethod("POST"))
		{
			$program_name= $request->getPostParameter('program_name');
			$aspect = $request->getPostParameter('aspect');
			$poster = $request->getPostParameter('poster');
			$state = $request->getPostParameter('state');
			$sort = $request->getPostParameter('sort');
			$style = $request->getPostParameter('style');
			$start_time = $request->getPostParameter('start_time');
			$dateTime = $date.' '.$start_time;
			
			$this->nextweek_programs->setProgramName($program_name);
			$this->nextweek_programs->setAspect($aspect);
			$this->nextweek_programs->setPoster($poster);
			$this->nextweek_programs->setSort($sort);
			$this->nextweek_programs->setStyle($style);
			$this->nextweek_programs->setStartTime(new DateTime($dateTime));
			if($state == '1')
			{
				$this->nextweek_programs->setState(true);
			}else {
				$this->nextweek_programs->setState(false);
			}
			$this->nextweek_programs->save();
			
			// delete memcache 
			$key = 'nextweek'.$date;	//下周预告
			self::delCache($key);
			
			$this->getUser()->setFlash('notice', '修改所选项成功');
		}
	}
  
	/**
	* Executes list action
	*
	* @param sfRequest $request A request object
	* @author tianzhongsheng-ex@huan.tv
	*/
	public function executeList(sfWebRequest $request)
	{
		$this->date = strval($request->getParameter('date'));
		$this->channelNmaes = $channelNames = Doctrine::getTable('Channel')->getAllChannelsNames( );
		$this->PageTitle = '下周预告 : '.$this->date;
		
		$this->nextWeekPrograms = self::getMdb()->getDatePrograms($this->date);
		foreach($this->nextWeekPrograms  as $k=>$v)
		{
			$ids = $v->getId()."||".$ids;
		}
		$this->ids = $ids;

	}
	/**
   * Executes delete action
   *
   * @param sfRequest $request A request object
   */
	public function executeDelete(sfWebRequest $request)
	{
		$id = strval($request->getParameter('id'));
		$this->nextweek_programs = self::getMdb()->findOneByID(new MongoId($id));
		
		// delete memcache 
		$date = $this->nextweek_programs->getDate();
		$key = 'nextweek'.$date;	//下周预告
		self::delCache($key);
			
		if($this->nextweek_programs)
		{
			if(!$this->nextweek_programs->delete())
        		$this->getUser()->setFlash("notice",'删除成功!');
			else
				$this->getUser()->setFlash("error",'删除失败!');
		}else{
			$this->getUser()->setFlash("error",'该记录不存在!');
			$this->redirect($request->getReferer());
		}

		$this->redirect($request->getReferer());
	}
  
  /**
   * Executes batchdelete action
   *
   * @param sfRequest $request A request object
   */
	public function executeBatchDelete(sfWebRequest $request)
	{
		$ids = $request->getParameter('id');
		foreach($ids as $v)
		{
			$this->nextweek_programs = self::getMdb()->findOneByID(new MongoId($v));
			if($this->nextweek_programs)
			{
				$date = $this->nextweek_programs->getDate();
				$this->nextweek_programs->delete();	
			}
		}
		
		// delete memcache 
		$key = 'nextweek'.$date;	//下周预告
		self::delCache($key);
		
		$this->getUser()->setFlash("notice",'删除成功!');
		$this->redirect($request->getReferer());
	}
	//另存为
	public function executeOtherSave(sfWebRequest $request)
    {
		if($request->isMethod("POST"))
		{
	    	$this->id = strval($request->getParameter('id'));
	    	$this->date = strval($request->getParameter('myDate'));
			if(!$this->date || !$this->id)
			{
				$this->getUser()->setFlash("error",'节目不存在!');
				$this->redirect($request->getReferer());
			}
			$ids = rtrim($this->id,"||");
			$ids = explode("||",$ids );
			foreach($ids as $k => $v)
			{
				$this->nextweek_programs = self::getMdb()->findOneByID(new MongoId($v));
				$nextweekPrograms = new NextweekProgram();
				
				$startTime = $this->date.' '.$this->nextweek_programs->getStartTime()->format("H:i");
				$endTime = $this->date.' '.$this->nextweek_programs->getEndTime()->format("H:i");
				
				$nextweekPrograms->setProgramName($this->nextweek_programs->getProgramName());
				$nextweekPrograms->setChannelCode($this->nextweek_programs->getChannelCode());
				$nextweekPrograms->setDate($this->date);
				$nextweekPrograms->setStartTime(new DateTime($startTime));
				$nextweekPrograms->setEndTime(new DateTime($endTime));
				$nextweekPrograms->setWikiId($this->nextweek_programs->getWikiId());
				$nextweekPrograms->setTags($this->nextweek_programs->getTags());
				$nextweekPrograms->setAspect($this->nextweek_programs->getAspect());
				$nextweekPrograms->setPoster($this->nextweek_programs->getPoster());
				$nextweekPrograms->setSort($this->nextweek_programs->getSort());
				$nextweekPrograms->setStyle($this->nextweek_programs->getStyle());
				$nextweekPrograms->setAuthor($this->nextweek_programs->getAuthor());
				$nextweekPrograms->setState($this->nextweek_programs->getState());
				$nextweekPrograms->save();
			}
			
			// delete memcache 
			$key = 'nextweek'.$this->date;	//昨日回顾
			self::delCache($key);

			$this->getUser()->setFlash("notice",'另存为成功!');
			$this->redirect("/nextweek_program/list?date=".$this->date);
				
		}
		$this->getUser()->setFlash("error",'另存为不成功!');
		$this->redirect($request->getReferer());
    }
}
