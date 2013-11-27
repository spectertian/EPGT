<?php
/**
 * count actions.
 *
 * @package    epg2.0
 * @subpackage count
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class countActions extends sfActions
{
    private $patterns = array();   //敏感词数组
    /**
    * Executes index action
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('default', 'module');
    }
    /**
    * 上线电视剧情况统计
    * @author lifucang 2013-07-30
    */
    public function executeVideoCount(sfWebRequest $request)
    {
        $this->pageTitle='上线电视剧错误日志';
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');
        $wiki_repo = $mongo->getRepository('Wiki');
        
        $querya = array('has_video'=>array('$gt'=>0),'model'=>'teleplay');
        $wikis=$wiki_repo->find(array('query'=>$querya));
        
        $this->datas=array();
        foreach($wikis as $wiki){
            $marks=$wiki->getEpisodes();
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
                $this->datas[]=array(
                    'id'=>$wiki_id,
                    'marks'=>$marks,
                    'maxnum'=>$maxnum,
                    'videoHdYs'=>$videoHdYCount,
                    'videoHdNs'=>$videoHdNCount,
                    'title'=>$wiki->getTitle()
                );
            }
        }
    }
    /**
    * 上线电视剧播放统计
    * @author lifucang 2013-09-14
    */
    public function executeVideoPlayCount(sfWebRequest $request)
    {
        set_time_limit(0);
        if ($request->getMethod() == 'POST') { 
            $memcache = tvCache::getInstance();
            $key = "videoPlayCount";
            $datas=$memcache->get($key);
            if(!$datas){
                $mongo = $this->getMondongo();
                $video_repo = $mongo->getRepository('Video');
                $startTime = new MongoDate(strtotime("-3 day"));
                $query = array('updated_at' => array('$gte' => $startTime));
                
                $video_count = $video_repo->count($query);
                $datas=array();

                $videos = $video_repo->find(array("query"=>$query));
                foreach ($videos as $video) {
                    $id = (string)$video->getId();
                    $pageId=$video->getPageId();
                    $url=$this->getYangVideoUrl($pageId);
                    if(!$url){
                        $config=$video ->getConfig();
                        $datas[] = array(
                            'id' => $id,
                            'title' => $video ->getTitle(),
                            'asset_id' => $config['asset_id'],
                            'page_id' => $video ->getPageId()
                        );
                    }
                }
                //$memcache->set($key,$datas,6000);  //100分钟
            }   
            return $this->renderText(json_encode($datas));     
        }
    }
    /**
    * 上线电视剧播放统计
    * @author lifucang 2013-09-14
    */
    public function executeVideoPlayCountBak(sfWebRequest $request)
    {
        set_time_limit(0);
        $this->pageTitle='视频播放错误日志';
        
        $memcache = tvCache::getInstance();
        $key = "videoPlayCount";
        $this->datas=$memcache->get($key);
        if(!$this->datas){
            $mongo = $this->getMondongo();
            $video_repo = $mongo->getRepository('Video');
    
            $i = 0;
            $video_count = $video_repo->count(array());
            $id='519b02ab7b5fbd8f20000000';
            $errors=array();
            while ($i < $video_count) 
            {
                $videos = $video_repo->find(array("query"=>array("_id"=>array('$gt'=>new MongoId($id))), "limit" => 50,"sort"=>array('_id'=>1)));
                foreach ($videos as $video) {
                    $id = (string)$video->getId();
                    $pageId=$video->getPageId();
                    $url=$this->getYangVideoUrl($pageId);
                    if(!$url){
                        $errors[] = array(
                            'id' => $id,
                            'title' => $video ->getTitle(),
                            'page_id' => $video ->getPageId()
                        );
                    }
                }
                $i = $i + 50;
            }
            $this->datas=$errors;
            $memcache->set($key,$errors,600);  //10分钟
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
    * 影片点播量统计
    * @author lifucang 2013-07-30
    */
    public function executeVodCount(sfWebRequest $request)
    {
        $query_arr=array();
        $this->date1 = trim($request->getGetParameter('date1', date("Y-m",strtotime('-1 month'))));
        $this->date2 = trim($request->getGetParameter('date2', date("Y-m")));
        $query_arr=array('date'=>array('$gte'=>$this->date1,'$lte'=>$this->date2,));

        $this->pager = new sfMondongoPager('VodhitLog', 50);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('hits' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        //统计总次数
        $mongo = $this->getMondongo();
        $vodhitLog_repo = $mongo->getRepository('VodhitLog');
        $vodhitLogs = $vodhitLog_repo ->find(array('query'=>$query_arr));
        $hits=0;
        foreach($vodhitLogs as $vodhitLog){
            $hits =$hits+$vodhitLog->getHits();
        }
        $this->hitCount=$hits;
    }
    /**
    * 直播频道点击次数统计
    * @author lifucang 2013-08-01
    */
    public function executeLiveLog(sfWebRequest $request)
    {
        $query_arr=array();
        $this->date1 = trim($request->getGetParameter('date1', date("Y-m-d")));
        $this->date2 = trim($request->getGetParameter('date2', date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))));
        $startTime =new MongoDate(strtotime($this->date1));
        $endTime =new MongoDate(strtotime($this->date2));
        $query_arr=array('created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));

        $this->pager = new sfMondongoPager('LiveLog', 24);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('_id' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        //统计总次数
        $mongo = $this->getMondongo();
        $liveLog_repo = $mongo->getRepository('LiveLog');
        $liveLogs = $liveLog_repo ->find(array('query'=>$query_arr));
        $hits=0;
        foreach($liveLogs as $liveLog){
            $hits =$hits+$liveLog->getHits();
        }
        $this->hitCount=$hits;
    }
    /**
    * EPG发送频道统计
    * @author lifucang 2013-08-01
    */
    public function executeEpgLog(sfWebRequest $request)
    {
        $query_arr=array();
        $this->date = trim($request->getGetParameter('date', null));
        $this->channel = trim($request->getGetParameter('channel', null));
        if($this->date){
            $query_arr['date']=$this->date;
        }
        if($this->channel){
            $query_arr['channels']=$this->channel;
        }
        $this->pager = new sfMondongoPager('EpgLog', 3);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('_id' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    /**
    * 上下线影片数量统计
    * @author lifucang 2013-07-31
    */
    public function executeCdiCount(sfWebRequest $request)
    {
        $this->pageTitle='上下线影片数量统计';
        $this->date1 = trim($request->getGetParameter('date1', date("Y-m-d")));
        $this->date2 = trim($request->getGetParameter('date2', date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))));

        $mongo = $this->getMondongo();
        $cdi_repo = $mongo->getRepository('ContentCdi');
        $import_repo = $mongo->getRepository('ContentImport');
        $wiki_repo = $mongo->getRepository('Wiki');
        
        $startTime =new MongoDate(strtotime($this->date1));
        $endTime =new MongoDate(strtotime($this->date2));

        $arr=array();
        $querya = array('command'=>'ONLINE_TASK_DONE','created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));
        $arr['onlineNum']=$cdi_repo->count($querya);
        
        $querya = array('command'=>'CONTENT_OFFLINE','created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));
        $arr['offlineNum']=$cdi_repo->count($querya);

        $querya = array('state'=>1,'created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));
        $arr['onlineNum1']=$import_repo->count($querya);

        //总的
        $querya = array('has_video'=>array('$gt'=>0),'model'=>'teleplay');
        $arr['teleplayNum']=$wiki_repo->count($querya);
        
        $querya = array('has_video'=>array('$gt'=>0),'model'=>'film');
        $arr['filmNum']=$wiki_repo->count($querya);
        
        $querya = array('has_video'=>array('$gt'=>0),'model'=>'television');
        $arr['televisionNum']=$wiki_repo->count($querya);
        
        $this->nums=$arr;

    }
    /**
    * ADI文件入库统计
    * @author lifucang 2013-07-31
    */
    public function executeInjectCount(sfWebRequest $request)
    {
        $this->pageTitle='ADI文件入库统计';
        $this->date1 = trim($request->getGetParameter('date1', date("Y-m-d")));
        $this->date2 = trim($request->getGetParameter('date2', date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))));
        $startTime =new MongoDate(strtotime($this->date1));
        $endTime =new MongoDate(strtotime($this->date2));
        $querya=array('created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));
        
        $mongo = $this->getMondongo();
        $inject_repo = $mongo->getRepository('ContentInject');
        $import_repo = $mongo->getRepository('ContentImport');
        $arr['injectNum']=$inject_repo->count($querya);
        
        $query=array('content' => new MongoRegex("/Delete/"),'created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));
        $arr['injectDelNum']=$inject_repo->count($query);
        /*
        //未成功解析
        $query=array('state' => -4);
        $arr['injectNum4']=$inject_repo->count($query);
        //未知类型
        $query=array('state' => -3);
        $arr['injectNum3']=$inject_repo->count($query);
        //无video信息
        $query=array('state' => -2);
        $arr['injectNum2']=$inject_repo->count($query);
        //未设置Show_Type
        $query=array('state' => -1);
        $arr['injectNum1']=$inject_repo->count($query);
        */
        $arr['importNum']=$import_repo->count($querya);
        $arr['importWikiNum']=$import_repo->count(array('wiki_id'=>array('$exists'=>1),'created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime)));
        
        $this->nums=$arr;

    }
    /**
    * 节目统计
    * @author lifucang 2013-07-31
    */
    public function executeProgramCount(sfWebRequest $request)
    {
        $this->pageTitle='节目统计';
        $this->date=trim($request->getGetParameter('date', date('Y-m-d')));
        $this->action='count/programCount';
        
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository('Program');

        $query=array('date' =>$this->date);
        $this->programNum=$program_repo->count($query);
        
        $query=array('wiki_id' => array('$exists'=>true),'date' =>$this->date);
        $this->programWikiNum=$program_repo->count($query);
        
        $this->bili=intval($this->programWikiNum/$this->programNum*100);
        //分频道统计
        $memcache = tvCache::getInstance();
        $key = "programCount_$this->date";
        $this->channelPrograms=$memcache->get($key);
        if(!$this->channelPrograms){
            $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epg');
            $this->channelPrograms = array();       
            foreach($channels as $channel){
                $code = $channel->getChannelCode();
                $name = $channel->getName();
                if($code){
                    $num = $program_repo->countDayPrograms($code,$this->date);
                    $wikiNum = $program_repo->countDayPrograms($code,$this->date,true); //统计匹配上wiki的
                    $this->channelPrograms[]=array(
                        'name'=>$name,
                        'num'=>$num,
                        'wikiNum'=>$wikiNum,
                        'bili'=>intval($wikiNum/$num*100)
                    );
                }
            }
            $memcache->set($key,$this->channelPrograms,3600);  //1小时
        }
    }
    /**
    * 节目匹配日志
    * @author lifucang 2013-07-30
    */
    public function executeProgramLog(sfWebRequest $request)
    {
        $query_arr=array();
        $this->date1 = trim($request->getGetParameter('date1', null));
        $this->date2 = trim($request->getGetParameter('date2', null));
        
        if($this->date1&&$this->date2){
            $query_arr['date']=array('$gte'=>$this->date1,'$lte'=>$this->date2);
        }elseif($this->date1){
            $query_arr['date']=array('$gte'=>$this->date1);
        }elseif($this->date2){
            $query_arr['date']=array('$lte'=>$this->date2);
        }

        $this->pager = new sfMondongoPager('ProgramLog', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('_id' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        
        $mongo = $this->getMondongo();
        $programLog_repo = $mongo->getRepository('ProgramLog');
        $programLogs=$programLog_repo->find(array('query'=>$query_arr));
        $nums=0;
        $wikinums=0;
        foreach($programLogs as $programLog){
            $nums += $programLog->getNums();
            $wikinums += $programLog->getWikiNums();
        }
        $this->nums=$nums;
        $this->wikinums=$wikinums;
        $this->bili=intval($wikinums/$nums*100);     
    }
    /**
    * 图片统计
    * @author lifucang 2013-07-31
    */
    public function executeFileCount(sfWebRequest $request)
    {
        $this->pageTitle='图片统计';
        $this->date1 = trim($request->getGetParameter('date1', date("Y-m-d")));
        $this->date2 = trim($request->getGetParameter('date2', date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))));
        
        $arr['num'] = Doctrine::getTable('Attachments')->createQuery()
            ->Where('created_at >= ?', $this->date1)
            ->andWhere('created_at < ?', $this->date2)
            ->count();
        $this->nums=$arr;
    }
    /**
    * 维基统计
    * @author lifucang 2013-07-31
    */
    public function executeWikiCount(sfWebRequest $request)
    {
        $this->pageTitle='维基统计';
        $this->date1 = trim($request->getGetParameter('date1', date("Y-m-d")));
        $this->date2 = trim($request->getGetParameter('date2', date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))));
        $startTime =new MongoDate(strtotime($this->date1));
        $endTime =new MongoDate(strtotime($this->date2));
        $querya=array('created_at'=>array('$gte'=>$startTime,'$lt'=>$endTime));
        
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository('Wiki');

        $arr['wikiNum']=$wiki_repo->count($querya);

        //获取敏感词
        $this -> getSensitiveWords();
        $arr['wordNum'] = 0;
        $wikis = $wiki_repo->find(array("query"=>$querya));
        foreach ($wikis as $wiki) {
            $title=$wiki->getTitle();
            $content=$wiki->getContent();
            $arr['wordNum'] += $this -> wordsCount($title,$content);
        }
        $this->nums=$arr;
    }
    /**
    * 紧急下线日志
    * @author lifucang 2013-10-08
    */
    public function executeOfflineLog(sfWebRequest $request)
    {
        $query_arr=array();
        $this->title = trim($request->getGetParameter('title', null));
        if($this->title){
            $query_arr['title']= new MongoRegex("/.*".$this->title.".*/i");
        }
        $this->pager = new sfMondongoPager('OfflineLog', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('_id' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    //获取敏感词
    private function getSensitiveWords(){
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('words');
        $words_res = $repository->find();
        $arr=array();
        foreach($words_res as $rs){
            $arr[] = $rs->getWord();
        }
        $words=implode(',',$arr);
        $this->patterns=Common::getSensitiveWords($words);
    }
    //统计敏感词数量
    private function wordsCount($wiki_title,$wiki_content){
        $mongo = $this->getMondongo();
        $wordLog_res = $mongo->getRepository('WordsLog');
        $num = 0;
        $wikititle = preg_replace($this->patterns, "*", $wiki_title);
        $content = preg_replace($this->patterns, "*", $wiki_content);

        if($wikititle!=$wiki_title){
            $num = 1;
        }
        if($content!=$wiki_content){
            $num = 1;
        }
        return $num;
    }   
}
