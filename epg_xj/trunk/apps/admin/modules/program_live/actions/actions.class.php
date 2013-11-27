<?php

/**
 * program_live actions.
 *
 * @package    epg2.0
 * @subpackage program_live
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class program_liveActions extends sfActions
{
    /**
     * 
     * @param sfRequest $request A request object
     * @todo by zhigang 记住一个规则，action 只做自已应该做的事情，视图层需要的其他数据使用局部模板或组件调用
     * @todo 将选择频道的功能剥离出去
     * @todo 列表页面调用 Wiki 达100多次， 这是不正常的，找到原因并修复
     */
    public function executeIndex(sfWebRequest $request) {
    	$this->adminid = $this->getUser()->getAttribute('adminid');
        //当前时间获取
    /*    $this->current_time = $request->getParameter('date', ( $this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d", time()) ));
        $this->getUser()->setAttribute('date',$this->current_time);
        $this->channel=$request->getParameter('channel', '');
        $this->start_time=$request->getParameter('start_time', '');
        $this->end_time=$request->getParameter('end_time', '');
        $channel_codes = array();
        if($this->channel!=''){
            switch($this->channel){
                case 1:
                    $channels = Doctrine::getTable('Channel')->getAllChannelByTv();  
                    foreach ($channels as $channel) {
                        $channel_codes[] = $channel->getCode();
                    }                    
                    break;
                case 2:
                    $channels = Doctrine::getTable('Channel')->getAllChannelByTv('cctv');  
                    foreach ($channels as $channel) {
                        $channel_codes[] = $channel->getCode();
                    }                    
                    break;
                case 3:
                    $channels = Doctrine::getTable('Channel')->getAllChannelByTv('tv');  
                    foreach ($channels as $channel) {
                        $channel_codes[] = $channel->getCode();
                    }                    
                    break;                                        
                default:
            }
        }
            
        $query_arr=array();
        $query_arr['date']=$this->current_time;
        if(count($channel_codes)!=0)
            $query_arr['channel_code']=array('$in'=>$channel_codes);
        if($this->start_time!=''&&$this->end_time!=''){
            $a=new MongoDate(strtotime($this->current_time.' '.$this->start_time));
            $b=new MongoDate(strtotime($this->current_time.' '.$this->end_time));
            $query_arr['start_time']=array('$gt' => $a,'$lt' => $b);
        }elseif($this->start_time!=''){
            $a=new MongoDate(strtotime($this->current_time.' '.$this->start_time));
            $query_arr['start_time']=array('$gt' => $a);
        }elseif($this->end_time!=''){
            $b=new MongoDate(strtotime($this->current_time.' '.$this->end_time));
            $query_arr['start_time']=array('$lt' => $b);     
        }     
        //获得program集合
        $this->pager = new sfMondongoPager('Program', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('time' => 1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();*/
    }
	public function executeNow(sfWebRequest $request) 
	{   

		$mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
		$this->adminid = $this->getUser()->getAttribute('adminid');
		$cctvcodes = $_COOKIE[$this->adminid.'_cctvcodes']?$_COOKIE[$this->adminid.'_cctvcodes']:array();
		$tvcodes = $_COOKIE[$this->adminid.'_tvcodes']?$_COOKIE[$this->adminid.'_tvcodes']:array();
		$channel_codes = array_merge(unserialize($cctvcodes),unserialize($tvcodes));
		$now = time();
		$nowstr = '';
        $programs = $repository->getLiveProgramsSortByCode($channel_codes,date("Y-m-d H:i:s",$now));
        if($programs)
        {
        	foreach($programs as $program)
        	{
        	 
        		$id = $program->getChannel()->getId();
        		// $url = "/program/index?channel_id=$id&date=".date("Y-m-d");
        	     $channel_code_id = $program->getChannel()->getCode();
        		 $url="/program/tvsou/action?channel_code=".$channel_code_id;
	        	if(date("H:i",$now) == $program->getTime())
	        		$nowstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td style="color:red"><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td style="color:red" class="sf_admin_list_td_name">'.$program->getName().'</td><td style="color:red" class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
	        	else
					$nowstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td class="sf_admin_list_td_name">'.$program->getName().'</td><td  class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
        	}
        }

 
		return $this->renderText($nowstr);                    
	}  
	public function executeNext(sfWebRequest $request) 
	{   
		$mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
		$this->adminid = $this->getUser()->getAttribute('adminid');
		$cctvcodes = $_COOKIE[$this->adminid.'_cctvcodes']?$_COOKIE[$this->adminid.'_cctvcodes']:array();
		$tvcodes = $_COOKIE[$this->adminid.'_tvcodes']?$_COOKIE[$this->adminid.'_tvcodes']:array();
		$channel_codes = array_merge(unserialize($cctvcodes),unserialize($tvcodes));
		$now = time();
		$nowstr = '';
        $programs = $repository->getNextProgram($channel_codes,date("Y-m-d H:i:s",$now),false);
        if($programs)
        {
        	foreach($programs as $program)
        	{
        		if($program)
        		{
	        		$id = $program->getChannel()->getId();
	        		   //$url = "/program/index?channel_id=$id&date=".date("Y-m-d");
	        		  $channel_code_id = $program->getChannel()->getCode();
	        		  $url="/program/tvsou/action?channel_code=".$channel_code_id;
		        	if(date("H:i",$now) == $program->getTime())
		        		$nowstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td style="color:red"><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td style="color:red" class="sf_admin_list_td_name">'.$program->getName().'</td><td style="color:red" class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
		        	else
						$nowstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td class="sf_admin_list_td_name">'.$program->getName().'</td><td  class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
        		}
        	}
        }

		return $this->renderText($nowstr);                    
	} 	  
	public function executeCCTV(sfWebRequest $request) 
	{   
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 	
		$channels = Doctrine::getTable('Channel')->getAllChannelByTv('cctv');  
		$now = time();
		$cctvstr = '';
        foreach ($channels as $channel) {
        	$channel_codes[] = $channel->getCode();
        } 		
        $programs = $repository->getLivePrograms($channel_codes,date("Y-m-d H:i:s",$now),false);
        if($programs)
        {
        	foreach($programs as $program)
        	{
        		$id = $program->getChannel()->getId();
        		//$url = "/program/index?channel_id=$id&date=".date("Y-m-d");
        	     $channel_code_id = $program->getChannel()->getCode();
        		 $url="/program/tvsou/action?channel_code=".$channel_code_id;
	        	if(date("H:i",$now) == $program->getTime())
	        		$cctvstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td style="color:red"><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td style="color:red" class="sf_admin_list_td_name">'.$program->getName().'</td><td style="color:red" class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
	        	else
					$cctvstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td class="sf_admin_list_td_name">'.$program->getName().'</td><td  class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
        	}
        }

		return $this->renderText($cctvstr);                    
	}
	
	public function executeTV(sfWebRequest $request) 
	{   
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 	
		$channels = Doctrine::getTable('Channel')->getAllChannelByTv('tv');
		$now = time(); 
		$tvstr = '';
        foreach ($channels as $channel) {
        	$channel_codes[] = $channel->getCode();
        } 		
        $programs = $repository->getLivePrograms($channel_codes,date("Y-m-d H:i:s",$now),false);
        if($programs)
        {
            foreach($programs as $program)
        	{
        		$id = $program->getChannel()->getId();
        		//$url = "/program/index?channel_id=$id&date=".date("Y-m-d");
        	    $channel_code_id = $program->getChannel()->getCode();
        	    $url="/program/tvsou/action?channel_code=".$channel_code_id;
	        	if(date("H:i",$now) == $program->getTime())
	        		$tvstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td style="color:red"><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td style="color:red"  class="sf_admin_list_td_name">'.$program->getName().'</td><td style="color:red" class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
	        	else
					$tvstr.='<tr><td style="display:none" class="sf_admin_list_td_id">'.$program->getId().'</td><td><a target="_blank" href="'.$url.'">'.$program->getChannelName().'</a></td><td  class="sf_admin_list_td_name">'.$program->getName().'</td><td class="sf_admin_list_td_time">'.$program->getTime().'</td></tr>';
        	}
        }
		return $this->renderText($tvstr);                 
	}
	public function executeLiveEdit(sfWebRequest $request) 
	{   
		$this->cctvids =  array();
		$this->tvids = array();
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 	
		$this->cctv_channels = Doctrine::getTable('Channel')->getAllChannelByTv('cctv');  
		$this->tv_channels = Doctrine::getTable('Channel')->getAllChannelByTv('tv');
		$this->adminid = $this->getUser()->getAttribute('adminid');
		if ($request->getMethod() == 'POST') {
			$this->cctvcodes = (array)$request->getParameter('cctvcodes', array());
			$this->tvcodes = (array)$request->getParameter('tvcodes', array());
			if (1) {
				
				setCookie($this->adminid.'_cctvcodes','',-22222);
				setCookie($this->adminid.'_tvcodes','',-22222);
				setCookie($this->adminid.'_cctvcodes', serialize((array)$this->cctvcodes), time()+31536000, '/'); //one month
				setCookie($this->adminid.'_tvcodes', serialize((array)$this->tvcodes), time()+31536000, '/'); //one month
				
				$this->getUser()->setFlash('notice', '监控频道设置成功');
				$this->redirect('program_live/liveEdit');
			} else {
				$this->getUser()->setFlash('error', '监控频道设置失败，请检查', false);     
			}			
		}
	}		
    /**
     * 根据字段名称，修改字段值
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_update(sfWebRequest $req) {
        $return = array('code'=>0, '非法请求');
        $name   = $req->getParameter('key');
        $value  = $req->getParameter('value');
        $id     = $req->getParameter('id');
        $allow  = array('name','time');
        if(!in_array($name, $allow)) {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '非法字段')));
        }
                $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
        $return = $repository->ajaxUpdate($id, $name, $value);
        return $this->renderText(json_encode($return));
    }
    /**
     * 直播监控
     * @param sfWebRequest $request
     * @return <type>
     * @author jhm
     */
    public function executePlay(sfWebRequest $request)
    {

    	$this->pageTitle    = '播放监控列表';
    	$this->t = $request->getParameter('t', '');
    	$this->n='未知';
    	$starttime = date("Y-m-d H:i");
        $m_starttime = new MongoDate(strtotime($starttime));
			   switch ($this->t)
			{
			  case '1':
			  	$query= array(
			  			   '$or' => array(
			  			   		array('name' => new MongoRegex("/^".trim($this->n).".*?/im")),
			  			   		array('end_time' => array('$lte' => $m_starttime)),
			  			   )
			  	);

			  break;  
			  case 2:
			  	$query= array(
			  			'name' => new MongoRegex("/^".trim($this->n).".*?/im"),
			  	);
			  break;
			  case 3:
			  	$query= array(
			  			"end_time" =>array('$lte' => $m_starttime),
			  	);
			  	break;
			  default:
				$query= array();	 
			 }
 
			 
    	$this->pager = new sfMondongoPager('ProgramLive', 20);
    	$this->pager->setFindOptions(array(
    			'query' =>$query,
    			"sort" => array("end_time" => 1),
    	  )
    	);
    	$this->pager->setPage($request->getParameter('page', 1));
    	$this->pager->init(); 
    } 
    /**
     * 删除当前播放的节目
     * @param sfWebRequest $request
     * @return <type>
     * @author jhm
     */
    public function executeDelete(sfWebRequest $request)
    {
    	$id     = $request->getParameter('id');
    	$mongo = $this->getMondongo();
    	$ProgramLive = $mongo->getRepository('ProgramLive')->findOneById(new MongoId($id));//print_r($video);exit;
    	$ProgramLive->delete();
        $this->redirect($this->generateUrl('',array('module'=>'program_live','action'=>'play')));
    }
    /**
     * 批量删除当前播放的节目
     * @param sfWebRequest $request
     * @return <type>
     * @author jhm
     */
    public function executeBatchdelete(sfWebRequest $request)
    {
     if($request->isMethod("POST")) {
            $ids = $request->getPostParameter('id');
            if(count($ids)==0) {
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
            }else{
                foreach($ids as $id){
                   $mongo = $this->getMondongo();
                   $program_mongo = $mongo->getRepository("ProgramLive");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   $program->delete();
                }
               $this->getUser()->setFlash("notice",'删除成功!');

            }
        }
        $this->redirect($this->generateUrl('',array('module'=>'program_live','action'=>'play')));
    }
    
    /**
     * 更新当前播放的节目
     * @param sfWebRequest $request
     * @return <type>
     * @author jhm
     */
    public function executeUpdate(sfWebRequest $request)
    {    
    	$id     = $request->getParameter('id');
    	$channel_code=$request->getParameter('channel_code');
    	$starttime = date("Y-m-d H:i");
    	$starttimes=strtotime($starttime);
    	$m_starttime =date("Y-m-d H:i",$starttimes);
    	$m_endtime = date("Y-m-d H:i",$starttimes+60*10);
        $mongo = $this->getMondongo();
		$program_repository = $mongo->getRepository('Program');
		$programlive_repository = $mongo->getRepository('ProgramLive');
		$wiki_repository = $mongo->getRepository('wiki');
		$now = new MongoDate();
    	$program_now=  $program_repository->getLiveProgramByCode($channel_code,'',false,true);//获取当前播放的节目
    	$next_program = $program_repository->getNextProgram(array($channel_code),'',false,true);//获取接下来要播放的节目
    	if($program_now){
    		$programLive_s = $mongo->getRepository('ProgramLive');		
    		$wiki = $wiki_repository->findOneById(new MongoId($program_now->getWikiId()));
    		$program_Live = $programLive_s->findOneById(New MongoId($id));
    		$program_Live->setName($program_now->getName());
    		$program_Live->setStartTime($program_now->getStartTime());
    		if($program_now->getEndTime()){
    			$program_Live->setEndTime($program_now->getEndTime());
    		}else{
    			$l_endtime = date("Y-m-d H:i",$starttimes+60*30);
    			$program_Live->setEndTime($l_endtime);
    		}
    		if($next_program[0]){
    			$program_Live->setNextName($next_program[0]->getName());
    		}else{
    	
    			$program_Live->setNextName("未知");
    		}
    		if($wiki) {
    			$program_Live->setWikiCover($wiki->getCover());
    			$program_Live->setWikiTitle($wiki->getTitle());
    			$program_Live->setWikiId((string)$wiki->getWikiId());
    		}
    		$program_Live->save();
    			
    	}else{
 
    		$programLive_ss = $mongo->getRepository('ProgramLive');
    		$program_Live_s = $programLive_ss->findOneById(New MongoId($id));
    		$program_Live_s->setName("未知");
    		$program_Live_s->setStartTime($m_starttime);
    		$program_Live_s->setEndTime($m_endtime);
    		if($next_program[0]){
    			$program_Live_s->setNextName($next_program[0]->getName());
    		}else{	
    			$program_Live_s->setNextName("未知");
    		}
    		$program_Live_s->save();
    			
    	}
    	$this->redirect($this->generateUrl('',array('module'=>'program_live','action'=>'play')));
    }
    /**
     * 批量更新当前播放的节目
     * @param sfWebRequest $request
     * @return <type>
     * @author jhm
     */
    public function executeBatchupdate(sfWebRequest $request)
    {
       if($request->isMethod("POST")) {
            $ids = $request->getPostParameter('id');
            if(count($ids)==0) {
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
            }else{
                foreach($ids as $id){  
                	$starttime = date("Y-m-d H:i");
                	$starttimes=strtotime($starttime);
                	$m_starttime =date("Y-m-d H:i",$starttimes);
                	$m_endtime = date("Y-m-d H:i",$starttimes+60*10);
                	$mongo = $this->getMondongo();
                	$program_repository = $mongo->getRepository('Program');   
                	$programlive_repository = $mongo->getRepository('ProgramLive');
                    $programlive_code = $programlive_repository->findOneById(new MongoId($id));
                    $channel_code= $programlive_code->getChannelcode();
                	$wiki_repository = $mongo->getRepository('wiki');
                	$now = new MongoDate();
                	$program_now=  $program_repository->getLiveProgramByCode($channel_code,'',false,true);//获取当前播放的节目
                	$next_program = $program_repository->getNextProgram(array($channel_code),'',false,true);//获取接下来要播放的节目
                	if($program_now){
                		$programLive_s = $mongo->getRepository('ProgramLive');
                		$wiki = $wiki_repository->findOneById(new MongoId($program_now->getWikiId()));
                		$program_Live = $programLive_s->findOneById(New MongoId($id));
                		$program_Live->setName($program_now->getName());
                		$program_Live->setStartTime($program_now->getStartTime());
                		if($program_now->getEndTime()){
                			$program_Live->setEndTime($program_now->getEndTime());
                		}else{
                			$l_endtime = date("Y-m-d H:i",$starttimes+60*30);
                			$program_Live->setEndTime($l_endtime);
                		}
                		if($next_program[0]){
                			$program_Live->setNextName($next_program[0]->getName());
                		}else{
                			 
                			$program_Live->setNextName("未知");
                		}
                		if($wiki) {
                			$program_Live->setWikiCover($wiki->getCover());
                			$program_Live->setWikiTitle($wiki->getTitle());
                			$program_Live->setWikiId((string)$wiki->getWikiId());
                		}
                		$program_Live->save();
                		 
                	}else{
                	
                		$programLive_ss = $mongo->getRepository('ProgramLive');
                		$program_Live_s = $programLive_ss->findOneById(New MongoId($id));
                		$program_Live_s->setName("未知");
                		$program_Live_s->setStartTime($m_starttime);
                		$program_Live_s->setEndTime($m_endtime);
                		if($next_program[0]){
                			$program_Live_s->setNextName($next_program[0]->getName());
                		}else{
                			$program_Live_s->setNextName("未知");
                		}
                		$program_Live_s->save();
                		 
                	}
                	$this->getUser()->setFlash("notice",'更新成功!');
                }

            }
        }
        $this->redirect($this->generateUrl('',array('module'=>'program_live','action'=>'play')));
    }
    
}