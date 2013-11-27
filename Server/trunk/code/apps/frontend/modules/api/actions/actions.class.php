<?php
/**
 * api actions.
 *
 * @package    epg
 * @subpackage api
 * @author     wangnan
 */
class apiActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    * @author ward
    * @final 2010-08-31 11:04
    */
    public function executeIndex(sfWebRequest $request) {
        $HTTP_RAW_POST_DATA = file_get_contents('php://input');
        $this->getResponse()->setContentType('application/xml');
        if ($request->getMethod() == 'POST') {
            $body = $request->getPostParameters();
            $server = new XmlRpcServer();
        } else {
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText('XML-RPC server accepts POST requests only.');
        }
        return sfView::NONE;
    }

	/**
    * Executes rpc2 action
    *
    * @param sfRequest $request A request object
    * @author ward
    * @final 2010-08-31 11:04
    */
    public function executeRpc2(sfWebRequest $request) { 
        $HTTP_RAW_POST_DATA = file_get_contents('php://input');
        $this->getResponse()->setContentType('application/xml');
        $ip = $request->getRemoteAddress();

        if(!$this->tclVisitControl($ip)){
            return $this->renderText(
                '<?xml version="1.0" encoding="utf-8"?>
                <response website="http://www.epg.huan.tv/RPC/interface2">
                    <error type="true" note="访问次数过多" servertime="'.date('Y-m-d H:i:s',time()).'"/>
                    <data language="zh-CN"/>
                </response>');
        }

        if ($request->getMethod() == 'POST') {
            $body = $request->getPostParameters();
            $server = new XmlRpc2Server();
        } else {
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText('XML-RPC server accepts POST requests only.');
        }
        return sfView::NONE;
    }

    /**
     * 获取直播频道及节目表，JSON接口，供XBMC使用
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeLiveTV(sfWebRequest $request) {
        $this->getResponse()->setContentType('text/plain');
        $live_channels = Doctrine::getTable("Channel")->createQuery()
                ->where("live = 1")
                ->execute();

        $channels = array();
        foreach ($live_channels as $live_channel) {
            $live_tv = array();
            $live_tv['code'] = $live_channel->getCode();                                                                                  
            $live_tv['name'] = $live_channel->getName();
            $live_tv['logo'] = $live_channel->getLogoUrl();

            $config = $live_channel->getLiveConfig();
            $live_tv['config'] = $config;
            
            $weekday = (date('w') == 0) ? 7 : date('w');
            $today = time();

            $programs = array();
            for ($i = 1; $i <= 7; $i++) {
                $n = $i - $weekday;
                $date = date('Y-m-d', $today + $n * 86400);
                $day_programs = $live_channel->getDayPrograms($date);

                $day_program_items = array();
                foreach ($day_programs as $key => $program) {
                    $program_item = array(
                        'name' => $program->getName(),
                        'time' => $program->getTime(),
                        'date' => $program->getDate(),
                        'tags' => $program->getTags(),
                    );
                    if ($program->getStartTime() instanceof DateTime) {
                        $program_item['start_time'] = $program->getStartTime()->format("Y-m-d H:i:s");
                    } else {
                        $program_item['start_time'] = $program->getDate()." ".$program->getTime();
                    }
                    if ($program->getEndTime() instanceof DateTime) {
                        $program_item['end_time'] = $program->getEndTime()->format("Y-m-d H:i:s");
                    }
                    if ($program->getWikiId()) {
                        $program_item['wiki_id'] = $program->getWikiId();
                    }
                    $day_program_items[] = $program_item;
                }
                $programs[] = $day_program_items;
            }
            $live_tv["programs"] = $programs;
            $channels[] = $live_tv;
        }
        return $this->renderText(json_encode($channels));
    }

    /**
     * 根据ID返回Wiki 信息，返回JSON格式，目前供客户端使用
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeWiki(sfWebRequest $request) 
    {
        $this->getResponse()->setContentType('text/plain');
        
        $id = $request->getParameter("id");
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");

        $wiki = $wiki_repo->findOneByid(new MongoId($id));
        if ($wiki) {
            $wiki_array = array();
            $wiki_array["title"] = $wiki->getTitle();
            $wiki_array["cover"] = $wiki->getCoverUrl();

            return $this->renderText(json_encode($wiki_array));
        } else {
            return $this->renderText(json_encode(array("status" => false)));
        }
    }

    /*
     * 欢网接口入口
     * @param sfWebReqeust $request
     * @author guoqiang.zhang
     */
    public function executeInterface(sfWebRequest $request)
    {
        //$HTTP_RAW_POST_DATA = file_get_contents('php://input');
		//$visit = new VisitControl();
        //if ($request->getMethod() == 'POST' && $visit::visit()) {
     	if ($request->getMethod() == 'POST') {
            //$data = simplexml_load_string($post);                  
            $post = $request->getPostParameter("xmlString");
            $this->getResponse()->setContentType('text/xml');
	        $xml = new Simple($post);
            return $this->renderText($xml->response);
        }
    }
    
    /*
     * 欢网接口入口2
     * @param sfWebReqeust $request
     * @author wangnan
     */
    public function executeInterface2(sfWebRequest $request)
    {
        //$HTTP_RAW_POST_DATA = file_get_contents('php://input');
     	$ip = $request->getRemoteAddress();

        if(!$this->visitControl($ip)){
            return $this->renderText('
                <?xml version="1.0" encoding="utf-8"?>
                <response website="http://www.epg.huan.tv/RPC/interface2">
                    <error type="true" note="访问次数过多" servertime="'.date('Y-m-d H:i:s',time()).'"/>
                    <data language="zh-CN"/>
                </response>
            ');
        }
     	
        if ($request->getMethod() == 'POST') {                
            $post = $request->getPostParameter("xmlString");
            $this->getResponse()->setContentType('text/xml');
	        $xml = new Simple2($post);
            return $this->renderText($xml->response);
        } else {
           
        }
     }
     
    /**
     * 接口访问控制
     * @param unknown $ip
     * @return boolean
     */
    private function visitControl($ip)
    {        
     	if(!$ip){
            return true;
        }
        if(!(bool)sfConfig::get("app_api_need_visit_ontrol")) {
            return true;
        }
     	if (in_array($ip, sfConfig::get("app_api_white_iplist"))) {
            return true;
        }
        $key = 'visit-'.date('Ymd').'-'.$ip;
        $visitNum = tvCache::getInstance()->get($key);
        if ($visitNum){
            tvCache::getInstance()->set($key,$visitNum+1,60*60*24);
            if ($visitNum < 1000) return true; 
            else return false;
        }else {
            tvCache::getInstance()->set($key,1,60*60*24);
            return true;
        }     	
    }

    /**
     * tcl rpc2接口专用 ip限制
     * @author majun
     * @param  ip
     * @return boolean
     */
    private function tclVisitControl($ip){
        if (in_array($ip, sfConfig::get("app_api_white_iplist"))) {
            return true;
        }
    	// 加入黑名单 Modify by tianzhongsheng-ex@huan.tv 2013-11-15 12:42:00
    	if (in_array($ip, sfConfig::get("app_api_black_iplist")))
    	{
            return false;
        }
        $key = "tclVisitControl-".date("Ymd",time())."-".$ip;
        $visitNum = tvCache::getInstance()->get($key);
        if ($visitNum){
            if ($visitNum < 1000) {
                tvCache::getInstance()->set($key,$visitNum+1,60*60*24);
                return true;
            }else return false;
        }else {
            tvCache::getInstance()->set($key,1,60*60*24);
            return true;
        }
    }

}    
?>
