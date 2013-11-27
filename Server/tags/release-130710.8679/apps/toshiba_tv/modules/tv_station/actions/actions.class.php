<?php

/**
 * tv_station actions.
 *
 * @package    epg
 * @subpackage tv_station
 * @author     Mozi Tekz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tv_stationActions extends sfActions {

    /**
     * 查看电视台与其下频道
     * @param sfWebRequest $request
     */
    /*
    public function executeShow(sfWebRequest $request) {
        $this->page = $request->getParameter('page', '1');
        $limit = 3;
        $offset = ($this->page - 1) * $limit;

        $this->type = $request->getParameter('type');
        switch ($this->type) {
            case 'local':
                $city = $this->getUser()->getAttribute('user_city');
                $this->channels = '';
                $provice = $this->getUser()->getAttribute('province');
                $md5 = array(md5($provice), md5($city));
                if (!empty($city)) {
                    $channel_ids = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
                    if (count($channel_ids) == 0) {
                        $this->channels = '';
                    } else {

                        $this->count = Doctrine::getTable('Channel')->total_find_in_tv_staion_id($channel_ids);
                        $this->page_total = ceil($this->count / $limit);
                        if ($this->page > $this->page_total) {
                            $offset = ($this->page_total - 1) * $limit;
                        }
                        $this->channels = Doctrine::getTable('Channel')->findInTvStaionId($channel_ids, $offset, $limit);
                    }
                }
                break;
            case 'cctv':
                $channel_ids = array('1');
                $this->count = Doctrine::getTable('Channel')->total_find_in_tv_staion_id($channel_ids);

                $this->page_total = ceil($this->count / $limit);

                if ($this->page > $this->page_total) {
                    $offset = ($this->page_total - 1) * $limit;
                }

                //                $this->tv_station_id = '1';
                $this->channels = Doctrine::getTable('Channel')->findInTvStaionId($channel_ids, $offset, $limit);
                break;
            case 'tv':
            case 'edu':
                $this->count = Doctrine::getTable('Channel')->total_find_list_by_type($this->type);

                $this->page_total = ceil($this->count / $limit);
                if ($this->page > $this->page_total) {
                    $offset = ($this->page_total - 1) * $limit;
                }

                $this->channels = Doctrine::getTable('Channel')->findListByType($this->type, $offset, $limit);
                break;
        }
    }*/

    public function executeShow(sfWebRequest $request) {
        $this->type = $request->getParameter('type');
        $this->channel_id = $request->getParameter('channel_id');
        $this->channels = Doctrine::getTable('Channel')->getReceiveChannels("深圳", "广东");
        /*switch ($this->type) {
            case 'local':
                $city = "深圳";//$this->getUser()->getAttribute('user_city');
                $this->channels = '';
                $provice = "广东";//$this->getUser()->getAttribute('province');
                $md5 = array(md5($provice), md5($city));
                if (!empty($city)) {
                    $channel_ids = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
                    if (count($channel_ids) == 0) {
                        $this->channels = array();
                    } else {
                        $this->channels = Doctrine::getTable('Channel')->findInTvStaionId($channel_ids);
                    }
                }
                break;
            case 'cctv':
                $channel_ids = array('1');
                $this->count = Doctrine::getTable('Channel')->total_find_in_tv_staion_id($channel_ids);
                $this->channels = Doctrine::getTable('Channel')->findInTvStaionId($channel_ids);
                break;
            case 'tv':
            case 'edu':
                $this->channels = Doctrine::getTable('Channel')->findListByType($this->type);
                break;
        }*/
    }

    /**
     * 显示本地频道
     *
     * @param sfRequest $request A request object
     * @author ward
     * @final 2010-08-31 15:27
     */
    public function executeShow_local_channel(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $this->tv = '';
        $provice = $this->getUser()->getAttribute('province');
        $md5 = array(md5($provice), md5($city));
        if (!empty($city)) {
            $channel_ids = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
            if (count($channel_ids) == 0) {
                $this->tv = '';
            } else {
                $this->tv = Doctrine::getTable('Channel')->findInTvStaionId($channel_ids);
            }
        }
    }

    /**
     * 显示卫视
     * @param sfWebRequest $request
     * @author ward
     * @final 2010-08-31 15:27
     */
    public function executeShow_tv(sfWebRequest $request) {
        $this->type = $request->getParameter('type', 'tv');
        $this->tv = Doctrine::getTable('Channel')->findListByType($this->type);
    }

}
