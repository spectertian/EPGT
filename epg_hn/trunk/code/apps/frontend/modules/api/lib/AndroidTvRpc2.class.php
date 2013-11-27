<?php
//sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'GetFileUrl'));
/*
 * 1、某地区频道列表
 * 2、某频道按天的节目列表
 * 3、按分类正在直播的节目列表
 * 4、维基详细信息
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * @aurhot lizhi
 */
class AndroidTvRpc2 {

    public function sayHello() {
        return 'hello';
    }
    private function setVideoUrl($id=0){
        return "http://proxy.kkttww.net:8080/urlproxy/qiyi/?redirect=1&tv_id=".$id;
    }
    public function getEpisodes($wiki)
    {
    	$wiki_episode=array();
        $model = $wiki->getModel();
        if ($model == 'film') 
        {
            $videos = $wiki->getVideos();
            if ($videos != NULL) 
            {
                foreach ($videos as $key=>$video) 
                {
                	static $n=0;
			        $referer = $video->getReferer();
                    $tvconfig = $video->getConfig();
			    	$refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
					if (array_key_exists($referer, $refererSource)) 
					{
					    $source = $refererSource[$referer];
					}
					$url = ($referer == 'qiyi')?$this->setVideoUrl($tvconfig['tvId']):$video->getUrl();
					$wiki_episode['episode'][$n][0]['source']=$source;                	
                    	$wiki_episode['episode'][$n][1][$key]['id']=$video->getId();
                    	$wiki_episode['episode'][$n][1][$key]['index']=1;
                    	$wiki_episode['episode'][$n][1][$key]['size']=0;
                    	$wiki_episode['episode'][$n][1][$key]['length']=0;
                    	$wiki_episode['episode'][$n][1][$key]['format']='';
                    	$wiki_episode['episode'][$n][1][$key]['rate']=0;
                    	$wiki_episode['episode'][$n][1][$key]['vip']=0;
                    	$wiki_episode['episode'][$n][1][$key]['url']=$url;
                    	$wiki_episode['episode'][$n][1][$key]['live']=0;
                    $n++;
                }
            }
			return $wiki_episode;
        }
        if ($model == 'teleplay') 
        {
            $playLists = $wiki->getPlayList();
            if ($playLists != NULL) 
            {
                foreach ($playLists as $playList) 
                {
			        static $n=0;
			        $referer = $playList->getReferer();
			    	$refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
					if (array_key_exists($referer, $refererSource)) 
					{
					    $source = $refererSource[$referer];
					}
					$wiki_episode['episode'][$n][0]['source']=$source;
					$countVideo = $playList->countVideo();
					$videos = $playList->getVideos();                	
					$j = 1;
					foreach ($videos as $key=>$video) 
					{
						$tvconfig = $video->getConfig();
						$wiki_episode['episode'][$n][1][$key]['id']=$video->getId();
						$wiki_episode['episode'][$n][1][$key]['index']=$j;
						$wiki_episode['episode'][$n][1][$key]['size']=0;
						$wiki_episode['episode'][$n][1][$key]['length']=0;
						$wiki_episode['episode'][$n][1][$key]['format']='';
						$wiki_episode['episode'][$n][1][$key]['rate']=0;
						$wiki_episode['episode'][$n][1][$key]['vip']=0;
						$wiki_episode['episode'][$n][1][$key]['url']=($referer=='qiyi')?$this->setVideoUrl($tvconfig['tvId']):$video->getUrl();
						$wiki_episode['episode'][$n][1][$key]['live']=0;
						$j++;
					}
				$n++;
                }
            }
        }
		return $wiki_episode;
    }  
    /**
    * 获取所有频道的栏目
    */
    public function getAllChannel() {
        $channels = Doctrine::getTable('Channel')->getChannels();
        $channelcode = array();
        $total = count($channels);
        foreach($channels as $key=>$channel){
            $channelcode[$key]['code'] = $channel->getCode();
            $channelcode[$key]['id'] = $channel->getId();
            $channelcode[$key]['name'] = $channel->getName();
            $channelcode[$key]['logo'] = $channel->getLogoUrl();
        }
        return $channelcode;
    }
    /**
     * 某地区频道列表
     * @param array $args[0] 地区
     * @param array $args[1] 市
     * @author lizhi
     * @return array
     */
    public function getChannelList(array $args){
        $user = sfContext::getInstance()->getUser();
        $allProvince = Province::getProvince();        
        $province_location = isset($args[0])?$args[0]:'';
        $city_location = isset($args[1])?$args[1]:'';
        $order= isset($args[2])?$args[2]:'';  //排序lfc
        if($province_location){
            $province = array_search($province_location, $allProvince);
            $user->setAttribute('province',  $province);
        }elseif($user->getAttribute('province')){
            $province = $user->getAttribute('province');
        }else{
            $province = $user->getUserProvince();
        }
        if($city_location){
             $city = $city_location;
        }else{
            $city = $user->getUserCity();
        }
        
        $local_channels = Doctrine::getTable('Channel')->getUserChannels('',$province,$order);
        
        $total = count($local_channels);
        foreach($local_channels as $key=>$channel){
            $channelcode[$key]['code'] = $channel->getCode();
            $channelcode[$key]['id'] = $channel->getId();
            $channelcode[$key]['name'] = $channel->getName();
            $channelcode[$key]['logo'] = $channel->getLogoUrl();
            $channelcode[$key]['hot'] = $channel->getHot();
        }
        return $channelcode;
    }

    /**
     * 某频道按天的节目列表
     * @author lizhi
     * @param array $argc
     * @param int $args[0] 节目的channel_code
     * @param sring $args[1] 时间表示
     * @return array | void
     */
    public function getWeekByProvinceList(array $args){
        $this->channel_code = isset($args[0]) ? $args[0] : 0;
        $this->date = isset($args[1]) ? $args[1] : date('Y-m-d');
        //$this->channel = Doctrine::getTable('Channel')->findOneByCode($this->channel_code);
        $mongo = sfContext::getInstance()->getMondongo();
        $programRes = $mongo->getRepository("program");
        //$Program_lists = $programRes->getProgramByDateAndChannelCode($this->date, $this->channel_code);
        $Program_lists = $programRes->getDayPrograms($this->channel_code, $this->date);
        if(empty($Program_lists)) return false;
        $total = count($Program_lists);
        $program_list = array();
        foreach($Program_lists as $key=> $Program){
            $program_list[$key]['name'] = $Program->getName();
            $program_list[$key]['time'] = $Program->getTime();
            $program_list[$key]['date'] = $Program->getDate();
            if($Program->getEndTime()!=""){
                $program_list[$key]['end_time'] = date("Y-m-d H:i:s",$Program->getEndTime()->getTimestamp());
            }
            $program_list[$key]['tags'] = $Program->getTags();
            if($Program->getWikiId() > 0) {
                $program_list[$key]['wiki_id'] = $Program->getWikiId();
                $program_list[$key]['wiki_cover'] = $Program->getWikiCoverUrl();
                $program_list[$key]['content'] = $Program->getWiki()->getContent();
				 
            }
        }
        return $program_list;
    }
    
    /**
     * 获取今天正在或将要播出的节目
     * @author wangnan
     */
    public function getNowPrograms($args){
        if(empty($args)) return false;
        $mongo = sfContext::getInstance()->getMondongo();
        $channel_info = Doctrine::getTable('Channel')->findOneByCode($args);
		$channel['code'] = $channel_info->getCode();
		$channel['name'] = $channel_info->getName();
		$channel['logo'] = $channel_info->getLogoUrl();
		$channel['hot'] = $channel_info->getHot();
        $program_repo = $mongo->getRepository("Program");
        $now=new MongoDate(time());
        $programs = $program_repo->find(
				        	array(
				        		"query" =>
				                	array(
				                		"channel_code" => $args,
										"end_time" => array('$gte' => $now),
				                      ),
				                'sort' => array('start_time' => 1),
				                'limit' => 5
                      		)
        		);
        if($programs){
        	$total = count($programs);
            $program_item = array();
            foreach($programs as $key=>$program){
                $channel['programs'][$key]['name'] = $program->getName();
                $channel['programs'][$key]['time'] = date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp());
                $channel['programs'][$key]['channel_name'] = $program->getChannelName();
                $channel['programs'][$key]['channel_logo'] = file_url($program->getChannelLogo());
                $channel['programs'][$key]['status'] = $program->getPlayStatus();
                $channel['programs'][$key]['wiki_id'] = $program->getWikiId(); //add wiki id
            }
            return $channel;
        }
        return false;
    }
    /**
     * 获得live tags
     * @author lizhi
     * @return array
     */
    public function getLiveTags(){
        return array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
    }

    /**
     * 按分类正在直播的节目列表
     * @author lizhi
     * @param array $args
     * @param $args[0] 表示地区名称
     * @return array
     */
    public function getLiveList(array $args){
        $tag = isset($args[1]) ? trim($args[1]) : "";
        $order= isset($args[2])?$args[2]:'';  //排序lfc
        $user = sfContext::getInstance()->getUser();
        $request = sfContext::getInstance()->getRequest();
        $type = $request->getParameter('type',"local");//本地，cctv,tv...
        $allProvince = Province::getProvince();
        $location = $request->getParameter('location',$args[0]);//地区
        if($location){
            $province = array_search($location, $allProvince);
            $user->setAttribute('province',  $province);
        }elseif($user->getAttribute('province')){
            $province = $user->getAttribute('province');
        }else{
            $province = $user->getUserProvince();
        }
        //$tv_station = Doctrine::getTable('TvStation')->findOneByCode(md5($province));
        //$local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
        //$channels = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
        //$cctv_channels = Doctrine::getTable('Channel')->findListByType("cctv");
        //$tv_channels = Doctrine::getTable('Channel')->findListByType("tv");
        $channels = Doctrine::getTable('Channel')->getUserChannels('',$province,$order);
/*        foreach ($channels as $channel){
            $channelcode[] = $channel->getCode();
        }*/
        $mongo = sfContext::getInstance()->getMondongo();
        $programRes = $mongo->getRepository('program');
        $total = 0;
//        if(!empty($tag)){
            $program_lists = $programRes->getLiveProgramByTag($tag, $channels);
            if(!empty($program_lists)){
                $program_list = array();
                foreach($program_lists as $key=>$program){
                    if(is_null($program)) continue;
                    $wiki = $program->getWiki();
                    if(empty($wiki)) continue;
                    $program_list[$key]['cover'] = $program->getWikiCoverUrl();
                    $program_list[$key]['namewiki'] = $program->getWikiTitle();
                    $program_list[$key]['name'] = $program->getName();
                    $program_list[$key]['start_time'] = date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp());
                    $program_list[$key]['end_time'] = date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp());
                    $program_list[$key]['status'] = $program->getPlayStatus();
                    $program_list[$key]['channel_code'] = $program->getChannelCode();
                    $program_list[$key]['channel_name'] = $program->getChannelName();
                    $program_list[$key]['channel_logo'] = file_url($program->getChannelLogo());
					$program_list[$key]['channel_hot'] = $program->getChannelHot();
                    $program_list[$key]['tags'] = $program->getTags();
                    $program_list[$key]['wiki_id'] = $program->getWikiId();
					
                    $total++;
                }
                return $program_list;
            }
            return false;
//        }
        return false; // when is empty
    }

    /**
     * 通过$args[0] 传入相应的wiki_id来获得详细的信息
     * @access public
     * @author lizhi
     * @param array $args
     * @param $args[0] 表示wiki_id String
     * @return array | void
     */
   public function getWikiAllInfo($args){ 
	  
        $wiki_id = !empty($args) ? trim($args) : 0;
        if($wiki_id==0) return false;
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRes = $mongo->getRepository('wiki');
        $wikiInfo = $wikiRes->getWikiById($wiki_id);
        if(is_array($wikiInfo)) return false;
        
        $wiki = array();
        $wiki['alias'] = $wikiInfo->getAlias();
        $wiki['content'] = $wikiInfo->getContent();
        $wiki['country'] = $wikiInfo->getCountry();
        $wiki['created_at'] = $wikiInfo->getCreatedAt();
        $wiki['html_cache'] = $wikiInfo->getHtmlCache();
        $wiki['coverurl'] = $wikiInfo->getCoverUrl();
        $wiki['tags'] = $wikiInfo->getTags();
        $wiki['title'] = $wikiInfo->getTitle();
        $wiki['updated_at'] = $wikiInfo->getUpdatedAt();
        $wiki['director'] = $wikiInfo->getDirector(); //导演
        $wiki['writer'] = $wikiInfo->getWriter(); //编剧
        $wiki['starring'] = $wikiInfo->getStarring(); //主演
        $wiki['screen'] = $wikiInfo->getScreenshotUrls(); //剧照
        $wiki['episodes'] = $this->getEpisodes($wikiInfo);
//        $wiki['episodes'] = $wikiInfo->getModel();
        return $wiki;
    }
    
    /**
     * 通过$args[0] 传入频道号和时间来获得相关wiki详细的信息
     * @access public
     * @author lifucang
     * @param array $args
     * @param $args[0] 表示channel_code(频道号) String,$args[1] 表示time：时间,
     * @return array | void
     */
   public function getWikiInfoByChannel($args){ 
        $channel_code=isset($args[0]) ? trim($args[0]) : false;
        $time=isset($args[1]) ? new MongoDate(strtotime($args[1])) : new MongoDate();
        if(!$channel_code) return false;
        if(!$time) return false;
        //根据频道号和时间查询wiki_id
        $mongo = sfContext::getInstance()->getMondongo();
        $programRes =$mongo->getRepository('program');
        $query = array('query' => array(  "wiki_id"=>array('$exists'=>true),"channel_code" => $channel_code,"start_time"=>array('$lt' => $time),"end_time"=>array('$gt' => $time)));
        $program=$programRes->findOne($query);
        if(!$program) return false;
        
        $wiki_id=$program->getWikiId();
        //获取wiki信息
        $wikiRes = $mongo->getRepository('wiki');
        $wikiInfo = $wikiRes->getWikiById($wiki_id);
        if(is_array($wikiInfo)) return false;
        
        $wiki = array();
        $wiki['id'] = $wiki_id;
        $wiki['start_time'] = $program->getStartTime();
        $wiki['end_time'] = $program->getEndTime();
        $wiki['alias'] = $wikiInfo->getAlias();
        $wiki['content'] = $wikiInfo->getContent();
        $wiki['country'] = $wikiInfo->getCountry();
        $wiki['created_at'] = $wikiInfo->getCreatedAt();
        $wiki['html_cache'] = $wikiInfo->getHtmlCache();
        $wiki['coverurl'] = $wikiInfo->getCoverUrl();
        $wiki['tags'] = $wikiInfo->getTags();
        $wiki['title'] = $wikiInfo->getTitle();
        $wiki['updated_at'] = $wikiInfo->getUpdatedAt();
        $wiki['director'] = $wikiInfo->getDirector(); //导演
        $wiki['writer'] = $wikiInfo->getWriter(); //编剧
        $wiki['starring'] = $wikiInfo->getStarring(); //主演
        $wiki['screen'] = $wikiInfo->getScreenshotUrls(); //剧照
        $wiki['episodes'] = $this->getEpisodes($wikiInfo);
//        $wiki['episodes'] = $wikiInfo->getModel();
        return $wiki;
    }
    /**
     * 指定节目的详细播放情况
     * param $args wiki_id
     */
    public function programDetail($args){
      $this->wiki_id = !empty($args) ? $args : NULL;
      $mongo = sfContext::getInstance()->getMondongo();
      $wiki_repository = $mongo->getRepository('Wiki');
      $wiki = $wiki_repository->getWikiById($this->wiki_id);
      if(empty ($wiki)) return false;
      if($wiki->getModel() == 'actor') {
          $program_repository = $mongo->getRepository('Program');
          $programs = $program_repository->getUnPlayedProgramByWikiId($wiki->getId());

          $this->related_programs = array();
          if($programs) {
          	  $i=0;
          	  $date='';          	
              foreach($programs as $key => $program) {
              	  if($date != $program->getDate())
              	  $i=0;              	
                  //$this->related_programs[$program->getDate()][$key][] = $program;
                  $this->related_programs[$program->getDate()][$i]['name'] = $program->getName();
                  $this->related_programs[$program->getDate()][$i]['channel_name'] = $program->getChannelName();
                  $this->related_programs[$program->getDate()][$i]['time'] = $program->getTime();
                  if($program->getEndTime())
                  $this->related_programs[$program->getDate()][$i]['end_time'] = date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp());
                  else
                  $this->related_programs[$program->getDate()][$i]['end_time'] ='';
                  $this->related_programs[$program->getDate()][$i]['week'] = $program->getWeekChineseName();
                  $this->related_programs[$program->getDate()][$i]['tags'] = $program->getTags();
                  $this->related_programs['cover'] = $program->getWikiCoverUrl();
                  $this->related_programs['wiki_id'] = $program->getWikiId();
                  $this->related_programs['wiki_name'] = $wiki->getTitle(); 
                  $i++;
                  $date=$program->getDate();                  
              }
          }
          return $this->related_programs;
      }

      //处理电视剧类型EPG数据
      if($wiki->getModel() == 'teleplay') {
            //return $wiki->getWeekRelatedPrograms();
            
          $week_num = date('N');
          if($week_num == 0) {
              $week_num = 7;
          }
          $date_from = date('Y-m-d', strtotime('-' . ($week_num - 1) . ' day'));
          $date_end = date('Y-m-d', strtotime('+' . (7 - $week_num) . ' day'));
          $program_repository = $mongo->getRepository('Program');
          $programs = $program_repository->getCustomDateProgramByWikiId($wiki->getId(), $date_from, $date_end);
          $this->related_programs = array();
          if($programs) {
          	  $i=0;
          	  $date='';
              foreach($programs as $key => $program) {
              	  if($date != $program->getDate())
              	  $i=0;
                  //$this->related_programs[$program->getDate()][$key][] = $program;
                  $this->related_programs[$program->getDate()][$i]['name'] = $program->getName();
                  $this->related_programs[$program->getDate()][$i]['channel_name'] = $program->getChannelName();
                  $this->related_programs[$program->getDate()][$i]['time'] = $program->getTime();
                  if($program->getEndTime())
                  $this->related_programs[$program->getDate()][$i]['end_time'] = date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp());
                  else
                  $this->related_programs[$program->getDate()][$i]['end_time'] = '';
                  $this->related_programs[$program->getDate()][$i]['week'] = $program->getWeekChineseName();
                  $this->related_programs[$program->getDate()][$i]['date'] = $program->getDate();
                  $this->related_programs['cover'] = $program->getWikiCoverUrl();
                  $this->related_programs['wiki_id'] = $program->getWikiId();
                  $this->related_programs['wiki_name'] = $wiki->getTitle();
                  $i++;
                  $date=$program->getDate();
              }
          }
          return $this->related_programs;
       }
       if($wiki->getModel() == 'television'){
        $program_repository = $mongo->getRepository('Program');
        $wikiMetaRepos = $mongo->getRepository('WikiMeta');
        $programs = $program_repository->getUnPlayedProgramByWikiId($this->wiki_id);
        $this->related_programs = array();
        if($programs) {
          foreach($programs as $key=>$program) {
              $this->related_programs[$key][] = $program;
              $this->related_programs[$key]['name'] = $program->getName();
              $this->related_programs[$key]['channel_name'] = $program->getChannelName();
              $this->related_programs[$key]['time'] = $program->getTime();
              if($program->getEndTime())
              $this->related_programs[$key]['end_time'] = date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp());
              else
              $this->related_programs[$key]['end_time'] = '';
              $this->related_programs[$key]['week'] = $program->getWeekChineseName();
              $this->related_programs[$key]['date'] = $program->getDate();
              $this->related_programs['cover'] = $program->getWikiCoverUrl();
              $this->related_programs['wiki_id'] = $program->getWikiId();
              $this->related_programs['wiki_name'] = $wiki->getTitle();
          }
        }
        
        return $this->related_programs;
       }
       if($wiki->getModel() =="film") {
        $program_repository = $mongo->getRepository('Program');
        $programs = $program_repository->getUnPlayedProgramByWikiId($wiki->getId());
        $this->related_programs = array();
        if($programs) {
          foreach($programs as $key=>$program) {
              $this->related_programs[$key][] = $program;
              $this->related_programs[$key]['name'] = $program->getName();
              $this->related_programs[$key]['channel_name'] = $program->getChannelName();
              $this->related_programs[$key]['time'] = $program->getTime();
              if($program->getEndTime()) 
              $this->related_programs[$key]['end_time'] = date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp());
              else
              $this->related_programs[$key]['end_time'] = '';
              $this->related_programs[$key]['week'] = $program->getWeekChineseName();
              $this->related_programs[$key]['date'] = $program->getDate();
              $this->related_programs['cover'] = $program->getWikiCoverUrl();
              $this->related_programs['wiki_id'] = $program->getWikiId();
              $this->related_programs['wiki_name'] = $wiki->getTitle();
          }
          return $this->related_programs;
        }
       }
       return false;
    }

    /**
     * 推荐的影视电视剧
     * @author wangnan 
     */
    public function recommendVideo(){
        $mongo = sfContext::getInstance()->getMondongo();
        $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
        $recommends = $wiki_recommend_repository->getRandWiki();
        $total = count($recommends);
        $recommend_item = array();
        if($recommends){
            foreach($recommends as $key =>$recommend){
                $recommend_item[$key]['wiki_id'] = $recommend->getWikiId();
                $recommend_item[$key]['cover'] = $recommend->getWiki()->getCoverUrl();
                $recommend_item[$key]['name'] = $recommend->getWiki()->getTitle();
                $recommend_item[$key]['tags'] = $recommend->getTags();
            }
            return $recommend_item;
        }
        return FALSE;
    }
    
    /**
     * 用户收看信息上报
     * @param $args[0] userid  
     * @param $args[1] channel  
     * @author lifucang
     * @return int(0:不存在该频道 1:无该用户，保存记录 2：有该用户查看该频道记录，更新 3:有该用户但没有查看该频道记录，添加并更新该用户其他频道活动状态)
     */
    public function postUserLiving(array $args){
        
        $userid=(int)$args[0];
        $channel=(string)$args[1];
        //判断是否存在该频道
        $arrchannel = Doctrine::getTable('Channel')->createQuery()
            ->where('code = ?', $channel)
            ->orWhere('name = ?', $channel)
            ->fetchOne();
        //return($arrchannel->getCode());
        if($arrchannel){
            $channel_code=$arrchannel->getCode();
        }else{
            //return '不存在该频道';
            return false;
        }
        
        //是否有该用户记录
        $userliving = Doctrine::getTable('UserLiving')->createQuery()
            ->where('user_id = ?', $userid)
            ->fetchOne();
        if ($userliving) {
            //是否有该用户访问该频道记录
            $userlivinga = Doctrine::getTable('UserLiving')->createQuery()
                ->where('user_id = ?', $userid)
                ->andWhere('channel = ?', $channel_code)
                ->fetchOne();
            if($userlivinga){
                $userlivinga->setIsliving(1);
                $userlivinga->setUpdatedAt(date('Y-m-d H:i:s'));
                $userlivinga->save();   
                //$info='有该用户查看该频道记录，更新';  
                $info=2;           
            }else{
                //更新该用户其他频道活动标志为0
                $q = Doctrine_Query::create() 
                         ->update('UserLiving') 
                         ->set('isliving=?',0) 
                         ->where('user_id = ?', $userid); 
                $numrows = $q->execute(); 
                //插入该频道记录
                $living=new UserLiving();  //实例化类后调用
                $living->setUserId($userid);
                $living->setChannel($channel_code);
                $living->setCreatedAt(date('Y-m-d H:i:s'));
                $living->setUpdatedAt(date('Y-m-d H:i:s'));
                $living->setIsliving(1);
                $living->save();
                //$info='无该用户查看该频道记录，添加并更新该用户其他频道活动状态';      
                $info=3;             
            } 
        } else {
            $living=new UserLiving();  //实例化类后调用
            $living->setUserId($userid);
            $living->setChannel($channel_code);
            $living->setCreatedAt(date('Y-m-d H:i:s'));
            $living->setUpdatedAt(date('Y-m-d H:i:s'));
            $living->setIsliving(1);
            $living->save();
            //$info='无该用户，保存记录';
            $info=1;
        }
        return $info;
    }    
    
    /**
    * 获得服务器时间
    * @author lizhi
    */
    public function getServerTime(){
     return time();
    }
    /**
    * 根据wiki_id获取分集剧情
    * @param string $wiki_id
    * @author wangnan
    */
    public function getMetasByWikiId($wiki_id) {
		$mongo = sfContext::getInstance()->getMondongo();
        $wiki_meta = $mongo->getRepository('WikiMeta');
        $result=$wiki_meta->getMetasByWikiId($wiki_id);
        $wiki_meta = array();
        if($result)
        {
        	$total = count($result);
			foreach($result as $k=>$meta)
			{
	            $wiki_meta[$k]['wiki_id'] = $wiki_id;
	            $wiki_meta[$k]['mark'] = $meta->getMark();
	            $wiki_meta[$k]['title'] = $meta->getTitle();
	            $wiki_meta[$k]['content'] = $meta->getContent();
			}
			return $wiki_meta;
	    }
    	return FALSE;
    }
    /**
     * 搜索功能
     * @param array $args
     * @param  $args[0]搜索关键字
     * @param  $args[1]页码
     * @author wangnan
     */
    public function search(array $args)
    {
        $total = 0;
        $limit = 10;
        $page = intval($args[1])? intval($args[1]) : 1;
        $keyWord = $args[0];
        if($args[2])
        {
        	$keyWord = '';
        	$fields = explode('+',$args[2]);
        	foreach($fields as $field)
        	{
          		$keyWord .= $field.':'.$args[0].' OR ';
        	}
        }
		$keyWord = preg_replace("/OR $/", "",  $keyWord);
        
    	$mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('Wiki');
        $offset = ($page - 1) * $limit;
        $result = $wikiRepository->xun_search($keyWord,$total,$offset,$limit,null,1);
        $result_num = $wikiRepository->xun_search($keyWord,$total,0,999999,null,1);
		$item = array();
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('GetFileUrl', 'Asset'));
        if($result)
        {
			foreach($result as $key=>$wikiInfo)
			{
				$item[$key]['name'] = $wikiInfo->getTitle();
				$item[$key]['channel_name'] = $wikiInfo->getChannel();
				$item[$key]['channel_code'] = $wikiInfo->getChannelCode();
				$item[$key]['time'] = $wikiInfo->getPlayTime();
				$item[$key]['week'] = $wikiInfo->getWeekChineseName();
				$item[$key]['day'] = $wikiInfo->getDate();
				$item[$key]['cover'] = thumb_url($wikiInfo->getCover(), 100, 150);
				$item[$key]['wiki_id'] = $wikiInfo->getId()->__toString();
				
			}
			$item['search_name'] =  $args[0];
			$item['total'] = count($result_num);
			$item['total_page'] = (int)ceil(count($result_num) / 10);
			return $item; 
        }
		return FALSE;
    }
     
}
