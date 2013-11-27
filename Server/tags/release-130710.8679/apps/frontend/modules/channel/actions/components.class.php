<?php

class channelComponents extends sfComponents {

    public function executePlaying_program( sfWebRequest $request ) {
        $this->cctv_channels = Doctrine::getTable('Channel')->findOneById('1');
        $this->tv_HuNan = Doctrine::getTable('Channel')->findOneById('332');
        $this->tv_DongFang = Doctrine::getTable('Channel')->findOneById('871');
        $this->tv_BeiJing = Doctrine::getTable('Channel')->findOneById('38');
        $this->tv_ZheJiang = Doctrine::getTable('Channel')->findOneById('833');

        $mondongo = $this->getMondongo();
        $program_repo = $mondongo->getRepository("Program");

        $this->cctv_programs = $program_repo->find(array("query" =>
                    array("channel_code" => $this->cctv_channels->getCode(),
                          "end_time" => array('$gte' => new MongoDate()),
                          ),
                    'sort' => array('start_time' => 1),
                    'limit' => "5")
                );

        $this->tv_HuNanPrograms = $program_repo->find(array("query" =>
                    array("channel_code" => $this->tv_HuNan->getCode(),
                          "end_time" => array('$gte' => new MongoDate()),
                          ),
                    'sort' => array('start_time' => 1),
                    'limit' => "5")
                );

        $this->tv_DongFangPrograms = $program_repo->find(array("query" =>
                    array("channel_code" => $this->tv_DongFang->getCode(),
                          "end_time" => array('$gte' => new MongoDate()),
                          ),
                    'sort' => array('start_time' => 1),
                    'limit' => "5")
                );

        $this->tv_BeiJingPrograms = $program_repo->find(array("query" =>
                    array("channel_code" => $this->tv_BeiJing->getCode(),
                          "end_time" => array('$gte' => new MongoDate()),
                          ),
                    'sort' => array('start_time' => 1),
                    'limit' => "5")
                );

        $this->tv_ZheJiangPrograms = $program_repo->find(array("query" =>
                    array("channel_code" => $this->tv_ZheJiang->getCode(),
                          "end_time" => array('$gte' => new MongoDate()),
                          ),
                    'sort' => array('start_time' => 1),
                    'limit' => "5")
                );
//        $this->channel_id = $request->getParameter('id');
//        $this->date = $request->getParameter('date') ? $request->getParameter('date') : date('Y-m-d');
//        $this->channel = Doctrine::getTable('Channel')->findOneById($this->channel_id);
//        if (!$this->channel)
//            $this->redirect404();
//        $this->programs = $this->channel->getDayPrograms($this->date);
//        $title = $this->channel->getName() . $this->date . "节目表";
//        $this->getResponse()->setTitle($title);
    }

    /**
     *
     * @param sfWebRequest $request
     * @date 2010-11-22
     * @author author fangdun.cai
     * @modfiled by zhigang  2011-1-19
     */
    public function executeShow(sfWebRequest $request) {
        $this->province = $request->getCookie("province", $this->getUser()->getUserProvince());
//        $city           = $request->getParameter('city', $this->getUser()->getAttribute('user_city'));
//        $province       = $request->getParameter('province', $this->getUser()->getAttribute('province'));
//        $channel_id     = $request->getParameter('id',0);
        if (!$this->province) {
            $this->province = "上海";
        }

//        $this->city     = $city;
//        $this->province = $province;
//
//        $this->isLocal  = $province == '上海';

//        if ( $channel_id > 0 ) {
//            $channel = Doctrine::getTable('Channel')->findOneById($channel_id);
//            $parent_tv_station_id = Doctrine::getTable('TvStation')->getParentTvStationId($channel->getTvStationId());
//            $parent_tv_station = Doctrine::getTable('TvStation')->findOneById($parent_tv_station_id);
//            $province = $parent_tv_station->getCode();
//        }else{
//            $province = md5($province);
//        }
//
//        if (!$this->local_channels) {
////            $md5                  = array(md5($province), md5($city));
////            $local_channel_ids    = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
//            $tv_station = Doctrine::getTable('TvStation')->findOneByCode($province);
//            $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
//            $this->local_channels = Doctrine::getTable('Channel')->findInTvStaionId($local_channel_ids);
//        }
        $this->local_station = Doctrine::getTable("TvStation")->getByProvince($this->province);
        $this->cctv_channels = Doctrine::getTable("Channel")->findListByType("cctv");
        $this->tv_channels = Doctrine::getTable('Channel')->findListByType('tv');

//        if (!$this->cctv_channels) {
//            $this->cctv_channels = Doctrine::getTable('Channel')->findInTvStaionId(array(1));
//        }
//
//        if (!$this->tv_channels) {
//            $this->tv_channels = Doctrine::getTable('Channel')->findListByType('tv');
//        }
        
    }

    /**
     * 随机推荐维基
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeHotplay(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
        $this->Hotplay = $wikiRecommendRepo->getRandWiki(10);
    }

    /**
     * 获取省份
     * @param sfWebRequest $request
     * @author lyong
     */
    public function executeProvince(sfWebRequest $request){
        $this->allProvince = Province::getProvince();
        $this->province = $request->getParameter('location', null);
        if(!is_null($this->province)){
            $this->province = array_search($this->province, $this->allProvince);
        } else{
            $this->province = $this->getUser()->getUserProvince();
        }
    }
}
