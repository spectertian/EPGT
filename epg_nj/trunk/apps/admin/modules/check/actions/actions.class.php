<?php

/**
 * check actions.
 *
 * @package    epg2.0
 * @subpackage check
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class checkActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        //$this->forward('default', 'module');
    }
    /**
    * 各接口监测
    */
    public function executeInterface(sfWebRequest $request)
    {
        if ($request->getMethod() == 'POST') { 
            $str='';
            $str_log='';
            $cardId = '8250102372401749';
            $stbId = "99586611250057372";
            $tcllive = $this->getTclLivePrograms($cardId, 1, '');
            $centerlive = $this->getCenterLivePrograms($stbId, 1, '');
            $tongzhoulive = $this->getTongzhouLivePrograms($cardId, 1, '');
            $tclvod = $this->getTclVodPrograms($cardId, 1, '','','recommend.bygenre.v1');
            $centervod = $this->getCenterVodPrograms($stbId, 1, '','','RK');
            $tongzhouvod = $this->getTongzhouVodPrograms($cardId, 1, '');
            //节目收视率接口
            $channelHot = $this->getChannelHot();
            //数据仓库用户行为
            $userDate = $this->getUserDate();
            //CMS系统CDI接口监测
            $cmscdi = $this->getCmsCdi();
            //CMS系统Epg接收接口监测
            $cmsEpg = $this->getCmsEpg();
            //运营中心点播地址监测
            $vod = $this->getYangVideo();
            //pptv和1905点播地址监测
            $pptv = $this->getPPTVVideo();
            //$pptv = false;
            //页面服务器监测
            /*
            $portal=array();
            for($i=11;$i<22;$i++){
                $url = "172.31.201.$i";
                $portal[$i] = $this->portalTest($url);
            }
            */
            if($tcllive){
                $str.='<li class="check_ok"><span>TCL直播推荐接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="tclLiveError"><span>TCL直播推荐接口：</span>异常</li>';
                $str_log.='TCL直播推荐接口：异常　||　';
            }
            if($tclvod){
                $str.='<li class="check_ok"><span>TCL点播推荐接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="tclVodError"><span>TCL点播推荐接口：</span>异常</li>';
                $str_log.='TCL点播推荐接口：异常　||　';
            }
            if($centerlive){
                $str.='<li class="check_ok"><span>运营中心直播推荐接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="centerLiveError"><span>运营中心直播推荐接口：</span>异常</li>';
                $str_log.='运营中心直播推荐接口：异常　||　';
            }
            if($centervod){
                $str.='<li class="check_ok"><span>运营中心点播推荐接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="centerVodError"><span>运营中心点播推荐接口：</span>异常</li>';
                $str_log.='运营中心点播推荐接口：异常　||　';
            }
            if($tongzhoulive){
                $str.='<li class="check_ok"><span>技术部直播推荐接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="tongzhouLiveError"><span>技术部直播推荐接口：</span>异常</li>';
                $str_log.='技术部直播推荐接口：异常　||　';
            }
            if($tongzhouvod){
                $str.='<li class="check_ok"><span>技术部点播推荐接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="tongzhouVodError"><span>技术部点播推荐接口：</span>异常</li>';
                $str_log.='技术部点播推荐接口：异常　||　';
            } 
            if($channelHot){
                $str.='<li class="check_ok"><span>终端网管节目收视率：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="zhongduanError"><span>终端网管节目收视率：</span>异常</li>';
                $str_log.='终端网管节目收视率：异常　||　';
            } 
            if($userDate){
                $str.='<li class="check_ok"><span>用户行为数据：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="userError"><span>用户行为数据：</span>异常</li>';
                $str_log.='用户行为数据：异常　||　';
            } 
            if($cmscdi){
                $str.='<li class="check_ok"><span>CMS系统CDI接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="cmsCdiError"><span>CMS系统CDI接口：</span>异常</li>';
                $str_log.='CMS系统CDI接口：异常　||　';
            } 
            if($cmsEpg){
                $str.='<li class="check_ok"><span>CMS系统Epg接收接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="cmsEpgError"><span>CMS系统Epg接收接口：</span>异常</li>';
                $str_log.='CMS系统Epg接收接口：异常　||　';
            } 
            if($vod){
                $str.='<li class="check_ok"><span>运营中心视频接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="centerVideoError"><span>运营中心视频接口：</span>异常</li>';
                $str_log.='运营中心视频接口：异常　||　';
            } 
            if($pptv){
                $str.='<li class="check_ok"><span>PPTV视频接口：</span>正常</li>';
            }else{
                $str.='<li class="check_error" id="pptvError"><span>PPTV视频接口：</span>异常</li>';
                $str_log.='PPTV视频接口：异常　||　';
            } 
            /*
            for($i=11;$i<22;$i++){
                if($portal[$i]){
                    $str.='<li class="check_ok"><span>172.31.201.'.$i.'：</span>正常</li>';
                }else{
                    $str.='<li class="check_error" id="portal'.$i.'Error"><span>172.31.201.'.$i.'：</span>异常</li>';
                    $str_log.='172.31.201.'.$i.'：异常　||　';
                } 
            }
            */
            if($str_log!=''){
                $checkLog=new CheckLog();
                $checkLog -> setLog($str_log);
                $checkLog -> setTime(date('Y-m-d H:i:s'));
                $checkLog -> save();
            }
            return $this->renderText($str);   
        }
    }
    
	/**
	 * 监测欢网节目单
     * @author lifucang
     * @date 2013-04-18
	 */
	public function executeEpg(sfWebRequest $request) {
	    if ($request->getMethod() == 'POST') { 
            $mongo = $this->getMondongo();
            $programRes = $mongo->getRepository('program');
            $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epg');
            
            $data=array();
            for($days = 0; $days < 3 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));   
                $str = $date.'<br/>'; 
                foreach($channels as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $programRes->countDayPrograms($code,$date);
                    if($programNum<4&&!strpos($name,'节目')&&!strpos($name,'通道')){
                        $str.="<a href='/program?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }
                        
                }
                $data[]=array(
                    //'date' =>$date,
                    'content' => $str
                );
            }
            return $this->renderText(json_encode($data));   
        }
	}    
	/**
	 * 监测大网节目单
     * @author lifucang
     * @date 2013-09-09
	 */
	public function executeEpgWeek(sfWebRequest $request) {
	    if ($request->getMethod() == 'POST') { 
            $mongo = $this->getMondongo();
            $programRes = $mongo->getRepository('programWeek');
            $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epg');
            
            $data=array();
            for($days = 0; $days < 3 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));   
                $str = $date.'<br/>'; 
                foreach($channels as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $programRes->countDayPrograms($code,$date);
                    if($programNum==0&&!strpos($name,'节目')&&!strpos($name,'通道')){
                        $str.="<a href='/programWeek/index?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }
                        
                }
                $data[]=array(
                    //'date' =>$date,
                    'content' => $str
                );
            }
            return $this->renderText(json_encode($data));   
        }
	}   
	/**
	 * 监测回看节目单
     * @author lifucang
     * @date 2013-04-18
	 */
	public function executeEpgbak(sfWebRequest $request) {
	    if ($request->getMethod() == 'POST') { 
            $mongo = $this->getMondongo();
            $programRes = $mongo->getRepository('cpg');
            $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epgbak');
            $data=array();
            for($days = 1; $days < 3 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$days,date("Y")));   
                $str = $date.'<br/>';   
                foreach($channels as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $programRes->countDayPrograms($code,$date);
                    if($programNum==0&&!strpos($name,'节目')&&!strpos($name,'通道')){
                        $str.="<a href='/cpg?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }                
                }
                $data[]=array(
                    'content' => $str
                );
            }
            return $this->renderText(json_encode($data));   
        }
	}       
	/**
	 * 监测日志
     * @author lifucang
     * @date 2013-04-24
	 */
	public function executeLog(sfWebRequest $request) {
        $query_arr=array();
        $this->pager = new sfMondongoPager('CheckLog', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('time' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
	}   
    public function executeLogDel(sfWebRequest $request)
    {
       if($request->isMethod("POST")){
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0){
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的日志！');
           }else{
               $mongo = $this->getMondongo();
               $words_mongo = $mongo->getRepository("CheckLog");
               foreach($ids as $id){
                   $words = $words_mongo->findOneById(new MongoId($id));
                   $words -> delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'check','action'=>'log')));
    }  
	/**
	 * 监测欢网节目单是否有“以播出为准”的
     * @author lifucang
     * @date 2013-07-01
	 */
	public function executeEpgBochu(sfWebRequest $request) {
	    if ($request->getMethod() == 'POST') { 
            $mongo = $this->getMondongo();
            $programRes = $mongo->getRepository('program');
            $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epg');
            
            $data=array();
            for($days = 0; $days < 3 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));   
                $str = $date.'<br/>'; 
                foreach($channels as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $programRes->countDayProgramsBochu($code,$date);
                    if($programNum>0&&!strpos($name,'节目')&&!strpos($name,'通道')){
                        $str.="<a href='/program?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }
                        
                }
                $data[]=array(
                    //'date' =>$date,
                    'content' => $str
                );
            }
            return $this->renderText(json_encode($data));   
        }
	}    
	/**
	 * 一键监测
     * @author lifucang
     * @date 2013-09-10
	 */
	public function executeOneKey(sfWebRequest $request) {
	    if ($request->getMethod() == 'POST') { 
	        $memcache = tvCache::getInstance();
            $key = "OneKey";
            $data=$memcache->get($key);
            if($data){
                return $this->renderText(json_encode($data));   
            }
            $mongo = $this->getMondongo();
            $programRes = $mongo->getRepository('program');
            $programWeekRes = $mongo->getRepository('programWeek');
            $cpgRes = $mongo->getRepository('cpg');
            $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epg');
            $channelsBak=$mongo->getRepository('SpService')->getServicesByEpg('check_epgbak');
            $wiki_reps = $mongo->getRepository('Wiki');
            $video_repo = $mongo->getRepository('Video');
            $crontab_reps = $mongo->getRepository("CrontabLog");
            $data=array();
            $startTime =new MongoDate(strtotime("-1 days"));
            $endTime =new MongoDate(strtotime("+1 days"));
            //维基监测,统计最近两天维基更新的数量
            $querya=array('created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));
            $wikiNum=$wiki_reps->count($querya);
            if($wikiNum>0){
                $wikistatu='正常';
            }else{
                $wikistatu='异常';
            }
            $data['wikiNum']=array(
                'num' => $wikiNum,
                'statu' => $wikistatu
            );
            //计划任务监测,统计错误的数量
            $querya=array('start_time'=>array('$gte'=>$startTime,'$lt'=>$endTime),'state'=>0);
            $crontabNum=$crontab_reps->count($querya);
            if($crontabNum==0){
                $wikistatu='正常';
            }else{
                $wikistatu='异常';
            }
            $data['crontabNum']=array(
                'num' => $crontabNum,
                'statu' => $wikistatu
            );
            //欢网节目单监测,统计没有节目单的频道
            for($days = 0; $days < 2 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));   
                $str = '';
                foreach($channels as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $programRes->countDayPrograms($code,$date);
                    if($programNum<4){
                        $str.="<a href='/program?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }
                }
                $data['epg'][]=array(
                    'date' => $date,
                    'content' => $str
                );
            }
            //大网节目单监测,统计没有节目单的频道
            for($days = 0; $days < 2 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));   
                $str = '';
                foreach($channels as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $programWeekRes->countDayPrograms($code,$date);
                    if($programNum<4){
                        $str.="<a href='/programWeek?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }
                }
                $data['programWeek'][]=array(
                    'date' => $date,
                    'content' => $str
                );
            }
            //以播出为准节目单监测
            for($days = 0; $days < 2 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));   
                $str = '';
                foreach($channels as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $programRes->countDayProgramsBochu($code,$date);
                    if($programNum>0){
                        $str.="<a href='/program?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }
                        
                }
                $data['epgbochu'][]=array(
                    'date' => $date,
                    'content' => $str
                );
            }
            //回看节目单监测
            for($days = 1; $days < 2 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$days,date("Y")));   
                $str = '';
                foreach($channelsBak as $channel){
                    $code = $channel->getChannelCode();
                    $name = $channel->getName();
                    $tags = $channel->getTags();
                    $type = $tags[0];
                    $programNum = $cpgRes->countDayPrograms($code,$date);
                    if($programNum==0){
                        $str.="<a href='/cpg?type=$type&channel_code=$code&date=$date' target='_blank'>$name</a><br/>";
                    }                
                }
                $data['cpg'][]=array(
                    'date' => $date,
                    'content' => $str
                );
            }
            //上线电视剧错误监测
            $videoErrorNum=0;
            $querya = array('has_video'=>array('$gt'=>0),'model'=>'teleplay');
            $wikis=$wiki_reps->find(array('query'=>$querya));
            
            $this->datas=array();
            foreach($wikis as $wiki){
                $wiki_id=(string)$wiki->getId();
                //查找video表中最大的集数
                $video=$video_repo->findOne(array('query'=>array('wiki_id'=>$wiki_id),'sort'=>array('mark'=>-1)));
                if($video){
                    $maxnum=$video->getMark();
                }else{
                    $maxnum=0;
                }
                //$videoCount=$video_repo->count(array('wiki_id'=>$wiki_id));
                $videoHdYCount=$video_repo->count(array('wiki_id'=>$wiki_id,'config.hd_content'=>'Y'));
                $videoHdNCount=$video_repo->count(array('wiki_id'=>$wiki_id,'config.hd_content'=>'N'));
                if(($videoHdNCount!=$maxnum&&$videoHdNCount!=0)||($videoHdYCount!=$maxnum&&$videoHdYCount!=0)){
                    $videoErrorNum++;
                }
            }
            if($videoErrorNum==0){
                $videostatu='正常';
            }else{
                $videostatu='异常';
            }
            $data['videoError']=array(
                'num' => $videoErrorNum,
                'statu' => $videostatu
            );
            //视频播放错误日志
            $videoPlayErrorNum = 0;
            if(!$data['videoPlayError']){
                $startTime = new MongoDate(strtotime("-3 day"));
                $query = array('updated_at' => array('$gte' => $startTime));
                
                $video_count = $video_repo->count($query);

                $videos = $video_repo->find(array("query"=>$query));
                foreach ($videos as $video) {
                    $id = (string)$video->getId();
                    $pageId=$video->getPageId();
                    $url=$this->getYangVideoUrl($pageId);
                    if(!$url){
                        $videoPlayErrorNum++;
                    }
                }
            }  
            if($videoPlayErrorNum==0){
                $videoPlayStatu='正常';
            }else{
                $videoPlayStatu='异常';
            }
            $data['videoPlayError']=array(
                'num' => $videoPlayErrorNum,
                'statu' => $videoPlayStatu
            );
            //深度节目单更新情况
            $channelCodes=array('cctv1'=>array('name'=>'CCTV-1','type'=>'cctv'),'cctv3'=>array('name'=>'CCTV-3','type'=>'cctv'),'cctv5'=>array('name'=>'CCTV-5','type'=>'cctv'),'cctv6'=>array('name'=>'CCTV-6','type'=>'cctv'),'cctv_news'=>array('name'=>'CCTV-新闻','type'=>'cctv'),'c39a7a374d888bce3912df71bcb0d580'=>array('name'=>'湖南卫视','type'=>'tv'),'590e187a8799b1890175d25ec85ea352'=>array('name'=>'浙江卫视','type'=>'tv'),'5dfcaefe6e7203df9fbe61ffd64ed1c4'=>array('name'=>'北京卫视','type'=>'tv'),'antv'=>array('name'=>'安徽卫视','type'=>'tv'),'dragontv'=>array('name'=>'东方卫视','type'=>'tv'),'cctv22'=>array('name'=>'央视高清','type'=>'hd'),'hljweishigaoqing'=>array('name'=>'黑龙江卫视高清','type'=>'hd'),'cctv8gaoqing'=>array('name'=>'CCTV-8高清','type'=>'hd'),'gdweishigaoqing'=>array('name'=>'广东卫视高清','type'=>'hd'),'sitvdfcj'=>array('name'=>'东方财经','type'=>'pay'),'youyoubaobei'=>array('name'=>'优优宝贝','type'=>'pay'),'6612405d22d72e43ac5dc9d1762c5109'=>array('name'=>'极速汽车','type'=>'pay'),'shuhua'=>array('name'=>'书画','type'=>'pay'),'zhongguoqixiang'=>array('name'=>'中国气象','type'=>'pay'),'8486f91868f3e6d4f4d6517ca2c2c017'=>array('name'=>'南京新闻','type'=>'local'),'7f0bc7666fadfdbab99f00e79e9d6eed'=>array('name'=>'南京教科','type'=>'local'),'322fa7b66243b8d0edef9d761a42f263'=>array('name'=>'江苏卫视','type'=>'local'),'jiangsuzongyi'=>array('name'=>'江苏综艺','type'=>'local'),'dazhongyingyuan'=>array('name'=>'大众影院','type'=>'local'));
            foreach($channelCodes as  $channelCode=>$channelInfo) {
                $date = date("Y-m-d");   
                $query = array('channel_code'=>$channelCode,'date'=>$date);
                $program = $programRes->findOne(array('query'=>$query,'sort'=>array('time'=>1)));
                if($program){
                    $updateAt=$program->getUpdatedAt()? $program->getUpdatedAt()->format("Y-m-d"):'2000-1-1';
                }
                if(strtotime($updateAt)<strtotime($date)){
                    $data['epgNoUpdate'][]=array(
                        'code' => $channelCode,
                        'name' => $channelInfo['name'],
                        'type' => $channelInfo['type'],
                        'date' => $date
                    );
                }
            }
            $memcache->set($key,$data,600);  //10分钟 
            return $this->renderText(json_encode($data));   
        }
	}    
    private function getYangVideoUrl($contented)
	{
        $clientid = '01006608470056014';
        $backurl = '';
        $playtype = 0;
        if(!$contented) {
            return null;    
        }        
        $submit_url = sfConfig::get("app_cpgPortal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=y&contented=".$contented."&backurl=".urlencode($backurl); 
        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_USERPWD, sfConfig::get("app_cpgPortal_username").":".sfConfig::get("app_cpgPortal_password")); 
        curl_setopt($curl, CURLOPT_URL, $submit_url); 
        $data = curl_exec($curl);
        curl_close($curl); 
        if(!$data) {
            return '';
        }
        $xmls = @simplexml_load_string($data);        
        if(isset($xmls->url)) {
            $url=strval($xmls->url);
            if($url=='null'){
                return null;
            }else{
                return $url;
            }
        }else{
            return null;
        }
	}    
    /**
     * 获取tcl的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTclLivePrograms($user_id,$count=20,$type='')
    {
        $user_id = substr($user_id,0,strlen($user_id)-1);
        //按标签推荐
        $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$count."&genre=".$type."&uid=".$user_id;
        $contents = Common::get_url_content($url);
        if(!$contents){
            return false;
        }
        return true;
    }
    
    /**
     * 获取运营中心的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getCenterLivePrograms($user_id,$count=20,$type='',$cid='10557718')
    {
        $user_id = $user_id ? $user_id."_0" : "02316611160002151_0";
        $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&rtype=recommend.livetv.v1&operation=GetRecommendList&ctype=vod&count='.$count.'&uid='.$user_id.'&lang=zh&cid='.$cid;
        $contents = Common::get_url_content($recomUrl);
        if(!$contents){
            return false;
        }
        return true;
    }
    
    /**
     * 获取技术部同洲厂家的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTongzhouLivePrograms($user_id,$count=20,$type='')
    {   
        $user_id = substr($user_id,0,strlen($user_id)-1);
        $url = sfConfig::get('app_recommend_tongzhouUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$count."&genre=".$type."&uid=".$user_id;
        $contents = Common::get_url_content($url);
        if(!$contents){
            return false;
        }        
        return true;
    }
    
    /**
     * 获取Tcl的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTclVodPrograms($user_id,$count=10,$type='',$backurl='',$rtype='recommend.rs.v1')
    {
        $user_id = substr($user_id,0,strlen($user_id)-1);
        $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=".$rtype."&ctype=vod&count=".$count."&uid=".$user_id."&genre=".$type."&backurl=".$backurl;
        $contents = Common::get_url_content($url);
        if(!$contents){
            return false;
        }
        return true;
    }
                
    /**
     * 获取运营中心的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getCenterVodPrograms($user_id,$count=10,$type='',$backurl='',$alg='CF')
    {
        $user_id = $user_id ? $user_id."_0" : "99766609340071223_0";
        $filter="Category6%3D%27%E7%94%B5%E8%A7%86%E5%89%A7%27";
        if($type=='News'){
            $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.toprating.v1&ctype=vod&postertype=1&count='.$count.'&lang=zh&urltype=1&alg='.$alg.'&uid='.$user_id.'&user_weight=0.4&optr_weight=0.6&filter='.$filter.'&backurl='.$backurl;
        }else{
            $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count='.$count.'&lang=zh&urltype=1&alg='.$alg.'&uid='.$user_id.'&filter='.$filter.'&backurl='.$backurl;
        }
        $contents = Common::get_url_content($recomUrl);
        if(!$contents){
            return false;
        }
        return true;
    }
    
    /**
     * 获取技术部同洲厂家的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTongzhouVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $user_id = substr($user_id,0,strlen($user_id)-1);
        if($type!=''){
            //按标签推荐
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&genre='.$type.'&backurl='.$backurl;
        }else{
            //个性化推荐
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&backurl='.$backurl;
        }
        $contents = Common::get_url_content($recomUrl);
        if(!$contents){
            return false;
        }
        return true;
    }  
    /**
     * 终端网节目收视率接口监测。
     * @author lifucang
     * @date   2013-04-18
     */
    protected function getChannelHot()
    {
        $url = sfConfig::get("app_statsQuery_biz")."?CMD=Channel";
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cu, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($cu, CURLOPT_USERPWD, 'north_user:north_user');
        $ret = curl_exec($cu);
        curl_close($cu);
        $ca = @json_decode($ret);
        if($ca and isset($ca->BizStatsInfo)) {
            return true;
        }else{
            return false;
        }
    }  
    /**
     * 数据仓库用户行为数据监测。
     * @author lifucang
     * @date   2013-04-18
     */
    protected function getUserDate()
    {
        $ip = sfConfig::get("app_DataWarehouse_ip");
        $user = sfConfig::get("app_DataWarehouse_username");
        $pass = sfConfig::get("app_DataWarehouse_password");
        $ftp_conn = ftp_connect($ip,21); 
        $isLogin = ftp_login($ftp_conn ,$user,$pass);
        if($isLogin){
            return true;   
        }else{
            return false;
        }
        /*
        ftp_pasv($ftp_conn,TRUE);  //被动模式，否则会很慢
        $ftp_files = ftp_nlist($ftp_conn,'/');
        
        $date=date('Ymd');
        $fileName='STRD_TV_'.$date.'.csv';
        $fileName1='STRD_VOD_'.$date.'.csv';
        if(in_array($fileName,$ftp_files)&&in_array($fileName1,$ftp_files)){
            return true;   
        }else{
            return false;
        }
        */
    } 
    /**
     * CMS系统CDI接口监测
     * @author lifucang
     * @date   2013-04-18
     */
    protected function getCmsCdi()
    {
        $bkUrl = sfConfig::get('app_cmsCenter_bkjsonVod')."?action=adi1synccallback";
        $contents = Common::get_url_content($bkUrl);
        if(!$contents){
            return false;
        }
        return true;
    } 
    /**
     * CMS系统Epg接收接口监测
     * @author lifucang
     * @date   2013-08-26
     */
    protected function getCmsEpg()
    {
        $bkUrl = sfConfig::get('app_cmsCenter_bkjson')."?action=adi1synccallback";
        $contents = Common::get_url_content($bkUrl);
        if(!$contents){
            return false;
        }
        return true;
    } 
    /**
     * 运营中心点播地址监测
     * @author lifucang
     * @date   2013-05-13
     */
    private function getYangVideo()
	{
        $clientid = '01006608470056014';
        $contented='yang.com20130328036900';
        $playtype = 0;
        $backurl = sfConfig::get("app_base_url"); 
        $submit_url = sfConfig::get("app_cpgPortal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=y&contented=".$contented."&backurl=".urlencode($backurl); 
        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_USERPWD, sfConfig::get("app_cpgPortal_username").":".sfConfig::get("app_cpgPortal_password")); 
        curl_setopt($curl, CURLOPT_URL, $submit_url); 
        $data = curl_exec($curl);
        curl_close($curl); 
        if(!$data) {
            return false;
        }else{
            return true;
        }
	}
    /**
     * PPTV和1905点播地址监测
     * @author lifucang
     * @date   2013-05-13
     */
    protected function getPPTVVideo()
    {
        $bkUrl = sfConfig::get("app_linkQuery_center");
        $contents = Common::get_url_content($bkUrl);
        if(!$contents){
            return false;
        }
        return true;
    } 
    /**
     * 页面服务器监测
     * @author lifucang
     * @date   2013-09-06
     */
    protected function portalTest($url)
    {
        $fp = fsockopen($url, 80);
        if(!$fp){
            return false;
        }else{
            return true;
        }
    } 
}
