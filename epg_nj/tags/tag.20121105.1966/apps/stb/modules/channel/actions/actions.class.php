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
	  	$tvName = $request->getParameter('tvName','CCTV-1');
	  	$channel_code = Doctrine::getTable('Channel')->getNameByCode($tvName);
        //获得节目列表
        $mongo = $this->getMondongo();
    	$ProgramRepository = $mongo->getRepository('Program');
    	$programs = $ProgramRepository->getDayPrograms($channel_code,$date);
    	//当前正在播放的节目
    	//$program_now = $ProgramRepository->getLiveProgramByCode($channel_code);  
        foreach($programs as $key =>$program)
        {
            $wiki_info = $program->getWiki();
            //$program['name'] = mb_strcut($program['name'],0,36,'utf-8');
			$result[$key] = array(
				'name' => $program['name'],
				'time' => $program['time'],
				'startTime' => date("Y/m/d H:i:s",$program['start_time']->getTimestamp()),
				'endTime' => date("Y/m/d H:i:s",$program['end_time']->getTimestamp()),
				'channelCode' => $program['channel_code']
//					'wiki_id' => $program['wiki_id'],
//					'wiki_cover' => file_url($wiki_info['cover']),
//					'tags' => $wiki_info['tags'],
//					'wiki_slug' => $wiki_info['slug'],
           );
		}
		return $this->renderText(json_encode($result));
	}
	/**
	 * ajax获取台标
	 * @param sfWebRequest $request
	 */
	public function executeGetLogoUrl(sfWebRequest $request) {
		$tvName = $request->getParameter('tvName','CCTV-1');
		$channel = Doctrine::getTable('Channel')->findOneByName($tvName);
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('GetFileUrl','Asset'));
		return $this->renderText(json_encode(array('url'=>thumb_url($channel->getLogo(),80,43))));
		//return $this->renderText(json_encode(array('url'=>'http://image.epg.huan.tv/38/537/4319/1293431953738.png')));
	}
	/**
	 * 根据条件获取电视台
	 * @param sfWebRequest $request
	 */
	public function executeGetChannelsByType(sfWebRequest $request) {
		$type = $request -> getParameter('type');
		
		$data = array();
		
		switch ($type) {
			case 'cctv'://获取央视
				$data = Doctrine::getTable('Channel')->getCCTVChannelsGd();
			break;
			case 'local'://获取本地频道
				$data = Doctrine::getTable('Channel')->getLocalChannelsGd();
			break;
			case 'othor'://获取外省频道
				$data = Doctrine::getTable('Channel')->getProvinceTvChannelsGd();
			break;
			case 'pro': //获取专业频道
			break;
			case 'hd': //获取高清频道
			break;
			case 'all'://获取所有频道
				$data = Doctrine::getTable('Channel')->getChannels();
			break;
		}
		
		if (!empty($data)){
			foreach ($data as $value) {
				$dataArray[]=array(
					'id'=>$value->getID(),
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
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('GetFileUrl','Asset'));
		if ($channel_name){
			$channelCode = Doctrine::getTable('Channel')->getNameByCode($channel_name);
			$wikis = Doctrine::getTable('ChannelRecommend')->getWikis($channelCode,2);
			if ($wikis){
				foreach ($wikis as $wiki) {
					$des = $wiki->getRemark();
					$des = mb_strcut($des, 0, 27,'utf-8');
					$dataArray[] = array(
						'title' => $wiki->getTitle(),
						'cover' => thumb_url($wiki->getPic(),200,135),
						'des'   => $des,
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
}
