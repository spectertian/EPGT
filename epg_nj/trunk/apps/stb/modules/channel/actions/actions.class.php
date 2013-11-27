<?php
/**
 * channel actions.
 *
 * @package    epg2.0
 * @subpackage channel
 * @author     Huan Tek
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
class channelActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
		$this->channel=$request->getParameter('channel');
	}
	/**
	 * Executes list action
	 * @author majun
	 */
	public function executeList(sfWebRequest $request)
	{
        
	}	  
	/**
	 * ajax 获得该天的节目数据
	 * @param sfWebRequest $request
	 */
	public function executeGetProgram(sfWebRequest $request)
	{
		$date = $request->getParameter('date');
	  	$tvName = $request->getParameter('tvName');
	  	$userId = $request -> getParameter('userid');
        
        $mongo = $this->getMondongo();
        $channel_code=$mongo->getRepository('SpService')->getCodeByname($tvName);
        if(!$channel_code) $this->forward404();
        
        //先从缓存查找是否有该频道该日数据
        $memcache = tvCache::getInstance(); 
        $programDate=$memcache->get("channel_".$channel_code."_".$date);
        if($programDate)
            return $this->renderText($programDate);
            
    	$ProgramRepository = $mongo->getRepository('Program');
    	$programs = $ProgramRepository->getDayPrograms($channel_code,$date); 
    	if ($programs){
	        foreach($programs as $key =>$program)
	        {
	            $wiki_info = $program->getWiki();
				$result[$key] = array(
					'name' => $program['name'],
					'time' => $program['time'],
					'startTime' => date("Y/m/d H:i:s",$program['start_time']->getTimestamp()),
					'endTime' => date("Y/m/d H:i:s",$program['end_time']->getTimestamp()),
					'channelCode' => $program['channel_code'],
					//'ordered' => ($orderArr && $program['start_time']->getTimestamp() == $orderArr[$program['name']])?'1':'0'
	            );
			}
            $programDate=json_encode($result);
            if($date==date("Y-m-d")){
                $memcache->set("channel_".$channel_code."_".$date,$programDate,3600);  //1小时
            }
			return $this->renderText($programDate);
    	}else {
			$this->forward404();    	
    	}
	}

	/**
	 * @todo ajax 获取CPG
	 * @author gaobo 2012-12-20
	 * 
	 * @param sfWebRequest $request
	 */
	public function executeGetCpgProgram(sfWebRequest $request)
	{
        $date = $request->getParameter('date');
        $datea = date("Y-m-d",strtotime("-6 days"));
        if($date < $datea){
          return null;
        }
        $tvName = $request->getParameter('tvName');
        $mongo = $this->getMondongo();
        $channel_code=$mongo->getRepository('SpService')->getCodeByname($tvName);
        if(!$channel_code) $this->forward404();
        
        $CpgRepository = $mongo->getRepository('Cpg');
        $programs = $CpgRepository->getCpgDayPrograms($channel_code,$date);
        if ($programs){
            foreach($programs as $key =>$program){
                $result[$key] = array(
                  'name' => $program['program_name'],
                  'time' => date("H:i",$program['start_time']->getTimestamp()),
                  'startTime' => date("Y/m/d H:i:s",$program['start_time']->getTimestamp()),
                  'endTime' => date("Y/m/d H:i:s",$program['end_time']->getTimestamp()),
                  'channelCode' => $program['channel_code'],
                  'contentid'=> $program['content_id']
                );
            }
            return $this->renderText(json_encode($result));
        }else{
            $this->forward404();
        }
	}
	
	/**
	 * ajax获取台标
	 * @param sfWebRequest $request
	 */
	public function executeGetLogoUrl(sfWebRequest $request) {
		$tvName = $request->getParameter('tvName','CCTV-1');
        $mongo = $this->getMondongo();
        $channel = $mongo->getRepository('SpService')->getSpByname($tvName);
        if(!$channel) $this->forward404();
		
		if ($channel->getChannelLogo()){
			return $this->renderText(json_encode(array('url'=>thumb_url($channel->getChannelLogo(),93,50))));
		}else {
		    return $this->renderText(json_encode(array('url'=>'')));
		}
	}
	/**
	 * 根据条件获取电视台
	 * @param sfWebRequest $request
	 */
	public function executeGetChannelsByType(sfWebRequest $request) 
    {
		$type = $request -> getParameter('type');
		$type = ($type=='all')?'':$type;
        $mongo = $this->getMondongo();
        $spServiceRepository = $mongo->getRepository('SpService');
		$data =$spServiceRepository->getServicesByTag($type);
		if (!empty($data)){
			foreach ($data as $value) {
				$dataArray[]=array(
					'logicNumber'=>$value->getLogicNumber(),
					'name'=>$value->getName()
				);
			}
			return $this->renderText(json_encode($dataArray));
		}else {
			$this->forward404();
		}
	}
	
	/**
	 * 根据电视台名获取推荐维基
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	public function executeGetRecommendWiki(sfWebRequest $request) {
		$channel_name = $request-> getParameter('tvName','CCTV-1');
        $mongo = $this->getMondongo();
		if ($channel_name){
            $channel_code=$mongo->getRepository('SpService')->getCodeByname($channel_name);
            $wiki_repository = $mongo->getRepository('Wiki');
            if(!$channel_code) $this->forward404();
			$wikis = Doctrine::getTable('ChannelRecommend')->getWikis($channel_code,2);
			if ($wikis){
				foreach ($wikis as $wiki) {
					$des = $wiki->getRemark();
					$des = mb_strcut($des, 0, 27,'utf-8');
                    $wiki_id=$wiki->getWikiId();
                    $slug=$wiki_repository->getSlugById($wiki_id);
					$dataArray[] = array(
						'title' => $wiki->getTitle(),
						'cover' => thumb_url($wiki->getPic(),200,135),
						'des'   => $des,
                        'slug'  => $slug,
					);
				}
				return $this->renderText(json_encode($dataArray));
			}else {
				$this->forward404();
			}
		}else {
			$this->forward404();
		}
		
	}
    
    /**
	 * 根据机顶盒频道添加sp_service表，并根据channel表补齐code值，在页面中通过按0键执行该方法
	 * @param unknown_type $param
	 */
	public function executeInjectSpService(sfWebRequest $request) 
    {
        if ($request->getMethod() == 'POST') {
           $json_str = $request->getParameter('json_str');
           $mongo = $this->getMondongo();
           $spServiceRepository = $mongo->getRepository('SpService');
           if($json_str) {
                $channels = json_decode($json_str);
                $count = $success = 0;
                foreach($channels as $channel) {
                    if($channel->name != "Unknown Service") {
                        $sp_service = $spServiceRepository->findOne(array("query" => array("sp_code" => "njbd", "name" => $channel->name)));
                        if(!$sp_service) {
                            $sp_service = new SpService();
                            $sp_service->setSpCode('njbd');                    
                            $sp_service->setName($channel->name);
                        }                        
                        $sp_service->setServiceId($channel->serviceId);
                        $sp_service->setFrequency($channel->frequency);
                        $sp_service->setSymbolRate($channel->symbolRate);
                        $sp_service->setModulation($channel->modulation);
                        $sp_service->setOnId($channel->onId);
                        $sp_service->setTsId($channel->tsId);
                        $sp_service->setLogicNumber($channel->logicNumber);
                        $sp_service->setLocation($channel->location);
                        //$sp_service->setChannelCode(null);
                        //$sp_service->setChannelLogo(null);
                        
                        
                        //从channel表里获取code和logo
/*
                        $mychannel = Doctrine_Core::getTable("channel")->getChannleByName($channel->name);
                        if($mychannel){
                            if($mychannel[0]->getCode()){
                                $sp_service->setChannelCode($mychannel[0]->getCode());
                                $sp_service->setChannelLogo($mychannel[0]->getLogo());
                            }
                            
//                            $type=$mychannel[0]->getType();
//                            if($type==''||$type==null)
//                                $type='local';
//                            $sp_service->setTags(array($type));  
                            
                        }
*/
                        //从channel表里获取code和logo，只根据memo字段获取
                        $mychannel = Doctrine_Core::getTable("channel")->findOneByMemo($channel->name);
                        if($mychannel){
                            if($mychannel->getCode()){
                                $sp_service->setChannelCode($mychannel->getCode());
                                $sp_service->setChannelLogo($mychannel->getLogo());
                            }
                        }
                        if($sp_service->save()) {
                            $success ++;
                        }
                    }
                    $count ++;
                }
                return $this->renderText("总共发现".$count."个频道，完成添加".$success."个;");
           }
        }else {
            return sfView::NONE;
        }		
	}

	/**
	 * 查看频道下面是否有节目，测试用
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	public function executeIsProgram(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        $channels=$mongo->getRepository('SpService')->getServicesByTag();
        $dates=array();
        echo "<table border=1>";
        echo "<tr><td>频道名称</td>";
        for($days = 0; $days < 7 ; $days ++) {
            $dates[$days] = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));
            echo "<td>",$dates[$days],"</td>";
        }        
        echo "</tr>";
        foreach($channels as $channel){
            $code=$channel->getChannelCode();
            echo "<tr><td>",$channel->getName(),"</td>";
            foreach($dates as $date) {
                $programNum=$programRes->countDayPrograms($code,$date);
                if($programNum==0){
                    echo "<td bgcolor='#ff0000'>$programNum</td>";
                }else{
                    echo "<td>$programNum</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        return sfView::NONE;
	}
}
