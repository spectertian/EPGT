<?php

/**
 * live actions.
 *
 * @package    epg2.0
 * @subpackage live
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class liveActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->tag = $request->getParameter('tag', '');
        $this->mode = $request->getParameter('mode',"tile");//切换模板(list,tile)
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
        
        //所有
        //$channels = Doctrine::getTable('Channel')->getUserChannels('',$province);
        //本地
        $tv_station = Doctrine::getTable('TvStation')->findOneByCode(md5($province));
        $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
        $channels = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
        
		$mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        $this->program_now = $programRes->getOneLiveProgramByTag($this->tag, $channels);   //当前正在播放的节目	
        
        if($this->tag!=''){ //当天的电视节目
            //$this->program_list = $programRes->getDayLiveProgramByTag($this->tag, $channels,0);  //这样获取的wiki_id有很多重的
            $locationa=$allProvince[$province];  //获取地区代码，如beijing
            $this->datestamp = date('Y-m-d', time());
            $wikiPlayRepos = $mongo->getRepository('WikiPlay');
            $this->wikiPlays = $wikiPlayRepos->getWikiPlays($this->tag, $this->datestamp, $locationa);
            $this->setTemplate('tag');
        }else{               //当前时间正在播放的节目
		    $this->program_list = $programRes->getLiveProgramByTag($this->tag, $channels,0); 	
        }    
	}	

	public function executeShow(sfWebRequest $request)
	{
        if ($request->isXmlHttpRequest()) {
            $id = $request->getParameter('id');
    		$mongo = $this->getMondongo();
            $wikiRes = $mongo->getRepository('wiki');
            $this->wiki = $wikiRes->findOneById(new MongoId($id));   
            //通过wiki得到当前正在播放的节目   
            $programRes = $mongo->getRepository('program');
            $this->program_now = $programRes->getLiveProgramByWiki($id);    
                   
            return $this->renderPartial('wiki', array('wiki' => $this->wiki,'program_now'=>$this->program_now));            
            /*
            $programRes = $mongo->getRepository('program');
            $this->program_now = $programRes->findOneById(new MongoId($id));   //选中播放的栏目
            return $this->renderPartial('wiki', array('program_now' => $program_now));
            */
            
        } else {
            $this->forward404();
        }   
	}      
}
