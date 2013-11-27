<?php

/**
 * channel actions.
 *
 * @package    epg
 * @subpackage channel
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelActions extends sfActions
{
    /**
     * 节目表首页
     * @author pjl
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request)
    {
        $code = $request->getGetParameter('code', 'cctv');

        $this->current_navi = ''; //当前导航tab
        $this->channels = array();
        $this->all_province = array_flip(Province::getProvince());
        //处理自定义地区
        $this->customize_province = $this->getUser()->getUserProvince();
   
        if($code == 'cctv' || $code == 'tv') {
            $this->current_navi = $code;
            $this->channels = Doctrine::getTable('Channel')
                    ->createQuery()
                    ->where('type = ?', $code)
                    ->execute();
        } elseif('favorites' == $code){
            $this->current_navi = 'favorites';
            $favorites = file_get_contents('php://input');
            $favorites = explode(',', $favorites);
   
            if (!is_null($favorites)) {
                $this->channels = Doctrine::getTable('Channel')
                            ->createQuery()
                            ->WhereIn('code', $favorites)
                            ->execute();
            } else {
                $this->channels = null;
            }
        } else {
            $this->current_navi = 'customize';
            $tv_station = Doctrine::getTable('TvStation')->findOneByCode($code);
            $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
            $this->channels = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
        }
    }

    /**
     * 其他地区节目表
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeOther(sfWebRequest $request) {
        $province_pinyin = $request->getParameter('province', 'shanghai');
        $all_province = array_flip(Province::getProvince());

        $this->province = $all_province[$province_pinyin];
        
        $code = md5($this->province);
        
        $tv_station = Doctrine::getTable('TvStation')->findOneByCode($code);
        $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
        $this->channels = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
    }

    /**
     * 单个频道页面
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeShow(sfWebRequest $request) {
        if ((false === strpos($request->getReferer(), 'channel/show')) && (false === strpos($request->getReferer(), 'wiki/show'))) {
             $this->getUser()->setAttribute('showback',  $request->getReferer());
        } 

        $this->time = $request->getParameter('time', time());
        $this->code = $request->getParameter('code', 0);
        $this->channel = Doctrine::getTable('Channel')->findOneByCode($this->code);
        $this->redirectUnless( $this->channel, 'channel/index');
        
        $mongo = $this->getMondongo();
        $programRepository = $mongo->getRepository('Program');
        $this->programs = $programRepository->getDayPrograms($this->channel->getCode(), date('Y-m-d',$this->time));
    }

    /**
     * 获取今天、明天、后天的电视剧节目列表
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeTag(sfWebRequest $request) {
        $tags = array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
        $this->tag = urldecode($request->getParameter('tag', '电视剧'));
        $this->date = $request->getParameter('date', 'today');
        $province = $this->getUser()->getUserProvince();
        $provinces = Province::getProvince();
        
        if ('tomorrow' == $this->date) {
            $this->datestamp = date('Y-m-d', time()+86400);
        }elseif ('day-after-tomorrow' == $this->date) {
            $this->datestamp = date('Y-m-d', time()+172800);
        }else{
            $this->datestamp = date('Y-m-d', time());
        }

        if (in_array($this->tag, $tags)) {
            $mongo = $this->getMondongo();
            $wikiPlayRepos = $mongo->getRepository('WikiPlay');
            $this->getResponse()->setTitle($this->tag.' - 节目单');
            $this->wikiPlays = $wikiPlayRepos->getWikiPlays($this->tag, $this->datestamp, $provinces[$province], 0, 50);
        } else {
           $this->redirect('channel/index');
        }
    }
}
