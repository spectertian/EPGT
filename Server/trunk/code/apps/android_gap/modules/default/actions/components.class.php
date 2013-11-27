<?php
class defaultComponents extends sfComponents {

    public function executeLiveList( sfWebRequest $request ) 
    {
		$mongo = $this->getMondongo();
		//正在播出
		//$tag = $request->getParameter('tag', '');
		$tag = $this->tag ? $this->tag : '';
		$user = $this->getUser();
        $allProvince = Province::getProvince();
        $location = $request->getParameter('location','');//地区
        if($location)
        {
            $province = array_search($location, $allProvince);
            $user->setAttribute('province',  $province);
        }
        elseif($this->getUser()->getAttribute('province'))
        {
            $province = $user->getAttribute('province');
        }
        else
        {
            $province = $user->getUserProvince();
        }
        $channels = Doctrine::getTable('Channel')->getUserChannels('',$province);
        $programRes = $mongo->getRepository('program');
        $total = 0;
        
		$this->program_list = $programRes->getLiveProgramByTag($tag, $channels,20);
/*		if(!empty($program_lists))
		{
			foreach($program_lists as $key=>$program)
			{
				if(is_null($program)) continue;
				$wiki = $program->getWiki();
				if(empty($wiki)) continue;
				$program_list[$key]['channel_logo'] = $program->getChannelLogo();
				$program_list[$key]['namewiki'] = $program->getWikiTitle();
				$program_list[$key]['start_time'] = date("H:i",$program->getStartTime()->getTimestamp());
				$program_list[$key]['end_time'] = date("H:i",$program->getEndTime()->getTimestamp());
				$wiki = $program->getWiki();
				if($wiki)
				{
					$program_list[$key]['wiki_cover'] = $wiki->getCover();
					$program_list[$key]['wiki_slug'] = $wiki->getSlug();
					$program_list[$key]['wiki_id'] = (string)$wiki->getId();					
				}
				$program_list[$key]['name'] = $program->getName();
				$program_list[$key]['status'] = $program->getPlayStatus();
				$program_list[$key]['channel_name'] = $program->getChannelName();
				$program_list[$key]['channel_logo'] = file_url($program->getChannelLogo());
				$program_list[$key]['tags'] = $program->getTags();
				$program_list[$key]['wiki_id'] = $program->getWikiId();
				$total++;
                }
		}*/
    }
    
}