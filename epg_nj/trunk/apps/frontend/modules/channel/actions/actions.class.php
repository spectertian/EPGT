<?php

/**
 * channel actions.
 *
 * @package    epg
 * @subpackage channel
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelActions extends sfActions {
    
    /**
     * @param sfWebRequest $request
     * 获取正在直播即将播出/本地/cctv/tv的节目内容
     */
    public function executeIndex(sfWebRequest $request) {
		$this->getResponse()->setTitle("节目单 - 我爱电视");
        $this->active = 'now';
        $this->type = $request->getParameter('type',"all");//本地，cctv,tv...
        $this->allProvince = Province::getProvince();
        $this->location = $request->getParameter('location',"");//地区
        $this->mode = $request->getParameter('mode',"tile");//切换模板(list,tile)
        $this->tags = array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
        $this->programTile = array();
        $this->top_active = ($this->type) ? $this->type: 'all';
        
        //地区判断-取得电视台
        if($this->location){
            $this->province = array_search($this->location, $this->allProvince);
            $this->getUser()->setAttribute('province',  $this->province);
        }elseif($this->getUser()->getAttribute('province')){
            $this->province = $this->getUser()->getAttribute('province');
        }else{
            $this->province = $this->getUser()->getUserProvince();
        }

        switch($this->type) {
            case 'cctv':
                    $this->channels = Doctrine::getTable('Channel')->findListByType("cctv");
                break;
            case 'tv':
                    $this->channels = Doctrine::getTable('Channel')->findListByType('tv');
                break;
            case 'all':
                    $this->channels = Doctrine::getTable('Channel')->getUserChannels('',$this->province);
                break;
            case 'local':
                    $tv_station = Doctrine::getTable('TvStation')->findOneByCode(md5($this->province));
                    $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
                    $this->channels = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
                break;
        }
        
        //呈现方式
        if($this->mode == "tile"){
            foreach ( $this->channels as $channel) $channelcodes[] = $channel->getCode();
            $mongo = $this->getMondongo();
            $program_repo = $mongo->getRepository("Program");
            $programs = $program_repo->getLivePrograms($channelcodes);
            if(!empty($programs)){
                  foreach ($programs as $program) {
                    if (!$program) continue;
                    $wiki = $program->getWiki();
                    if (!$wiki) continue;
                    switch (true) {
                        case in_array('电视剧', $wiki->getTags()) :
                             $this->programTile['电视剧'][] = $program;
                             break;
                        case in_array('电影', $wiki->getTags()) :
                             $this->programTile['电影'][] = $program;
                             break;
                        case in_array('体育', $wiki->getTags()) :
                             $this->programTile['体育'][] = $program;
                             break;
                        case in_array('娱乐', $wiki->getTags()) :
                             $this->programTile['娱乐'][] = $program;
                             break;
                        case in_array('少儿', $wiki->getTags()) :
                             $this->programTile['少儿'][] = $program;
                             break;
                        case in_array('科教', $wiki->getTags()) :
                             $this->programTile['科教'][] = $program;
                             break;
                        case in_array('财经', $wiki->getTags()) :
                             $this->programTile['财经'][] = $program;
                             break;
                        case in_array('综合', $wiki->getTags()) :
                             $this->programTile['综合'][] = $program;
                             break;
                         default:
                             //..
                    }
                }
            }
            
            $this->setTemplate("index_tile");    
	}
    }
    
    /**
     * 频道节目列表页
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeShow(sfWebRequest $request) {
        $this->channel_id = $request->getParameter('id', 0);
        $this->date = $request->getParameter('date', date('Y-m-d', time()));
        if (strcmp(date('Y-m-d', strtotime($this->date)), '1970-01-01') == 0) $this->date = date('Y-m-d', time());
        $this->channel = Doctrine::getTable('Channel')->findOneById($this->channel_id);
        $this->forward404Unless($this->channel);
        $this->getResponse()->setTitle(sprintf('%s - 电视节目指南 - 我爱电视', $this->channel->getName()));
        $this->programs = $this->channel->getDayPrograms($this->date);
        $this->programswiki = $this->channel->getDayProgramsWiki($this->date);
        //地区判断-取得电视台
        $this->location = $request->getParameter('location',"");
        $this->allProvince = Province::getProvince();
        if(!empty($this->location)){
            $province = array_search($request->getParameter("location"), $this->allProvince);
        }else{
            $province = $this->getUser()->getUserProvince();
        }
        
        $tv_station = Doctrine::getTable('TvStation')->findOneByCode(md5($province));
        $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
        $this->local_station = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
        $this->cctv_station = Doctrine::getTable('Channel')->findListByType("cctv");
        $this->tv_station = Doctrine::getTable('Channel')->findListByType('tv');
        $this->user_id = $this->getUser()->getAttribute('user_id');
        if(empty($this->user_id)) {
            $this->mytv = null;
        }else{
            $mongo = $this->getMondongo();
            $channelFavoritesRep = $mongo->getRepository('ChannelFavorites');
            $this->mychannelfavorites = $channelFavoritesRep->getChannelByUserId($this->user_id);
            if($this->mychannelfavorites!=NULL){
                foreach($this->mychannelfavorites as $channel) {
                    $channelcode[] = $channel->getChannelCode();
                }
                $this->mytv = Doctrine::getTable('Channel')->findInCodes($channelcode);
            }else{
                $this->mytv = null;
            }
        }
    }

    /**
     * 获取今天、明天、后天的电视剧节目列表
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeTag(sfWebRequest $request) {
       $tags = array(
               '电影' => array(
                    '偶像','喜剧', '爱情', '悬疑', '恐怖', '科幻', '剧情', '武侠', '其它'
               ),
               '电视剧' => array(
                    '偶像', '爱情', '喜剧', '剧情', '神话', '历史', '谍战', '古装', '其它'
               ),
               '娱乐' => array(
                    '明星', '综艺', '交友', '情感', '访谈', '游戏', '搞笑', '选秀', '其它'
               ),
               '少儿' => array(
                   '益智','教育','亲子','动画','动漫', '其它'
               ),
               '科教'  => array(
                   '科学','讲坛','军事','揭秘','社会','自然', '其它'
               ),
               '财经'  => array(
                   '股票','经济','财富','金融','理财', '其它'
               ),
               '体育' => array(
                   '篮球','足球','网球','台球','排球','其它'
               ),
               '综合'  => array(
                   '美食','对话','奇闻','纪录','法制','地方','热点','农业', '其它'
               )
            );

        $this->allProvince = Province::getProvince();
        $this->tag = $request->getParameter('tag', '电视剧');
        $this->location = $request->getParameter('location', null);
        $this->date = $request->getParameter('date', 'today');
        $this->mode = $request->getParameter('mode','tile');
        $this->active = $this->tag;

        if (null == $this->location || !in_array($this->location, $this->allProvince)) {
            $this->location = $this->allProvince[$this->getUser()->getUserProvince()];
        }
        
        if ('tomorrow' == $this->date) {
            $this->datestamp = date('Y-m-d', time()+86400);
        }elseif ('day-after-tomorrow' == $this->date) {
            $this->datestamp = date('Y-m-d', time()+172800);
        }else{
            $this->datestamp = date('Y-m-d', time());
        }
        
        $mongo = $this->getMondongo();
        $wikiPlayRepos = $mongo->getRepository('WikiPlay');
        $this->getResponse()->setTitle($this->tag.' - 节目单');
        $this->province = array_search($this->location, $this->allProvince);
        $this->wikiPlays = $wikiPlayRepos->getWikiPlays($this->tag, $this->datestamp, $this->location);
        
        if (array_key_exists($this->tag, $tags)) {
            if ('tile' == $this->mode) {
                $this->wikiTile = array();
                $this->tags = $tags[$this->tag];
                if (!is_null($this->wikiPlays)) {
                    foreach ($this->wikiPlays as $wikiPlay) {
                        $tagArr = array_intersect ($wikiPlay->getTags(), $tags[$this->tag]);
                        if (! empty($tagArr)) {
                            $this->wikiTile[current($tagArr)][] = $wikiPlay;
                        }else{
                            $this->wikiTile['其它'][] = $wikiPlay;
                        }
                    }
                }
                $this->setTemplate('tag_tile');
            } else {
                $this->setTemplate('tag_list');    
            }
        } else {
            $this->redirect('channel/index');
        }

    }
    
    /**
    * 通过时间来获取当天的节目信息
    * @param sfWebRequest $request
    * @return void
    * @author lizhi
    */
    public function executeDayprograms(sfWebRequest $request) {
        if($request->isXmlHttpRequest()){
            $this->channel_id = $request->getParameter('id', 0);
            if($request->hasParameter('week')){
                $now_week = date("N",time());
                $need_week = $request->getParameter('week',1);
                if($need_week > 7 || $need_week < 0){
                    $need_week = $now_week;
                }
                $are_week = $need_week - $now_week;
                $this->date = date("Y-m-d",  time()+$are_week*86400);
            }else{
                $this->date = date("Y-m-d");
            }
            
            $this->channel = Doctrine::getTable('Channel')->findOneById($this->channel_id);
            $this->programs = $this->channel->getDayPrograms($this->date);
            return $this->renderText(json_encode($this->programs));
        }
    }
    
    /**
    * 频道首页
    * @param sfWebRequest $request
    * @return void
    * @author lizhi
    */
    public function executeChannel_index(sfWebRequest $request) {
        $this->channel_id = $request->getParameter('id', 0);
        $this->location = $request->getParameter('location',"");
        $this->allProvince = Province::getProvince();
        if(!empty($this->location)){
            $this->province = array_search($request->getParameter("location"), $this->allProvince);
        }else{
            $this->province = $this->getUser()->getUserProvince();
        }
        
        $tv_station = Doctrine::getTable('TvStation')->findOneByCode(md5($this->province));
        $this->forward404Unless($tv_station);
        $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
        $this->local_station = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
        $this->cctv_station = Doctrine::getTable('Channel')->findListByType("cctv");
        $this->tv_station = Doctrine::getTable('Channel')->findListByType('tv');
        $this->user_id = $this->getUser()->getAttribute('user_id');
        
        if(empty($this->user_id)) {
            $this->mytv = null;
        }else{
            $mongo = $this->getMondongo();
            $channelFavoritesRep = $mongo->getRepository('ChannelFavorites');
            $this->mychannelfavorites = $channelFavoritesRep->getChannelByUserId($this->user_id);
            foreach($this->mychannelfavorites as $key=> $channel) {
                $channelcode[] = $channel->getChannelCode();
            }
            $this->mytv = Doctrine::getTable('Channel')->findInCodes($channelcode);
        }
    }
    /**
    * 栏目收藏功能
    * @param sfWebRequest $request
    * @return void
    * @author lizhi
    */
    public function executeChannel_favorites(sfWebRequest $request) {
        if(!$this->getUser()->isAuthenticated() ) {
            if($request->isXmlHttpRequest()){
                $this->getUser()->setFlash('error', "您没有用户登录！");
                return $this->renderText(4);
            }
            $this->redirect('user/login');
        }
        $this->user_id = $this->getUser()->getAttribute('user_id');
        $this->channel_id = $request->getParameter('channe_id', 0);
        $mongo = $this->getMondongo();
        $channelFavoritesRep = $mongo->getRepository('ChannelFavorites');
        $channel = $channelFavoritesRep->getOneChannelByUCid($this->user_id, (int)$this->channel_id);
        if($channel==NULL){
            $this->channelOne = Doctrine::getTable('Channel')->findOneById($this->channel_id);
            if($this->channelOne==false){
                if($request->isXmlHttpRequest()){
                    $this->getUser()->setFlash('error', "非法提交数据！");
                    return $this->renderText(1);
                }
            }
            //var_dump($this->channelOne->getCode());exit;
            $channelfavorite = new ChannelFavorites();
            $channelfavorite->setUserId($this->user_id);
            $channelfavorite->setChannelCode($this->channelOne->getCode());
            $channelfavorite->setChannelType($this->channelOne->getType());
            $channelfavorite->setChannelId((int)$this->channel_id);
            $channelfavorite->save();
            if($request->isXmlHttpRequest()){
                $this->getUser()->setFlash('success', "栏目收藏成功！");
                return $this->renderText(2);
            }
        }else{
            if($request->isXmlHttpRequest()){
               $this->getUser()->setFlash('error', "您曾经添加过！");
               return $this->renderText(3);
            }
        }
        return sfView::NONE;
    }
}
