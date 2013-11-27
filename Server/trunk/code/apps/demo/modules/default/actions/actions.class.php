<?php
/**
 * default actions.
 *
 * @package    epg
 * @subpackage default
 * @author     superwen
 */
class defaultActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{ 	
		$tag=$request->getParameter('tag');
		$this->n='未知';
		$query= array(
			 	"name" =>array('$ne' => $this->n),
		);
		$this->pager = new sfMondongoPager('ProgramLive', 2000);
		$this->pager->setFindOptions(array(
				'query' =>$query,
				"sort" => array("end_time" => 1),
		)
		);
		$this->pager->setPage($request->getParameter('page', 1));
		$this->pager->init();
		$mongo = $this->getMondongo();
		$wiki_repository = $mongo->getRepository('Wiki');
		
		switch ($tag)
		{
			case "all":
				$tag="全部";
				break;
			case "teleplay":
				$tag="电视剧";
				break;
		 	case "film":	
		 		$tag="电影";
			 	break;
	    	case "sports":
			    $tag="体育";
			    break;
			case "entertainment":
			    $tag="娱乐";
			 	break;
		 	case "children":
		 		$tag="少儿";
		 		break;
		 	case "science":
		 		$tag="科教";
		 		break;
		 	case "finance":
		 		$tag="财经";
		 		break;			 	
			case "comprehensive":
		 		$tag="综合";
			 	break;	
			default:
		 		$tag="全部";
				break;
		}
		
		
		if($tag=="全部"){
			$tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
		}else{
			if(!empty($tag)){
			  $tag= array($tag);
			}else{
			  $tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
			}
		}
		foreach ($this->pager as $wiki){
			$id=$wiki->getWikiId();
		    $wiki_array[] = new MongoId($id);
		}
		$ProgramLive = $mongo->getRepository('ProgramLive');
	   //$programs_s= $wiki_repository->getWikiTagsIn($wiki_array,$tag ,$page = 1, $pagesize = 12 );//暂时弃用
		$this->pager_wiki = new sfMondongoPager('Wiki', 12);
		$this->pager_wiki->setFindOptions(array(
    				'query' => array(
    						'_id' => array('$in' =>$wiki_array),  
    						'tags' => array('$in' => $tag)
    				),
    				"sort" => array("wiki_id" => 1),
		)
		);
		$this->pager_wiki->setPage($request->getParameter('page', 1));
		$this->pager_wiki->init();
		//print_rr($this->pager_wiki->getLastPage());
    	foreach ($this->pager_wiki as $programs){
    		$wikis=  (array)$programs->getid();
    		$wiki_id=$wikis['$id'];
    		$wiki = $ProgramLive->getProgramByWiki($wiki_id);
    	    $channelcode = Doctrine::getTable('Channel')->findOneByCode($wiki->getchannelcode());
    		$program_s[] =array(
    				 'wikiid'=>$wiki_id,
    				 'cover'=>$programs->getCover(),
    				 'name'=> $programs->title,
    				 'channel' =>$channelcode->name,
    				 'start_time'   => date("H:i:s",$wiki->getStartTime()->getTimestamp()),
    				 'end_time'     => date("H:i:s",$wiki->getEndTime()->getTimestamp()),
    				 'next_name'    => $wiki->getNextName(),
    		);
    	}
		$this->nowplaying=	$program_s;
	}
	public function executeAjaxNowPlaying(sfWebRequest $request)
	{
		$tag=$request->getParameter('tag');
		$this->n='未知';
		$query= array(
				"name" =>array('$ne' => $this->n),
		);
		$this->pager = new sfMondongoPager('ProgramLive', 2000);
		$this->pager->setFindOptions(array(
				'query' =>$query,
				"sort" => array("end_time" => 1),
		)
		);
		$this->pager->setPage($request->getParameter('pagea', 1));
		$this->pager->init();
		$mongo = $this->getMondongo();
		$wiki_repository = $mongo->getRepository('Wiki');
		
		switch ($tag)
		{
			case "all":
				$tag="全部";
				break;
			case "teleplay":
				$tag="电视剧";
				break;
			case "film":
				$tag="电影";
				break;
			case "sports":
				$tag="体育";
				break;
			case "entertainment":
				$tag="娱乐";
				break;
			case "children":
				$tag="少儿";
				break;
			case "science":
				$tag="科教";
				break;
			case "finance":
				$tag="财经";
				break;
			case "comprehensive":
				$tag="综合";
				break;
			default:
				$tag="全部";
				break;
		}
		
		if($tag=="全部" || $tag=="null"){
			$tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
		}else{
			if(!empty($tag)){
				$tag= array($tag);
			}else{
				$tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
			}
		}
		foreach ($this->pager as $wiki){
			$id=$wiki->getWikiId();
			$wiki_array[] = new MongoId($id);
		}
		$ProgramLive = $mongo->getRepository('ProgramLive');
		$pages=$request->getParameter('page')!="" ? $request->getParameter('page') : 1 ;
		//$programs_s= $wiki_repository->getWikiTagsIn($wiki_array,$tag ,$pages, $pagesize = 12 );
		$this->pager_wiki = new sfMondongoPager('Wiki', 12);
		$this->pager_wiki->setFindOptions(array(
				'query' => array(
						'_id' => array('$in' =>$wiki_array),
						'tags' => array('$in' => $tag)
				),
				"sort" => array("wiki_id" => 1),
		)
		);
		$this->pager_wiki->setPage($request->getParameter('page', 1));
		$this->pager_wiki->init();
		foreach ($this->pager_wiki as $programs){
			$wikis=  (array)$programs->getid();
			$wiki_id=$wikis['$id'];
			$wiki = $ProgramLive->getProgramByWiki($wiki_id);
			$channelcode = Doctrine::getTable('Channel')->findOneByCode($wiki->getchannelcode());
			$program_s[] =array(
					'wikiid'=>$wiki_id,
					'cover'=>$programs->getCover(),
					'name'=> $programs->title,
					'channel' =>$channelcode->name,
					'start_time'   => date("H:i:s",$wiki->getStartTime()->getTimestamp()),
					'end_time'     => date("H:i:s",$wiki->getEndTime()->getTimestamp()),
					'next_name'    => $wiki->getNextName(),
			);
		}
		return $this->renderPartial('AjaxNowPlaying',array('nowplaying'=> $program_s));
	}
	/**
     *
     * @desc 获取当前正在播放的节目
     * @param <type>
     * @author jhm
     */
	public function executeNowPlaying(sfWebRequest $request)
	{
	 
		$id     = $request->getParameter('id');
		$channel_code=$request->getParameter('channel_code');
		$starttime = date("Y-m-d H:i");
		$starttimes=strtotime($starttime);
		$m_starttime =date("Y-m-d H:i",$starttimes);
		$m_endtime = date("Y-m-d H:i",$starttimes+60*10);
		$mongo = $this->getMondongo();
		//$program_repository = $mongo->getRepository('ProgramLive');
		$program_repository = $mongo->getRepository('Program');
		
		$now = new MongoDate();
		//$channels = Doctrine::getTable('Channel')->getChannels();
		$Channel_type= $request->getParameter('Channel_type') !='' ? $request->getParameter('Channel_type') : 'all';
		$mongo = $this->getMondongo();
		$sps_repository = $mongo->getRepository('SpService');
		$channels=$sps_repository->getServiceChannelsList($Channel_type,$sort = '', $page = 1, $pagesize = 14 );
		foreach ($channels as $channel) {
			$channel_codes[] = $channel->getChannelCode();
		}
		
	    $program_now=  $program_repository->getLivePrograms($channel_codes,date("Y-m-d H:i:s",time()),true,1,10);//获取当前播放的节目
        $repository = $mongo->getRepository('wiki');
	    foreach ($program_now as $program_now_s) {
	    	$id = $program_now_s->getWikiId();
	      	$programs = $repository->findOneById(new MongoId($id));
	      	if($programs){
		      	if($programs->getCover()){
			        $program_s[] =array(
			       		'wikiid'=>$id,
			       		'cover'=>$programs->getCover(),
		                'name'=>$program_now_s->name,
		        		'channel' =>$programs->getChannel(),
		        		'start_time'   => date("H:i:s",$program_now_s->getStartTime()->getTimestamp()),
		        		'end_time'     => date("H:i:s",$program_now_s->getEndTime()->getTimestamp()),
			       		);
		      	}else{
		      		$program_s[] =array(
	      				'wikiid'=>$id,
	      				'cover'=>'',
	      				'name'=>$program_now_s->name,
	      				'channel' =>$programs->getChannel(),
	      				'start_time'   => date("H:i:s",$program_now_s->getStartTime()->getTimestamp()),
	      				'end_time'     => date("H:i:s",$program_now_s->getEndTime()->getTimestamp()),
		      		);
		      		
		      	}
	      	}
	    }  
	    
	    $this->nowplaying=	$program_s;		
	}
	/**
	 *
	 * @desc 获取将要播放的节目
	 * @param <type>
	 * @author jhm
	 */
	public function executeWillPlay(sfWebRequest $request)
	{
		$tag=$request->getParameter('tag');
		$mongo = $this->getMondongo();
		$repository = $mongo->getRepository('Program');
		//$channels = Doctrine::getTable('Channel')->getChannels();
	    $Channel_type= $request->getParameter('Channel_type') !='' ? $request->getParameter('Channel_type') : 'all';
		$mongo = $this->getMondongo();
		/**
		$sps_repository = $mongo->getRepository('SpService');
		$channels=$sps_repository->getServiceChannelsList($Channel_type,$sort = '', $page = 1, $pagesize = 100 );
		foreach ($channels as $channel) {
			$channel_codes[] = $channel->getChannelCode();
		}
		*/
        $programs = $repository->getLiveProgramsWillPlay('',$page = 1, $pagesize = 100);//将要播放的节目
        $repository_wiki = $mongo->getRepository('wiki');
        switch ($tag)
        {
        	case "all":
        		$tag="全部";
        		break;
        	case "teleplay":
        		$tag="电视剧";
        		break;
        	case "film":
        		$tag="电影";
        		break;
        	case "sports":
        		$tag="体育";
        		break;
        	case "entertainment":
        		$tag="娱乐";
        		break;
        	case "children":
        		$tag="少儿";
        		break;
        	case "science":
        		$tag="科教";
        		break;
        	case "finance":
        		$tag="财经";
        		break;
        	case "comprehensive":
        		$tag="综合";
        		break;
        	default:
        		$tag="全部";
        		break;
        }
        if($tag=="全部"){
        	$tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
        }else{
        	if(!empty($tag)){
        		$tag= array($tag);
        	}else{
        	    $tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
        	}
        }
        foreach ($programs as $program_now_s){
        
        	 $id=$program_now_s->getWikiId();
             $wiki_array[] = new MongoId($id);
          /**
             $program_kk[] =array(
             'id'=>$id,
             'channel'=>	$program_now_s->getchannelcode(),
             'name'	  =>$program_now_s->name,
             'start_time'   => date("H:i:s",$program_now_s->getStartTime()->getTimestamp()),
             'end_time'     => date("H:i:s",$program_now_s->getEndTime()->getTimestamp()),
             		);
          */
        }
       // $programs_s= $repository_wiki->getWikiTagsIn($wiki_array,$tag ,$page = 1, $pagesize = 12 );
       
        $this->pager_wiki = new sfMondongoPager('Wiki', 12);
        $this->pager_wiki->setFindOptions(array(
        		'query' => array(
        				'_id' => array('$in' =>$wiki_array),
        				'tags' => array('$in' => $tag)
        		),
        		"sort" => array("wiki_id" => 1),
        )
        );
        $this->pager_wiki->setPage($request->getParameter('page', 1));
        $this->pager_wiki->init();
        
        
        $channel="";
	    foreach ($this->pager_wiki as $pro){
             $wiki_id=   $pro->getid();
             $wiki = $repository->getdayNotPlayedProgramByWikiId($wiki_id);// 根据wiki 获取播放节目
             $wikis=$wiki[0];
             if($wikis->channel_code){
             $channelcode = Doctrine::getTable('Channel')->findOneByCode($wikis->channel_code);
             $channel= $channelcode->name;
             }
             if($wikis->start_time){
             	$start_time=date("H:i:s",$wikis->start_time->getTimestamp());
             }
             if($wikis->end_time){
             	$end_time=date("H:i:s",$wikis->end_time->getTimestamp());
             }
	        	 $program_k[] =array(
	        			  'wikiid'=>$wiki_id,
	        			  'cover'=>$pro->getCover(),
	        			  'name'=> $pro->title,
	        			  'channel' =>$channel,
	        	          'start_time'   => $start_time,
	        		      'end_time'     => $end_time,
	        	 );
                      
        }
        $this->program_now=$program_k;
	}
	/**
	 *
	 * @desc Ajax获取将要播放的节目
	 * @param <type>
	 * @author jhm
	 */
	public function executeAjaxWillPlay(sfWebRequest $request){
		$pages=$request->getParameter('page')!="" ? $request->getParameter('page') : 1 ;
		$tag=$request->getParameter('tag');
		$mongo = $this->getMondongo();
		$repository = $mongo->getRepository('Program');
		//$channels = Doctrine::getTable('Channel')->getChannels();
		$Channel_type= $request->getParameter('Channel_type') !='' ? $request->getParameter('Channel_type') : 'all';
		$mongo = $this->getMondongo();
		$programs = $repository->getLiveProgramsWillPlay('',$page = 1, $pagesize = 100);//将要播放的节目
		$repository_wiki = $mongo->getRepository('wiki');
		switch ($tag)
		{
			case "all":
				$tag="全部";
				break;
			case "teleplay":
				$tag="电视剧";
				break;
			case "film":
				$tag="电影";
				break;
			case "sports":
				$tag="体育";
				break;
			case "entertainment":
				$tag="娱乐";
				break;
			case "children":
				$tag="少儿";
				break;
			case "science":
				$tag="科教";
				break;
			case "finance":
				$tag="财经";
				break;
			case "comprehensive":
				$tag="综合";
				break;
			default:
				$tag="全部";
				break;
		}
		if($tag=="全部"){
			$tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
		}else{
			if(!empty($tag)){
				$tag= array($tag);
			}else{
				$tag= array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
			}
		}
		foreach ($programs as $program_now_s){
		
			$id=$program_now_s->getWikiId();
			$wiki_array[] = new MongoId($id);
		}
		//$programs_s= $repository_wiki->getWikiTagsIn($wiki_array,$tag ,$pages, $pagesize = 12 );
		$this->pager_wiki = new sfMondongoPager('Wiki', 12);
		$this->pager_wiki->setFindOptions(array(
				'query' => array(
						'_id' => array('$in' =>$wiki_array),
						'tags' => array('$in' => $tag)
				),
				"sort" => array("wiki_id" => 1),
		)
		);
		$this->pager_wiki->setPage($request->getParameter('page', 1));
		$this->pager_wiki->init();
		$channel="";
		foreach ($this->pager_wiki as $pro){
			$wiki_id=   $pro->getid();
			$wiki = $repository->getdayNotPlayedProgramByWikiId($wiki_id);// 根据wiki 获取播放节目
			$wikis=$wiki[0];
			if($wikis->channel_code){
				$channelcode = Doctrine::getTable('Channel')->findOneByCode($wikis->channel_code);
				$channel= $channelcode->name;
			}
			if($wikis->start_time){
				$start_time=date("H:i:s",$wikis->start_time->getTimestamp());
			}
			if($wikis->end_time){
				$end_time=date("H:i:s",$wikis->end_time->getTimestamp());
			}
			$program_k[] =array(
					'wikiid'=>$wiki_id,
					'cover'=>$pro->getCover(),
					'name'=> $pro->title,
					'channel' =>$channel,
					'start_time'   => $start_time,
					'end_time'     => $end_time,
			);
		
		}	
		return $this->renderPartial('AjaxWillPlay',array('program_now'=> $program_k));
	}
	/**
	 *
	 * @desc 获取节目表
	 * @param <type>
	 * @author jhm
	 */
	public function executeProgramList(sfWebRequest $request)
	{
         $date= $request->getParameter('date', ( $this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d", time())));
         $channel_code= $request->getParameter('channel_code')!='' ?  $request->getParameter('channel_code') : 'cctv1';
         
         $this->programLists = new sfMondongoPager('Program', 12);
         $query = array(
         		'channel_code' => $channel_code,
         		'date' => $date,
         		//'wiki_id'=> array('$exists' => true),
         );
         $this->programLists->setFindOptions(array(
                    'query' => $query,
                    'sort' => array('start_time' => 1),
         )
         );
         $this->programLists->setPage($request->getParameter('page', 1));
         $this->programLists->init();
         
         //$mongo = $this->getMondongo();
	     //$programRes = $mongo->getRepository("program");
	    // $program= $programRes->getDayProgramsByChannelCode($channel_code,$date,true,false);
	    // $this->programLists  = $program;
	}
	/**
	 *
	 * @desc 获取频道表
	 * @param <type>
	 * @author jhm
	 */
	public function executeChannelList(sfWebRequest $request)
	{ 
	    $Channel_type= $request->getParameter('Channel_type') !='' ? $request->getParameter('Channel_type') : 'cctv';
		$mongo = $this->getMondongo();
		//$sps_repository = $mongo->getRepository('SpService');
		//$programs=$sps_repository->getServiceChannelsList($Channel_type,$sort = '', $page = 1, $pagesize = 14 );
		
		$this->ChannelList = new sfMondongoPager('SpService', 40);
		if($Channel_type != 'all'){
			$query = array("tags" => $Channel_type);
		}else{
			$query = array();
		}
		
		$sort = array("logicNumber" => 1);
		$this->ChannelList->setFindOptions(array(
			 	'query' => $query,
			 	"sort" => $sort,
		)
		);
		$this->ChannelList->setPage($request->getParameter('page', 1));
		$this->ChannelList->init();

		$programLive = $mongo->getRepository('ProgramLive');
	 	foreach($this->ChannelList as $program){
			
			$lives=$programLive->getProgramByCode($program->getChannelCode());
			$LivesArray[] = array(
					 'channel_code' => $program->getChannelCode(),
					 'logic_number' => $program->getlogicNumber(),
					 'channelname'     => $program->name,
					 'name'         =>$lives->name,
					//'start_time'   => date("H:i:s",$lives->start_time->getTimestamp()),
					// 'end_time'     => date("H:i:s",$lives->end_time->getTimestamp()),
			         'channel_logo'  =>$program->getChannellogo(),
					 'next_name'    => $lives->next_name,		 
			);
		 
 	
		}	
		$this->program=$LivesArray;
	}
	
	/**
	 *
	 * @desc 获取频道表
	 * @param <type>
	 * @author jhm
	 */
	public function executeAjaxChannelList(sfWebRequest $request)
	{
 
		$Channel_type= $request->getParameter('Channel_type') !='' ? $request->getParameter('Channel_type') : 'cctv';
		$mongo = $this->getMondongo();
		//$sps_repository = $mongo->getRepository('SpService');
		//$programs=$sps_repository->getServiceChannelsList($Channel_type,$sort = '', $page = 1, $pagesize = 14 );
		$this->ChannelList = new sfMondongoPager('SpService', 40);
		if($Channel_type != 'all'){
			$query = array("tags" => $Channel_type);
		}else{
			$query = array();
		}
 
		$sort = array("logicNumber" => 1);
		$this->ChannelList->setFindOptions(array(
				'query' => $query,
			    "sort" => $sort,
		)
		);
		$this->ChannelList->setPage($request->getParameter('page', 1));
		$this->ChannelList->init();
		$programLive = $mongo->getRepository('ProgramLive');
		foreach($this->ChannelList as $program){
			$lives=$programLive->getProgramByCode($program->getChannelCode());
			$LivesArray[] = array(
					'channel_code' => $program->getChannelCode(),
					'logic_number' => $program->getlogicNumber(),
					'channelname'     => $program->name,
					'name'         =>$lives->name,
					'channel_logo'  =>$program->getChannellogo(),
					//'start_time'   => date("H:i:s",$lives->start_time->getTimestamp()),
					// 'end_time'     => date("H:i:s",$lives->end_time->getTimestamp()),
					'next_name'    => $lives->next_name,
			);
				
	
		}
		return $this->renderPartial('ChannelList',array('program'=> $LivesArray));
		//$this->program=$LivesArray;
	}
	
	/**
	 *
	 * @desc 搜索节目
	 * @param <type>
	 * @author jhm
	 */
	public function executeSearchList(sfWebRequest $request)
	{	
	    $search_text= $request->getParameter('program_name');
		$mongo = $this->getMondongo();
		//$programRes = $mongo->getRepository("program");
 
			$this->search = new sfMondongoPager('Program', 12);
			$starttime   = empty($starttime)?time():strtotime($starttime);
			$m_starttime = new MongoDate($starttime);
			$m_endtime   = empty($endtime)?new MongoDate(($starttime + 3600*2)):new MongoDate(strtotime($endtime));
			$key = new MongoRegex("/.*".$search_text.".*/");
			
				if(!empty($key))
				{
					$query = array(
								
		 								'name'=>$key,
		 								'start_time' => array('$lte' => $m_endtime),
		 								'end_time' => array('$gte' => $m_starttime),
		 								'wiki_id'=>array('$exists'=>true)			
								
					);
				}else{
					$query = array(
							
							  			'start_time' => array('$lte' => $m_endtime),
		 								'end_time' => array('$gte' => $m_starttime),
		 								'wiki_id'=>array('$exists'=>true)
							
							
							);
				}
	 
 
		$this->search->setFindOptions(array(
				'query' => $query,
				"sort" => array("start_time" => 1),
		)
		);
		$this->search->setPage($request->getParameter('page', 1));
		$this->search->init();
		
		
		
		
	    $WikiRepository = $mongo->getRepository('Wiki');
	    foreach($this->search as $program_key =>$program)
	    {
	    	$wiki = $WikiRepository->getWikiById($program['wiki_id']);
	    	if($wiki)
	    	{
	    		$channel = $program->getChannel();
	    		$nodeArray[] = array(
	    				'channel_id'   => $channel->getId(),
	    				'channel_code' => $channel->getCode(),
	    				'channel_name' => $channel->getName(),
	    				'channel_logo' => $channel->getLogoUrl(),
	    				'channel_hot'  => $channel->getHot(),
	    				'likenum'      => $channel->getLikeNum(),
	    				'dislikenum'   => $channel->getDislikeNum(),
	    				'name'         => $program->getName(),
	    				'date'         => $program->getDate(),
	    				'start_time'   => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
	    				'end_time'     => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
	    				'wiki_id'      => (string)$wiki->getId(),
	    				'wiki_cover'   => file_url($wiki->getCover()),
	    				'tags'         => !$wiki->getTags() ? '' : $wiki->getTags(),
	    				'hasvideo'     => $wiki->getHasVideo()>0?'yes':'no',
	    				'source'       => implode(',',$wiki->getSource()),
	    				'content'      => $wiki->getcontent(),
	    				'host'         => implode(',',$wiki->getHost()),
	    				'cover'        => $wiki->getCover(),
	    				
	    		);
	    
	    	}
	    }
	    $this->searchlist=$nodeArray;
	}
	
	/**
	 *
	 * @desc Ajax搜索节目
	 * @param <type>
	 * @author jhm
	 */
	public function executeAjaxSearchList(sfWebRequest $request)
	{
		$search_text= urldecode($request->getParameter('program_name'));
		$mongo = $this->getMondongo();
		$programRes = $mongo->getRepository("program");
		$pages=$request->getParameter('page')!="" ? $request->getParameter('page') : 1 ;
		$this->search = new sfMondongoPager('Program', 12);
			$starttime   = empty($starttime)?time():strtotime($starttime);
			$m_starttime = new MongoDate($starttime);
			$m_endtime   = empty($endtime)?new MongoDate(($starttime + 3600*2)):new MongoDate(strtotime($endtime));
			$key = new MongoRegex("/.*".$search_text.".*/");
			
				if(!empty($key))
				{
					$query = array(
								
		 								'name'=>$key,
		 								'start_time' => array('$lte' => $m_endtime),
		 								'end_time' => array('$gte' => $m_starttime),
		 								'wiki_id'=>array('$exists'=>true)			
								
					);
				}else{
					$query = array(
							
							  			'start_time' => array('$lte' => $m_endtime),
		 								'end_time' => array('$gte' => $m_starttime),
		 								'wiki_id'=>array('$exists'=>true)
							
							
							);
				}
	 
 
		$this->search->setFindOptions(array(
				'query' => $query,
				"sort" => array("start_time" => 1),
		)
		);
		$this->search->setPage($request->getParameter('page', 1));
		$this->search->init();
		
		
		
		
	    $WikiRepository = $mongo->getRepository('Wiki');
	    foreach($this->search as $program_key =>$program)
	    {
			$wiki = $WikiRepository->getWikiById($program['wiki_id']);
			if($wiki)
			{
				$channel = $program->getChannel();
				$nodeArrays[] = array(
						'channel_id'   => $channel->getId(),
						'channel_code' => $channel->getCode(),
						'channel_name' => $channel->getName(),
						'channel_logo' => $channel->getLogoUrl(),
						'channel_hot'  => $channel->getHot(),
						'likenum'      => $channel->getLikeNum(),
						'dislikenum'   => $channel->getDislikeNum(),
						'name'         => $program->getName(),
						'date'         => $program->getDate(),
						'start_time'   => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
						'end_time'     => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
						'wiki_id'      => (string)$wiki->getId(),
						'wiki_cover'   => file_url($wiki->getCover()),
						'tags'         => !$wiki->getTags() ? '' : $wiki->getTags(),
						'hasvideo'     => $wiki->getHasVideo()>0?'yes':'no',
						'source'       => implode(',',$wiki->getSource()),
						'content'      => $wiki->getcontent(),
						'host'         => implode(',',$wiki->getHost()),
						'cover'        => $wiki->getCover(),
						 
				);
				 
			}
		}
		return $this->renderPartial('AjaxSearchList',array('searchlist'=> $nodeArrays));
	}
	/**
	 *
	 * @desc 获取我的预约
	 * @param <type>
	 * @author jhm
	 */
	public function executeReservationList(sfWebRequest $request)
	{
	    $date= $request->getParameter('date');
	    $mongo = sfContext::getInstance()->getMondongo();
		$userRepository = $mongo->getRepository('user');
		print_rr($this->device['dnum']);
		$hasUser = $userRepository->getUserIdByDeviceId('12345');
		if($hasUser){
		// $user_id =  (string)$hasUser->getId();
		 $user_id =  '517784226803fa1207000000';
		 $ProgramUserRepository = $mongo->getRepository('Programe_user');
	     $this->programusers=$ProgramUserRepository->getDateProgrameByUser($date,$user_id,1,10);
		}
	 
	}
	/**
	 *
	 * @desc 显示详情
	 * @param <type>
	 * @author jhm
	 */
	public function executeShow(sfWebRequest $request)
	{
		
		$id = $request->getParameter('wiki_id', null);
		$mongo = $this->getMondongo();
		$repository = $mongo->getRepository('wiki');
		$this->wiki = $repository->findOneById(new MongoId($id));
		$NextweekProgram = $mongo->getRepository("NextweekProgram"); //预告
	    $date=date('Y-m-d',strtotime('-1 day'));
		$this->nextweekprogram= $NextweekProgram->getDatePrograms($date);
	    $yesterdayprogram=$mongo->getRepository("YesterdayProgram"); //回看
	    $date=date('Y-m-d',strtotime('+1 day'));
		$this->yesterdayprogram=$yesterdayprogram->getDatePrograms($date);
		$user_id='516e09ce069c0a3972000002';
		$mongo = $this->getMondongo();
		$SingleChip_s = $mongo->getRepository('SingleChip');
		$this->collect = $SingleChip_s->getOneChip($user_id,$id);
	 
		
		//获取电视分集
		if( $this->wiki->getModel() == "teleplay" ){
			$wikiMetaRepos = $mongo->getRepository('wikiMeta');
			$this->metas = $wikiMetaRepos->getMetasByWikiId($id);
		}
	}
	/**
	 *
	 * @desc 用ajax显示详情 wiki
	 * @param <type>
	 * @author jhm
	 */
	public function executeAjaxShow(sfWebRequest $request)  
	{
		$id = $request->getParameter('wiki_id', null);
		$mongo = $this->getMondongo();
		$repository = $mongo->getRepository('wiki');
		$wiki = $repository->findOneById(new MongoId($id));
		$img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $wiki->getCover());
		$returns=array(
				'name'=>$wiki->title,
				'content'      => $wiki->getcontent(),
				'host'         => implode(',',$wiki->getHost()),
				'cover'        => $img,
				);
		
		
        return $this->renderText(json_encode($returns));
	}
	
	/**
	 *
	 * @desc ajax获取节目表
	 * @param <type>
	 * @author jhm
	 */
	public function executeAjaxProgramList(sfWebRequest $request)
	{
		if($request->getParameter('date')=="null"){
	    $date= date("Y-m-d", time());
		}else{
		$date= $request->getParameter('date', ( $this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d", time())));
		}
		if($request->getParameter('channel_code')=="null"){
	    $channel_code='cctv1';
		}else{
		$channel_code= $request->getParameter('channel_code')!='' ?  $request->getParameter('channel_code') : 'cctv1';
		}
		//$mongo = $this->getMondongo();
		//$programRes = $mongo->getRepository("program");
	   // $pages=$request->getParameter('page')!="" ? $request->getParameter('page') : 1 ;
	    //$program= $programRes->getDayProgramsByChannelCode($channel_code,$date,true,false,$pages,11);
	    
		$this->programLists = new sfMondongoPager('Program', 12);
		$query = array(
				'channel_code' => $channel_code,
				'date' => $date,
				//'wiki_id'=> array('$exists' => true),
		);
		$this->programLists->setFindOptions(array(
				'query' => $query,
				'sort' => array('start_time' => 1),
		)
		);
		$this->programLists->setPage($request->getParameter('page', 1));
		$this->programLists->init();
	   return $this->renderPartial('ProgramList',array('programLists'=> $this->programLists));
	}
	/**
	 * @desc 我的电视
	 * @author jhm
	 */
	public function executeMyTv(sfWebRequest $request)
	{
		///////////////////////////////////////
		$type= $request->getParameter('type');
		switch ($type)
		{
			case "reservation":
				$this->program_now=$this->reservation();
				$this->setTemplate('MyTv');
				break;
		 	case "collect":	
		 		$this->program_now=$this->collect();
		        $this->setTemplate('collect');
			 	break;
			case "channel":
		        $this->setTemplate('Channel');
			 	break;	
			default:
				$this->program_now=$this->reservation();
				$this->setTemplate('MyTv');
				break;
		}

		
	}
	public function reservation(){
		$program_m[] =array(
				'wikiid'=>'4d00884d2f2a241bd700cec1',
				'cover'=>'1318909082466.jpg',
				'name'=> '经济半小时',
				'channel' =>'CCTV2-财经',
				'start_time' =>'17:20',
		);
		$program_m[] =array(
				'wikiid'=>'4d12e0d2edcd88e54900002e',
				'cover'=>'1318908969276.jpg',
				'name'=> '经济信息联播',
				'channel' =>'CCTV2-财经',
				'start_time' =>'18:30',
		     );
		$program_m[] =array(
				'wikiid'=>'5018d1385570eca629000063',
				'cover'=>'1343803805486.jpg',
				'name'=> '央视财经评论',
				'channel' =>'CCTV2-财经',
				'start_time' =>'19:30',
		);
		return $program_m;
	}
   public function collect()
   {
	   	$program_m[] =array(
	   			'wikiid'=>'4d00885d2f2a241bd700cf66',
	   			'cover'=>'1318913246920.jpg',
	   			'name'=> '向幸福出发',
	   			'channel' =>'CCTV3-综艺',
	   	);
	   	$program_m[] =array(
	   			'wikiid'=>'51e385026dbde15629000004',
	   			'cover'=>'1373865173652.jpg',
	   			'name'=> '唱吧-2013年消夏歌会6(1)',
	   			'channel' =>'CCTV3-综艺',
	   	);
	   	$program_m[] =array(
	   			'wikiid'=>'51427cb7ed454b013d000002',
	   			'cover'=>'1363311785603.jpg',
	   			'name'=> '生活就是舞台(完整版)',
	   			'channel' =>'CCTV3-综艺',
	   	);
	   	return $program_m;
   }
   /**
    * @desc 删除我的收藏
    * @author jhm
    */
   public function executeDeletCollect(sfWebRequest $request)
   {
      	$wiki_id= $request->getParameter('wiki_id');
      	$user_id='516e09ce069c0a3972000002';
     	$mongo = $this->getMondongo();
     	$SingleChip_s = $mongo->getRepository('SingleChip');
        $SingleChip_m = $SingleChip_s->getOneChip($user_id,$wiki_id);
        if($SingleChip_m){
        	$SingleChip_m->delete();
        	return $this->renderText('1');
        }else{
        	return $this->renderText('0');
        }
   }
   
   /**
    * @desc 添加我的收藏
    * @author jhm
    */
   public function executeAddCollect(sfWebRequest $request)
   {
   	$wiki_id= $request->getParameter('wiki_id');
   	$user_id='516e09ce069c0a3972000002';
   	$mongo = $this->getMondongo();
   	$SingleChip_s = $mongo->getRepository('SingleChip');
    $SingleChip_m = $SingleChip_s->getOneChip($user_id,$wiki_id);
   	if($SingleChip_m){
   		return $this->renderText('0');
   	}else{
   		$SingleChip = new SingleChip();
   		$SingleChip->setUserId($user_id);
   		$SingleChip->setWikiId($wiki_id);
   		$SingleChip->setIsPublic('True');
   		$SingleChip->setCreatedAt(date("Y-m-d H:i:s",time()));
   		$SingleChip->save();
   		if($SingleChip){
   			return $this->renderText('1');
   		}else{
   		    return $this->renderText('2');
   		}
  
    	}
   }
	
}
