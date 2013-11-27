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
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->channel_id = 1;
        $this->date = date('Y-m-d', strtotime("2010-12-8 12:00:00"));
        $this->week = date('w', strtotime("2010-12-8 12:00:00"));
    }

    /**
     * 直播节目单
     * @param sfWebRequest $request 
     */
    public function executeLive(sfWebRequest $request) {
        
    }

    /**
     * 取得 tv_station_id 下的所有频道
     * @param sfWebRequest $request
     * @return
     */
    public function executeAjax_get_channels(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $this->tv_station_id = $request->getParameter('tv_station_id', 1);
            $this->channels = Doctrine::getTable('Channel')->getChannlesWithTvStation($this->tv_station_id);
        }
    }

    /**
     * 取得一周时间列表
     * @param sfWebRequest $request 
     */
    public function executeAjax_get_weekdays(sfWebRequest $request) {
        if (!$request->isXmlHttpRequest()) {
            return $this->renderText('none.');
        }
    }

    /**
     * 取数据
     */
    public function executeAjax_get_datas(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest()) {
            $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
            
            $this->pager = $request->getParameter('data_name',null);
            //if(date('H') > 12 && $this->pager=='vod2') $this->pager='vod3';
        }
    }

}
