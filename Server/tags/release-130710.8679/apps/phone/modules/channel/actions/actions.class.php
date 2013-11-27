<?php

/**
 * channel actions.
 *
 * @package    epg2.0
 * @subpackage channel
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
		$type = $request->getParameter('type','cctv');
		switch($type) {
			case "cctv":
				$this->channels = Doctrine::getTable('channel')->getWeiShiChannels();
				break;
			case "tv":
				$this->channels = Doctrine::getTable('channel')->getWeiShiChannels();
				break;
			case "local":
				$this->channels = Doctrine::getTable('channel')->getWeiShiChannels();
				break;		
		}
	}
	
	/**
	 * 按频道code 开始时间$starttime,结束时间$endtime 获取节目
	 * @param sfWebRequest $request
	 * return json
	 * author wangnan
	 */
	public function executeProgram(sfWebRequest $request)
	{
		if ($request->isXmlHttpRequest()) 
		{
			$channel_code = $request->getParameter('code');
            $day=$request->getParameter('day',date('Y-m-d'));
			$starttime = date('Y-m-d 00:00:00',strtotime($day));
            $endtime = date('Y-m-d 23:59:59',strtotime($day));
            
	    	$mongo = $this->getMondongo();
	    	$ProgramRepository = $mongo->getRepository('Program');
	    	$programs = $ProgramRepository->getProgramsByCode($channel_code,$starttime,$endtime);
	    	
            $program_now = $ProgramRepository->getLiveProgramByCode($channel_code);  //当前正在播放的节目
	        $WikiRepository = $mongo->getRepository('Wiki');
	        foreach($programs as $key =>$program)
	        {
	            $wiki_info = $WikiRepository->findOneById(new MongoId($program['wiki_id']));
                
                $jialiang=0;
                if($program_now){
    	            if($program_now->getWikiId()==$program['wiki_id']&&$program_now->getTime()==$program['time']){
    	               $jialiang=1;
    	            }else{
    	               $jialiang=0;
    	            }
                }
				$result[$key] = array(
					'name' => $program['name'],
					'time' => $program['time'],
//					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
//					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
//					'wiki_id' => $program['wiki_id'],
//					'wiki_cover' => file_url($wiki_info['cover']),
//					'tags' => $wiki_info['tags'],
					'wiki_slug' => $wiki_info['slug'],
                    'jialiang'=>$jialiang
	                );
			}
			return $this->renderText(json_encode($result));
		}
		else 
		{
			$this->forward404();
		}
		
	}	
	public function executeShowProgram(sfWebRequest $request)
	{
        if ($request->isXmlHttpRequest()) {
            $channel_code = $request->getParameter('channel_code');
    		$mongo = $this->getMondongo();
            $programRes = $mongo->getRepository('program');
            $this->program_now = $programRes->getLiveProgramByCode($channel_code);
            return $this->renderPartial('program', array('program_now'=>$this->program_now));      
        } else {
            $this->forward404();
        }   
	}      
}
