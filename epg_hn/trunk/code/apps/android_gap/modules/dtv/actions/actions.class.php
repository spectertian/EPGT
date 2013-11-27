<?php
/**
 * default actions.
 *
 * @package    epg
 * @subpackage default
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dtvActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->live_tags = array('dsj'=>'电视剧','dy'=>'电影','ty'=>'体育','yl'=>'娱乐','se'=>'少儿','kj'=>'科教','cj'=>'财经','zh'=>'综合');
	}  

	public function executeChannel(sfWebRequest $request)
	{
		$this->channels = Doctrine::getTable('channel')->getWeiShiChannels();
		
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
			
	    	$mongo = $this->getMondongo();
	    	$ProgramRepository = $mongo->getRepository('Program');
	    	$programs = $ProgramRepository->getProgramsByCode($channel_code,$starttime,$endtime);
	    	
	        $WikiRepository = $mongo->getRepository('Wiki');
	        foreach($programs as $key =>$program)
	        {
	            $wiki_info = $WikiRepository->findOneById(new MongoId($program['wiki_id']));
	            
				$result[$key] = array(
					'name' => $program['name'],
					'time' => $program['time'],
//					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
//					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
//					'wiki_id' => $program['wiki_id'],
//					'wiki_cover' => file_url($wiki_info['cover']),
//					'tags' => $wiki_info['tags'],
					'wiki_slug' => $wiki_info['slug'],
	                   );
			}
			return $this->renderText(json_encode($result));
		}
		else 
		{
			$this->forward404();
		}
		
	}	
}
