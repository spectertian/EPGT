<?php
/**
 * @author ward
 * @version 1.0
 * @since 2010-08-31 14:25
 */
class XmlRpc2Server extends IXR_Server 
{   
    /**
     * 构造函数
     * @author ward
     */
    public function __construct() 
    {
        $androidtv = new AndroidTvRpc2();
        $this->methods = array(
            'androidtv.sayHello' => array($androidtv, "sayHello"),
            'androidtv.getChannelList' => array($androidtv, "getChannelList"),
            'androidtv.getWeekByProvinceList' => array($androidtv, "getWeekByProvinceList"),
            'androidtv.getLiveList' => array($androidtv, "getLiveList"),
            'androidtv.getWikiAllInfo' => array($androidtv, "getWikiAllInfo"),
            'androidtv.search' => array($androidtv, "search"),
            'androidtv.programDetail' => array($androidtv, "programDetail"),
            'androidtv.recommendVideo' => array($androidtv, "recommendVideo"),
            'androidtv.getLiveTags' => array($androidtv, 'getLiveTags'),
            'androidtv.getNowPrograms' => array($androidtv, 'getNowPrograms'),
            'androidtv.getServerTime' => array($androidtv, 'getServerTime'),
            'androidtv.getAllChannel' => array($androidtv, 'getAllChannel'),
            'androidtv.getMetasByWikiId' => array($androidtv, 'getMetasByWikiId'),
            'androidtv.postUserLiving' => array($androidtv, 'postUserLiving'),
            'androidtv.getChannelInfo' => array($androidtv, 'getChannelInfo'),
            'androidtv.getWikiInfoByChannel' => array($androidtv, 'getWikiInfoByChannel'),
        );
        $this->failed   = false;
        $this->IXR_Server($this->methods);
    }
}
