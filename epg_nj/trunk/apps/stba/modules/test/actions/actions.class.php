<?php

/**
 * test actions.
 *
 * @package    epg2.0
 * @subpackage test
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testActions extends sfActions
{
    /**
    * Executes index action
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
    }
    /*
    ********************************************************************
    此处以下的经常调用，请勿删除
    ********************************************************************
    */   
    /**
     * 测试 mongo
     * @param  sfWebRequest $request
     * @author chenshengwen
     */
    public function executeMongo(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $sp_repos = $mongo->getRepository('wiki');
        $sps = $sp_repos->find(array("sort" => array("update_at" => 1), "limit" => 20,'timeout'=>1000));
        foreach ($sps as $sp) {
            echo $sp->getId() . "\t" . $sp->getModel() . "\t" . $sp->getTitle() . "<br>\r\n";
        }
        echo "Slave is " . $mongo->getConnection("mondongo")->getMongo()->getSlave() . "<br>";
        echo "timeout is " . $mongo->getConnection("mondongo")->getMongo()->timeout . "<br>";
        echo "connectTimeoutMS is " . $mongo->getConnection("mondongo")->getMongo()->getConnectTimeout() . "<br>";
        exit;
    }
    /**
    * 清除迅搜索引，谨慎操作
    * @author lifucang
    */
	public function executeCleanXs(sfWebRequest $request) {
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
        $xs = new XS('epg_wiki');
        $index = $xs->index; 
        $index->clean();  //清除所有索引
        echo "已成功清除训搜索引!";
		return sfView::NONE;
	}
    /**
    * 得到迅搜索引数量
    * @author lifucang
    */
	public function executeGetXsCount(sfWebRequest $request) {
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
		$xs = new XS('epg_wiki'); 
		$search = $xs->search;
        echo "训搜总搜索数:",$search->count();
        echo "<br/>";
        $search->setQuery('hasvideo:1')->search();
        echo "有视频数:",$search->count();
		return sfView::NONE;
	}
    /**
    * 迅搜查询，默认查询所有hasvideo为1的
    * @author lifucang
    */
	public function executeGetXsList(sfWebRequest $request) {
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
        $query=$request->getParameter('q','hasvideo:1');
		$xs = new XS('epg_wiki'); 
		$search = $xs->search; 
        $search->setQuery($query);
        echo $search->count();
        echo '<br/>';
		$objs = $search->setQuery($query)
					   ->setLimit(300,0)
                       ->setSort('id')
					   ->search();
        $i=0;
		foreach($objs as $obj){
			echo $obj['id'],'---',$obj['title'],'---',$obj['tag'],'---',$obj['hasvideo'],'---',$obj['video_update'],'<br/>';
		    $i++;
        }
        echo "count:$i";
		return sfView::NONE;
	}
	/**
	 * 清除memcache缓存，谨慎使用
     * @author lifucang
	 */
	public function executeMemClear(sfWebRequest $request) {
        $memcache = tvCache::getInstance();
        $memcache -> clear();
        echo '缓存已全部清除'; 
        return sfView::NONE;
	}
    /**
    * 查找wiki中has_video>0中无图片的
    * @author lifucang
    */
	public function executeGetWikiNoCover(sfWebRequest $request) {
	    $query=array(
            '$or'=>array(array('cover'=>''),array('cover'=>array('$exists'=>false))),
            'has_video'=>array('$gt'=>0)
        );
        $mongo = $this->getMondongo();
        $wikis = $mongo->getRepository('Wiki')->find(array('query'=>$query));
        echo "<table border=1>";
        $i=0;
        foreach($wikis as $wiki){
            echo "<tr><td>",(string)$wiki->getId(),"</td><td>",$wiki->getTitle(),"</td></tr>";
            $i++;
        }
        echo "</table>";
        echo "count$i";
		return sfView::NONE;
	}
    /**
    * 获取所有频道logo
    * @author lifucang
    */
	public function executeGetChannelLogo(sfWebRequest $request) {
	    sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
        $mongo = $this->getMondongo();
        $channels = $mongo->getRepository('SpService')->getServicesByTag();
        echo "<table bgcolor='#999999' border=1>";
        foreach($channels as $channel){
    		if ($channel->getChannelLogo()){
    		    $channel_logoa=thumb_url($channel->getChannelLogo(),75,110,'122.193.13.36');
                $channel_logos=explode('/',$channel_logoa);
                //$channel_logo="http://".$channel_logos[2].'/2012/12/12/'.$channel_logos[6];
                $channel_logo="http://122.193.13.36:81/2012/12/12/".$channel_logos[6];
    		}else{
    		    $channel_logo='';
                $channel_logoa='';
    		}  
            //echo "<tr><td>",$channel->getName(),"</td><td><img src='",$channel_logo,"'></img></td><td><img src='",$channel_logoa,"'></img></td></tr>";
            echo "<tr><td>",$channel->getName(),"</td><td><img src='",$channel_logo,"'></img></td></tr>";
        }
        echo "</table>";
		return sfView::NONE;
	}
	/**
	 * 统计节目信息
     * @author lifucang
	 */
	public function executeCountProgram(sfWebRequest $request) {
        sfConfig::set('app_photo1_config', array('hosts' => '172.31.201.101:6001', 'domain' => 'epg', 'class' => 'image'));
        sfConfig::set('app_photo1_type', 'MogilefsStorage');
        sfConfig::set('app_static1_url','http://image.epg.huan.tv/');
        $date = $request->getParameter('date',date("Y-m-d"));
        $mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        
        $program_num = $programRes->count(array('date'=>$date));
        echo $date,'共有节目数：',$program_num,"<br/>";
        
        $wiki_num = $programRes->count(array('date'=>$date,'wiki_id'=>array('$exists'=>true)));
        echo '匹配wiki数：',$wiki_num,"<br/>";
                
        $coverNum=0;
        $noCoverNum=0;        
        $query = array('date'=>$date,'wiki_id'=>array('$exists'=>true));        
        $programs=$programRes->find(array('query'=>$query));
        
        $storage = StorageService::get('photo1');
        foreach($programs as $program){
            $wikiCover=$program->getWikiCover();
            $content = $storage->get($wikiCover);
            if($content){
                $coverNum++;
            }else{
                $noCoverNum++;
            }
        }
        echo '有海报数：',$coverNum," 无海报数：$noCoverNum<br/>";
        return sfView::NONE;
	}
	/**
	 * 统计央视节目信息
     * @author lifucang
	 */
	public function executeCountCCTVProgram(sfWebRequest $request) {
        $startDate = $request->getParameter('startdate',date("Y-m-d",strtotime("-7 days")));
        $endDate = $request->getParameter('enddate',date("Y-m-d"));
        $mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        $channels=$mongo->getRepository('SpService')->getServicesByTag('cctv');
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getChannelCode();
        }
        
        $program_num = $programRes->count(array('date'=>array('$gte'=>$startDate,'$lte'=>$endDate),'channel_code' => array('$in'=>$channel_codes)));
        echo $startDate,'-',$endDate,'央视共有节目数：',$program_num,"<br/>";
        
        $wiki_num = $programRes->count(array('date'=>array('$gte'=>$startDate,'$lte'=>$endDate),'wiki_id'=>array('$exists'=>true),'channel_code' => array('$in'=>$channel_codes)));
        echo '匹配wiki数：',$wiki_num,"<br/>";
                
        return sfView::NONE;
	}
	/**
	 * 获取所有频道数据
     * @author lifucang
	 */
	public function executeGetSp(sfWebRequest $request) {

        $mongo = $this->getMondongo();
        $sps = $mongo->getRepository('SpService')->find(array("sort" => array("logicNumber" => 1)));
        echo "<table border=1>";
        $i=0;
        foreach($sps as $sp){
            echo "<tr><td>",(string)$sp->getLogicNumber(),"</td><td>",(string)$sp->getName(),"</td><td>",$sp->getChannelID(),"</td></tr>";
            $i++;
        }
        echo "</table>";
		return sfView::NONE;
	}
	/**
	 * 获取content_import 里面重复的from_id
     * @author lifucang
	 */
	public function executeGetImportRepeat(sfWebRequest $request) {
	    set_time_limit(0); 
        $mongo = $this->getMondongo();
        $import_repo = $mongo->getRepository("ContentImport");

        $query=array();
        $count = $import_repo->count();

        echo "count:",$count,"\n";
        sleep(1);
        $i = 0;
        $arr=array();
        ob_end_flush();//关闭缓存 
        echo str_repeat("　",256); //ie下 需要先发送256个字节 
        while ($i < $count) 
        {
            $imports = $import_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach ($imports as $import) 
            {
                $from_id=$import->getFromId();
                $id=$import->getId();
                $search=$import_repo->findOne(array("query"=>array('from_id'=>$from_id,'_id'=>array('$ne'=>$id))));
                if($search){
                    $arr[]=$from_id;
                }
            }
            $i = $i + 200;
            echo $i,'******',"\n";
            flush(); 
            sleep(1);
        }
        sleep(2);
        $k=0;
        foreach($arr as $value){
            if($k>500) break;
            echo $value,"<br/>";
            $k++;
        }
		return sfView::NONE;
	}
	/**
	 * 根据id删除某视频
     * @author lifucang
	 */
    public function executeVideoDel(sfWebRequest $request){
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $video_repository = $mongo->getRepository('Video');
        $query=array("_id"=>new MongoId($id));
        $video=$video_repository->findOne(array('query'=>$query));
        if($video){
            $video->delete();
        }
        echo $id,'视频已删除';
        return sfView::NONE;
    }
	/**
	 * 判断文件服务器是否存在某key值的图片
     * @author lifucang
	 */
    public function executeGetStorageByKey(sfWebRequest $request){
        $key = $request->getParameter('key');
        $storage = StorageService::get('photo');
        $content = $storage->get($key);
        if(!$content){
            echo '不存在！';
        }else{
            echo '存在';
        }
        return sfView::NONE;
    }
	/**
	 * 在消息队列服务器写入队列测试数据
     * @author lifucang
	 */
    public function executeHttpsqsPut(sfWebRequest $request){
        $httpsqs = HttpsqsService::get();
        $queue = $request->getParameter('queue','epg_queue');
        //测试队列数据开始
        for($i = 0; $i < 10; $i ++) {
            $array = array("title" => "video_add".$i,
                   "action" => "video_add",
                   "created_at" => time(),
                   "parms" => array("type" => "film",
                                    "url" => "http://www.baidu.com",
                                    "wiki_id" => "12345"));
            $result = $httpsqs->put('epg_queue',json_encode($array)); 
        }
        //测试队列数据结束 
        echo '成功放进10个数据';
        return sfView::NONE;
    }
	/**
	 * 获取更新的频道数据
     * @author lifucang
	 */
    public function executeGetChannelEpgUpdate(sfWebRequest $request){
        $channels = Doctrine::getTable("Channel")->getChannels();
        $this->channel_list=array();
        foreach ($channels as $channel){
            $editortime=strtotime($channel->getEditorUpdate());  //编辑确认时间
            $updatetime=strtotime($channel->getEpgUpdate());   //epg更新时间
            if($updatetime>strtotime(date('Y-m-d 2:00'))){
                if($editortime){
                    if($editortime>$updatetime){
                        $update=false;
                    }else{
                        $update=true;
                    }
                }else{
                    $update=true;
                }
            }else{
                $update=false;
            }
            if($update){
                $this->channel_list[]=array('id'=>$channel->getId(),'code'=>$channel->getCode(),'name'=>$channel->getName(),'editor_update'=>$channel->getEditorUpdate(),'epg_update'=>$channel->getEpgUpdate(),'epg_get'=>$channel->getEpgGet());
            }
        }
        echo "<pre>";
        print_r($this->channel_list);
        return sfView::NONE;
    }
	/**
	 * 设置video的page_id
     * @author lifucang
	 */
    public function executeVideoSetPageId(sfWebRequest $request){
        $id = $request->getParameter('id','yang.com20130404001800');
        $pid = $request->getParameter('pid','10966996');
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');
        $querya = array('config.asset_id'=>$id);
        $video=$video_repo->findOne(array('query'=>$querya));
        if($video){
            $video->setPageId($pid);
            $video->save();
            
        }
        echo '设置page_id完成',"\n";
        return sfView::NONE;
    }
	/**
	 * 上线数据分析
     * @author lifucang
	 */
    public function executeCdiTest(sfWebRequest $request){
        $id = $request->getParameter('id','51f1dd907b5fbdb44cc291bf');
        $mongo = $this->getMondongo();
        $cdi_repo = $mongo->getRepository('ContentCdi');
        $querya = array('_id'=>new MongoId($id));
        $cdi=$cdi_repo->findOne(array('query'=>$querya));
        if($cdi){
            $jsonstr=$cdi->getContent();
            $content = @simplexml_load_string(trim($jsonstr)); 
            foreach($content->body->tasks->task as $val){
                $attr=$val->attributes();
                $children_id=(string)$attr['subcontent-id'];
                break;
            }
            $page_id=$content->body->tasks->task->play-url;
            echo '<pre>';
            print_r($content);
            echo $children_id,"<br/>";
            print($page_id);
        }
        return sfView::NONE;
    }
	/**
	 * 检查wiki中has_video>0但是video没有相关wiki视频数据的
     * @author lifucang
	 */
    public function executeWikiCheckVideo(sfWebRequest $request){

        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');
        $wiki_repo = $mongo->getRepository('Wiki');
        
        $querya = array('has_video'=>array('$gt'=>0));
        $wikis=$wiki_repo->find(array('query'=>$querya));
        foreach($wikis as $wiki){
            $wiki_id=(string)$wiki->getId();
            $has_video=$wiki->getHasVideo();
            $videoCount=$video_repo->count(array('wiki_id'=>$wiki_id));
            if($videoCount!=$has_video){
                if($videoCount==0){
                    echo $wiki->getTitle(),'|',$has_video,"无视频<br/>";
                }else{
                    echo $wiki->getTitle(),'|',$videoCount,'|',$has_video,"视频数不对<br/>";
                }
            }
        }
        echo '完成';
        return sfView::NONE;
    }
	/**
	 * 设置wiki的has_video值和video一致
     * @author lifucang
	 */
    public function executeWikiSetVideo(sfWebRequest $request){

        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');
        $wiki_repo = $mongo->getRepository('Wiki');
        
        $querya = array('has_video'=>array('$gt'=>0));
        $wikis=$wiki_repo->find(array('query'=>$querya));
        foreach($wikis as $wiki){
            $wiki_id=(string)$wiki->getId();
            $has_video=$wiki->getHasVideo();
            $videoCount=$video_repo->count(array('wiki_id'=>$wiki_id));
            if($videoCount!=$has_video){
                echo $wiki->getTitle(),"<br/>";
                $wiki->setHasVideo($videoCount);
                $wiki->save();
                //$wiki -> updateXunSearchDocument();
            }
        }
        echo '更正完成';
        return sfView::NONE;
    }
	/**
	 * 检查video中有数据，但wiki的has_video值没数据的
     * @author lifucang
	 */
    public function executeVideoCheckWiki(sfWebRequest $request){

        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');
        $wiki_repo = $mongo->getRepository('Wiki');
        
        $count=$video_repo->count();
        $wikiIds=array();
        $i=0;
        while ($i < $count) 
        {
            $videos=$video_repo->find(array("sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach($videos as $video){
                $wikiIds[]=(string)$video->getWikiId();
            }
            $i = $i + 200;
        }        
        
        $wikiIds=array_unique($wikiIds);
        echo "count:",count($wikiIds),"<br/>";

        foreach($wikiIds as $wikiId){
            if($wikiId){
                $wiki=$wiki_repo->findOneById(new MongoId($wikiId));
                $has_video=$wiki->getHasVideo();
                if($has_video==0||!$has_video){
                    echo $wiki->getTitle(),'|',$has_video,"无视频<br/>";
                    //重写wiki的has_video值
                    $videoCount=$video_repo->count(array('wiki_id'=>$wikiId));
                    $wiki->setHasVideo($videoCount);
                    $wiki->save();
                    echo '------重写视频数成功!<br/>';
                }
            }
        }
        echo '完成';
        return sfView::NONE;
    }
	/**
	 * 检查videoPlayList有但是video没有相关视频数据的
     * @author lifucang
	 */
    public function executeVideoPlayListCheck(sfWebRequest $request){

        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');
        $videoPlayList_repo = $mongo->getRepository('VideoPlayList');
        $wiki_repo = $mongo->getRepository('Wiki');
        $videoPlayLists=$videoPlayList_repo->find();
        foreach($videoPlayLists as $videoPlayList){
            $id=(string)$videoPlayList->getId();

            $videoCount=$video_repo->count(array('video_playlist_id'=>$id));
            if($videoCount==0){
                echo $videoPlayList->getTitle(),"---无视频<br/>";
                $videoPlayList->delete();
                echo "---已删除<br/>";
            }
        }
        /*
        echo '<p>以下是wiki统计结果---<br/>';
        foreach($videoPlayLists as $videoPlayList){
            $id=(string)$videoPlayList->getWikiId();

            $wiki=$wiki_repo->findOneById(new MongoId($id));
            $has_video=$wiki->getHasVideo();
            if($has_video==0){
                echo $videoPlayList->getTitle(),"---无视频<br/>";
            }
        }
        */
        echo '完成';
        return sfView::NONE;
    }
	/**
	 * 网址监测
     * @author lifucang
	 */
    public function executeCheckUrl(sfWebRequest $request){
        $url=$request->getParameter('url','172.31.200.11');
        $http_response = '';
        $fp = fsockopen('172.31.200.11', 80);
        if($fp){
            echo '可以打开';
        }else{
            echo '有问题';
        }
        //fputs($fp, "GET / HTTP/1.1\r\n");
        //fputs($fp, "Host: www.php.net\r\n\r\n");
        while (!feof($fp)){
            $http_response .= fgets($fp);
        }
        fclose($fp);
        print_r($http_response);
        //echo nl2br(htmlentities($http_response));
        return sfView::NONE;
    }
	/**
	 * 测试xml是否能正确解析
     * @author lifucang 2013-09-23
	 */
    public function executeTestXml(sfWebRequest $request){
        if ($request->getMethod() == 'POST') { 
			if($request->getPostParameter('xmlString')){
				$xmlstr = $request->getPostParameter('xmlString');
			}else {
				$xmlstr = file_get_contents('php://input');
			}
    		$content = simplexml_load_string($xmlstr);
            echo '<pre>';
            print_r($content);
            return sfView::NONE;
        }
    }
    /*
    ********************************************************************
    此处以下的都只是临时调用，可以删除
    ********************************************************************
    */   
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeContentImport(sfWebRequest $request)
    {
        $this->acceptTypes = array("program","series");
        $mongo = $this->getMondongo();
        $inject_repo = $mongo->getRepository("ContentInject"); 
        $import_repo = $mongo->getRepository("ContentImport");      

        //$injects = $inject_repo->find(array("query"=>array("state"=>0),"sort"=>array("_id"=>-1),"limit" => 200));
        $injects = $inject_repo->find(array("query"=>array("state"=>0),"limit" => 200));
        if(!$injects){  
            echo "finished!";                       
        }else{
            echo "<pre>";
            $m=0;
            foreach($injects as $inject) {
                if($content = @simplexml_load_string(trim($inject->getContent()))) {
                    $adi_md = $this->getMetadata($content->Metadata);
                    $asset_md = $this->getMetadata($content->Asset->Metadata);
                    //$children = $content->Asset->Asset->Metadata->AMS;

                    if(!isset($asset_md['Show_Type'])) {
                        continue;
                    }
                    if(in_array($asset_md['Show_Type'],$this->acceptTypes)) {
                        //echo $children->attributes()->Asset_ID;
                        //print_r($adi_md);
                        //print_r($asset_md);
                        //获取分集信息  
                        echo ($content->Asset->Asset->Metadata->AMS->attributes()->Asset_Class[0]);
                        $videos=$this->getVideos($content->Asset->Asset);
                        print_r($adi_md);
                        print_r($asset_md);
                        print_r($videos); 
                        echo $m,'*************************************',"\n"; 
                        $m++;
                    }                   
                }
                if($m>2) break;
            }
        }
        return sfView::NONE;
    } 
    
    //executeContentImport用到的函数
    private function getVideos($Metadata) {
        $p = array();
        if(isset($Metadata)){
            foreach($Metadata as $val) {
                $movies=$this->getMetadata($val->Metadata);
                if($movies['Asset_Class']=='movie'){
                    $p[]=  $movies;
                }
            }  
        }   
        return $p;
    }

    //executeContentImport用到的函数
    private function getMetadata($Metadata) {
        $p = array();
        if(isset($Metadata)){
            $p = $this->getAttrs($Metadata->AMS);
            if(isset($Metadata->App_Data)){
                foreach($Metadata->App_Data as $key => $val) {
                    list($name,$value) = $this->getArrayByAttrs($val);
                    $p[$name] = $value;
                }  
            }
        }
        return $p;
    }
    //executeContentImport用到的函数
    private function getArrayByAttrs($s) {
        foreach($s->attributes() as $key => $val) {
            if($key == "Name"){
                $Name = (string)$val;
            }
            if($key == "Value"){
                $Value = (string)$val;
            }
        }
        return array($Name,$Value);
    }
    //executeContentImport用到的函数
    private function getAttrs($s) {
        $arr=array();
        if(isset($s)){
            foreach($s->attributes() as $key => $val) {
               $arr[$key] = (string)$val;
            }  
        }
        return $arr;
    } 

    /*
    * 临时测试
    * @author lifucang
    */   
	public function executeGetSpCode(sfWebRequest $request) {
        $channel_local=array('南京少儿',
                            '南京新闻',
                            '南京影视',
                            '南京生活',
                            '南京娱乐',
                            '南京信息',
                            '南京教科',
                            '南京十八',
                            '江苏卫视',
                            '江苏城市',
                            '江苏综艺',
                            '江苏影视',
                            '江苏教育',
                            '江苏公共',
                            '江苏体育',
                            '优漫卡通卫视',
                            '动漫秀场',
                            '全纪实',
                            '东方财经',
                            '游戏风云',
                            '劲爆体育',
                            '极速汽车',
                            '魅力音乐',
                            '卫生健康',
                            '生活时尚',
                            '欢笑剧场',
                            '都市剧场',
                            '金色频道',
                            '七彩戏剧',
                            'SITV新视觉',
                            '时代美食',
                            '老年福',
                            '梨园',
                            '第一剧场',
                            '环球奇观',
                            '游戏竞技',
                            '先锋纪录',
                            '发现之旅',
                            '风云剧场',
                            '央视精品',
                            '风云音乐',
                            '风云足球',
                            '高尔夫网球',
                            '央视怀旧剧场',
                            '世界地理',
                            '孕育指南',
                            '留学世界',
                            '早期教育',
                            '欧洲足球',
                            'DOXTV音像世界',
                            '江苏靓妆',
                            '天元围棋',
                            '书画',
                            '中国气象',
                            '四海钓鱼',
                            '快乐宠物',
                            '车迷',
                            '法治天地频道',
                            '环球旅游',
                            '摄影',
                            '幸福彩',
                            '收藏天下',
                            '家庭健康',
                            '优优宝贝',
                            '国防军事',
                            '英语辅导',
                            '法治天地',
                            '幼儿教育',
                            '江苏卫视高清',
                            'CHC高清电影',
                            'SITV新视觉高清',
                            'DOX映画',
                            'DOX剧场',
                            'DOX新知',
                            'DOX新艺',
                            '江苏国际',
                            'CHC家庭影院',
                            'CHC动作电影',
                            '中华美食',
                            '先锋乒羽',
                            '新动漫',
                            '网络棋牌',
                            '江苏招考',
                            );
        $channels=array();      
        $i=0;              
        $mongo = $this->getMondongo();
        foreach($channel_local as $value){
            $sp = $mongo->getRepository('SpService')->getSpByname($value);
            if($sp){
                $channels[$sp->getChannelCode()]=$value;
            }else{
                $channels[$i]=$value;
            }
            $i++;
        }
        //echo "<pre>";
        //print_r($channels);
        $k=0;
        foreach($channels as $key=>$value){
            if($key!=''){
                echo "'$key',";
                $k++;
            }
        }
        
        echo '<br/>';
        echo $k;

        
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeGetNJBCPrograms(sfWebRequest $request) {
	    echo "1\n";
        $ftp_conn = ftp_connect("110.173.3.73") or die("FTP服务器连接失败"); 
        ftp_login($ftp_conn ,"njepg","njepg#025#bk") or die("FTP服务器登陆失败");
        echo "2\n";
        $ftp_dir = ftp_nlist($ftp_conn,"/");
        if($ftp_dir){
            //$this->getDirList($ftp_dir);
            echo "3\n";
            print_r($ftp_dir);
        }else{
            echo "No files!\n";
        }
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeGetWikiById(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wikis = $mongo->getRepository("Wiki"); 
        $wiki = $wikis->findOneById(new MongoId('50bd71245570ec9453001600'));
        echo $wiki->getUpdatedAt()->getTimestamp();
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */      
	public function executeTest1(sfWebRequest $request) {
        preg_match ("/\d+/", "攻心 第24集", $m);
        print_r($m);
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeVideo(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $videor = $mongo->getRepository("Video"); 
        $videos =$videor->find(array('query'=>array('referer'=>'CP1N02A08_003'),'limit'=>100));
        foreach($videos as $video){
            echo "<a href='".$video->getUrl()."'>",iconv("utf-8","gbk",$video->getTitle()),"</a><br/>";
            //echo "<a href='".$video->getUrl()."'>",$video->getTitle(),"</a><br/>";
        }
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeTestFile(sfWebRequest $request) {
	    $path="../tmp/epg/";
        $dir = opendir($path);
        $date=date("Y-m-d");
        $num=0;
        while ($file = readdir($dir)){
            //if($file=='.'||$file=='..') continue;
            if(date("Y-m-d",filemtime($path.$file))==$date){
                echo "filename: " . iconv('gbk','utf-8',$file) . "------",date("Y-m-d H:i:s",filemtime($path.$file)). "<br />";
                $num++;
            }
        }
        echo $num;
        closedir($dir);
        /*
        $files=scandir("../tmp/epgbak");
        foreach($files as $file){
            if($file=='.'||$file=='..') continue;
            echo "filename: " . iconv('gbk','utf-8',$file) . "<br />";
        }
        */
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeFileTest(sfWebRequest $request) {
        $key = "魅力音乐.xml";
        //$fsc=new FSC();
        //$exist=$fsc->isFile('./tmp/epg/',$key);
        $files=scandir('../tmp/epg/');
        echo '<pre>';
        print_r($files);
        if(in_array($key,$files))
            echo '存在';
        else
            echo '不存在';     
 		return sfView::NONE;
	}
    
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeVideoSearch(sfWebRequest $request) {
	    $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $query=array('referer'=>'CP1N02A08_003','config.asset_id'=>'PA202266661212000002');
        $video=$video_repo->findOne(array('query'=>$query));
        if($video){
            echo "<pre>";
            $assetid=$video->getConfig();
            //print_r($assetid);
            echo $assetid['asset_id'];
            print_r($video);
        } 
  
 		return sfView::NONE;
	}    
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeContentTest(sfWebRequest $request) {
	    $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("ContentImport"); 
        $wiki_repo = $mongo->getRepository("Wiki"); 
        $query=array('from_title'=>'AA制生活');
        $video=$video_repo->findOne(array('query'=>$query));
        if($video){
            echo '<pre>';
            print_r($video);
            $child=$video->getChildrenId();
            echo $child[0];
        } 
 		return sfView::NONE;
	}     
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeTestUrl(sfWebRequest $request) {
	    echo "getUri",$request->getUri(),"<br/>";
        echo "getReferer",$request->getReferer(),"<br/>";
        echo "getHost",$request->getHost(),"<br/>";
        echo "getUrlParameter",$request->getUrlParameter(),"<br/>";
        echo "getRemoteAddress",$request->getRemoteAddress(),"<br/>";
 		return sfView::NONE;
	} 
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeGetVideos(sfWebRequest $request) {
	    
        $mongo = $this->getMondongo();
        $wiki = $mongo->getRepository('Wiki')->findOneById(new MongoId('4d00872b2f2a241bd700c456'));
        $playlist=$wiki->getPlayList('yang.com');
        echo '<pre>';
        if($playlist){
            print_r($playlist);
            $videos = $playlist[0]->getVideos();
            foreach($videos as $video){
                echo $video->getTitle(),'<br/>';
            }
        }                  
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeGetWikiVideo(sfWebRequest $request) {
	    $query=array(
            'has_video'=>array('$gt'=>0)
        );
        $mongo = $this->getMondongo();
        $wikis = $mongo->getRepository('Wiki')->find(array('query'=>$query,'sort'=>'_id'));
        echo "<table border=1>";
        foreach($wikis as $wiki){
            echo "<tr><td>",(string)$wiki->getId(),"</td><td>",$wiki->getTitle(),"</td><td>",$wiki->getHasVideo(),"</td></tr>";
        }
        echo "</table>";
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeGetTongZhou(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $sp_repository = $mongo->getRepository('SpService');
        $programs = $mongo->getRepository('program');
        $url="http://172.31.178.6:10080/recommand/recommand/epgAction.action?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=20&filter=Entertainment&uid=".$user_id;
        //$url = 'http://172.31.178.6:10080/recommand/recommand/epgAction.action?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid='.$user_id.'&backurl=http://'.$request->getHost().'/';
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents,true);
            $k=0;
            echo '<pre>';

            foreach($arr_contents['recommend'][0]['recommand'] as $value){
                echo $value['Channel_ID'],1;
                echo '<br/>';      
            }
        }
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeTcltest(sfWebRequest $request) {
	    echo '<pre>';
	    $user_id='8250102886999246';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:8250102886999246</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='8250102886999238';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:8250102886999238</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='825010288699924';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:825010288699924</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='825010288699923';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:825010288699923</b>',"<br/>";
            print_r($arr_contents);
        }
        

		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeTcltest1(sfWebRequest $request) {
	    echo '<pre>';
	    $user_id='8250102886999246';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=30&genre=Series&uid=".$user_id;
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:8250102886999246</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='8250102886999238';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=30&genre=Series&uid=".$user_id;
        $contents=Common::get_url_content($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:8250102886999238</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='825010288699924';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=30&genre=Series&uid=".$user_id;
        $contents=Common::get_url_content($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:825010288699924</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='825010288699923';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=30&genre=Series&uid=".$user_id;
        $contents=Common::get_url_content($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:825010288699923</b>',"<br/>";
            print_r($arr_contents);
        }
        

		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeTcltest2(sfWebRequest $request) {
	    echo '<pre>';
	    $user_id='8250102886999246';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:8250102886999246</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='8250102886999238';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:8250102886999238</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='825010288699924';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:825010288699924</b>',"<br/>";
            print_r($arr_contents);
        }
        
	    $user_id='825010288699923';
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=20&uid=".$user_id;
        $contents=Common::get_url_content($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            echo '<b>cardID:825010288699923</b>',"<br/>";
            print_r($arr_contents);
        }
        

		return sfView::NONE;
	}
    /*
    * 临时测试   获取重复title的wiki
    * @author lifucang
    */  
	public function executeGetWikiTitle(sfWebRequest $request) {
	    set_time_limit(0); 
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");

        $query=array();
        $wiki_count = $wiki_repo->count();

        echo "count:",$wiki_count,"\n";
        sleep(1);
        $i = 0;
        $arr_title=array();
        ob_end_flush();//关闭缓存 
        echo str_repeat("　",256); //ie下 需要先发送256个字节 
        while ($i < $wiki_count) 
        {
            $wikis = $wiki_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach ($wikis as $wiki) 
            {
                $title=$wiki->getTitlte();
                $wikititle=$wiki_repo->findOne(array("query"=>array('title'=>$title)));
                if($wikititle){
                    $arr_title[]=$title;
                }
            }
            $i = $i + 200;
            echo $i,'******',"\n";
            flush(); 
            sleep(1);
        }
        sleep(2);
        $k=0;
        foreach($arr_title as $value){
            if($k>500) break;
            echo $value,"<br/>";
            $k++;
        }
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeVideoConfig(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Video');
        $video=$repository->findOne(array('query'=>array('title'=>'妻子的诱惑062')));
        $config=$video->getConfig();
        $asset_id=$config['asset_id'];
        echo $asset_id;
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeGetPPTVVideoUrl(sfWebRequest $request)
    {
        $asset_id = $request->getParameter('asset_id');
        $sp_code  = $request->getParameter('sp_code');
        $userid   = $request->getParameter('user_id'); 
        $urlpptv = "http://172.31.155.22:9080/core/ContentLinksQuery.do?spcode=SP1N02A08_003&assetid=$asset_id&usercode=$userid&stbno=10000&movieassetid=$asset_id";
        $data=Common::get_url_content($url);
        if($data){
    		$result = json_decode($data,true);
            echo $result['BackURL'];  
        }else{
            echo '空网址';
        }
        return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeWikiRecommend(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $recommend_repository = $mongo->getRepository('WikiRecommend');
        $recommends=$recommend_repository->find();
        foreach($recommends as $recommend){
            $wiki=$wiki_repository->findOneById(new MongoId($recommend->getWikiId()));
            if(!$wiki){
                echo $recommend->getWikiId(),"<br/>";
            }
        }
		return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeGetWikisDayTest(sfWebRequest $request)
    {
        //$this->connectMaster($options);
        $startTime=$request->getParameter('startTime');
        $endTime=$request->getParameter('endTime');
        $queryat=$request->getParameter('queryat');
        
        $mongo = $this->getMondongo();
        $url=sfConfig::get('app_epghuan_url');
        if(isset($queryat)){
            $startTime=$startTime?$startTime:date("Y-m-d 00:00:00");
            $endTime=$endTime?$endTime:date("Y-m-d 23:59:59");
            $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'","queryat":"'.$queryat.'"}}';
        }else{
            if (isset($startTime)) {
                $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'"}}';
            }else{
                $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"}}';
            } 
        }
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $wikis=$result['wiki']?$result['wiki']:array();   
        echo '<pre>';
        print_r($wikis); 
        /*   
        $i=0;
        $k=0;
        $count=0;
        foreach($wikis as $wikiinfo){
               $wiki_exists = $mongo->getRepository("Wiki")->findOneById(new MongoId($wikiinfo['id']));
               if(!$wiki_exists){
                    $this->importWiki($url,$wikiinfo,$options);   
                    $i++;
               }else{
                    if($queryat=='updated_at'){
                        $this->updateWiki($url,$wikiinfo,$wiki_exists);   
                        $k++;     
                    }
               }  
               $count++;     
        }    
        */
        echo date("Y-m-d H:i:s"),'------',"Count:$count;WikiAdd:".$i.";WikiUpate:".$k; 
        echo "------finished!\r\n"; 
		return sfView::NONE;
    }
    /*
    * 临时测试   敏感词测试
    * @author lifucang
    */  
    public function executeReplaceSensitiveWords(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Setting');
        $query = array('query' => array( "key" => 'sensitiveWords' ));
        $rs = $repository->findOne($query);
        if($rs){
            $words=$rs->getValue();
        }
        echo '<pre>';
		$words=str_replace('/',"\/",$words);
        $words="/".$words."/";
        $words=str_replace(',',"/,/",$words);
        $words=str_replace('“','\“',$words);
        $words=str_replace('”','\”',$words);
		$words=str_replace('.','\.',$words);
		$words=str_replace('[','\[',$words);
		$words=str_replace(']','\]',$words);
        //print_r($words);  
        $patterns=explode(',',$words);    

        $str="办公室的故事，还有西藏独立";
        echo $str,"<br/>";
        $str = preg_replace($patterns, "*", $str);
        echo $str;
		return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeTestMatchWiki(sfWebRequest $request){
        $title = $this->getSubTitle('新闻夜宴');
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        if($title){
            $wiki = $wiki_repository->getWikiByTitle($title);
            if($wiki){
                echo $wiki->getTitle(),"<br/>";
                echo (string)$wiki->getId(),"<br/>";
            }  
        }
        return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    private function getSubTitle($str){
        //忽略
        $passs = array("蜂乃宝天天30分");    
        if(in_array($str,$passs)) {
            return $str;
        }
        
        //替换
        $patterns = array('/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/','/《.*》/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/复/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/.*剧场/','/;提示/',
                          '/第.*集/','/\d+集/','/\d/','/―/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/-.*/');
                          
        $str = preg_replace($patterns, "", $str);
        
        //替换
        $patterns = array('/法治中国/','/视野/','/爱探险的朵拉/',
                          '/欧美流行.*/');
        $repatt = array('法治中国（江苏）','视野（辽宁）','爱探险的Dora',
                        '欧美流行');
        $str = preg_replace($patterns, $repatt, $str);
        $str=str_replace("\r","",$str);
        $str=str_replace("\n","",$str);
        $str=str_replace("\n\r","",$str);
        return $str;
    }

    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeAdiTest(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $inject_repo = $mongo->getRepository("ContentInject");     

        //$injects = $inject_repo->find(array("query"=>array("state"=>0),"sort"=>array("_id"=>-1),"limit" => 200));
        $injects = $inject_repo->findOneById(new MongoId('51f9cf137b5fbd056a14bf86'));
        if(!$injects){  
            echo "finished!";                       
        }else{
            echo "<pre>";
            $jsonstr=$injects->getContent();
            //echo '解析前<br/>';     
            //print_r($jsonstr);
            $content = @simplexml_load_string(trim($jsonstr));
            $asset_md = $this->getMetadata($content->Asset->Metadata);
            echo $asset_md['Show_Type'];
            $videos=$this->getSeriesVideos($content);
            print_r($videos);
            //echo '解析后<br/>';      
            //print_r($content);          
            //$adi_md = $this->getMetadata($content->Metadata);
            //$asset_md = $this->getMetadata($content->Asset->Metadata);
            //print_r($adi_md); 
            //print_r($asset_md);
            //异步反馈
            //$backxmlstring = $this->getBackXmlString($adi_md['Asset_ID'], $adi_md['Asset_ID'], $asset_md['Show_Type'], 0, 'ok'); 
            
        }
        return sfView::NONE;
    } 
    //获取电视剧movie信息
    private function getSeriesVideos($xml_strs) {
        $p = array();
        $asset = $xml_strs->Asset;       
        for($i = 0; $i < $asset->count(); $i ++) {
            $title = $this->getMetadata($asset[$i]->Metadata);
            //里面还有多个asset,有language,poster,movie等，还得循环
            $subasset = $asset[$i]->Asset;   
            for($k = 0; $k < $subasset->count(); $k ++){
                $meta = $this->getMetadata($subasset[$k]->Metadata);
                if($meta['Asset_Class'] == "movie"&&$meta['Screen_Format'] == 1){
                    $meta['Chapter'] = $title['Chapter'] ? $title['Chapter'] : 0;
                    //$p[] = $meta;
                    $p[]=array(
                        'Asset_ID' => $meta['Asset_ID'],
                        'Asset_Name' => $meta['Asset_Name'],
                        'Chapter' => $meta['Chapter'],
                        'HD_Content' => $meta['HD_Content']
                    );
                }
            }
        }    
        return $p;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    private function getBackXmlString($asset_id, $import_id, $type, $status, $desc = '') {
        $xml = "<?xml version = \"1.0\" encoding=\"utf-8\"?>";
        $xml .= "<SyncContentsResult Time_Stamp=\"".date("Y-m-d H:i:s")."\"  System_ID=\"epgdb\">"; 
        $xml .= "<Asset ID=\"".$asset_id."\"  Current_ID=\"".$import_id."\" Type=\"".$type."\"  Status=\"".$status."\" Desc=\"".$desc."\"></Asset>";
        $xml .= "</SyncContentsResult>";
        return $xml;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeFtpTest(sfWebRequest $request) 
    {
        $ftp_conn = ftp_connect("172.31.143.126",21); 
        ftp_login($ftp_conn ,"njepg","njepg");
        ftp_pasv($ftp_conn,TRUE);  //被动模式，否则会很慢
        $ftp_files = ftp_nlist($ftp_conn,'/');
        echo '<pre>';
        print_r($ftp_files);
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
	public function executeTestArr(sfWebRequest $request) 
    {
        $arr=array('2013-05-10'=>'5月10日','2013-05-11'=>'5月11日','2013-05-12'=>'5月12日','2013-05-13'=>'5月13日','2013-05-14'=>'5月14日');
        $date=date("Y-m-d");
        $isdate=false;
        foreach($arr as $key=>$value){
            if($date==$key){
                $isdate=true;
            }
            if($isdate){
                echo $value,"你好<br/>";
            }else{
                echo $value;
            }
        }
        //echo '<pre>';
        echo $isdate;
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeGetYangVideo(sfWebRequest $request)
	{
        $clientid = $clientid?$clientid:'01006608470056014';
        $playtype = 0;
        $backurl = $request->getReferer() ? $request->getReferer() : sfConfig::get("app_base_url");
        $contented= $request->getParameter('id','10978099');
        if(!$contented) {
            return $this->renderText("参数错误！");            
        }        
        $submit_url = sfConfig::get("app_cpgPortal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=y&contented=".$contented."&backurl=".urlencode($backurl); 
        echo '请求地址：<br/>';
        echo $submit_url,"<br/>";
        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_USERPWD, sfConfig::get("app_cpgPortal_username").":".sfConfig::get("app_cpgPortal_password")); 
        curl_setopt($curl, CURLOPT_URL, $submit_url); 
        $data = curl_exec($curl);
        curl_close($curl); 
        if(!$data) {
            echo '无返回数据<br/>';
        }
        //echo $data;
        $xmls = @simplexml_load_string($data);
            
           
        if(isset($xmls->url)) {
            echo strval($xmls->url);
        }else{
            echo '无解析成功的url<br/>';
        }
        echo '完成';
        return sfView::NONE;
	}
    /*
    * 临时测试     接收content_inject数据
    * @author lifucang
    */  
    public function executeInject(sfWebRequest $request)
    {
        if ($request->getMethod() == 'POST') { 
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
    		$result = json_decode($jsonstr,true); 
            $injects=$result['injects']?$result['injects']:array();
            if(count($injects)>0){
                $mongo = $this->getMondongo();
                foreach($injects as $inject){
                       $injectExists = $mongo->getRepository("ContentInject")->findOneById(new MongoId($inject['id']));
                       if(!$injectExists){
                            /*
                            $injectLocal = new ContentInjectNew();
                            $injectLocal -> setId(new MongoId($inject['id']));
                            $injectLocal -> setContent($inject['content']);
                            $injectLocal -> setFrom($inject['from']);
                            $injectLocal -> setState($inject['state']);
                            $injectLocal -> save();
                            */
                            return $this->renderText($inject['id']);  
                       }
                }    
                return $this->renderText('ok');  
            }else{
                return $this->renderText('false');  
            }

        }
    }
    /*
    * 临时测试  测试超时是否有效
    * @author lifucang
    */  
	public function executeCurTest(sfWebRequest $request) 
    {
        $start=microtime(true);
        $recomTxt = Common::get_url_content("10.30.20.289");
        $end=microtime(true);
        $runtime=$end-$start;   
        echo $runtime;
		return sfView::NONE;
	}
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeImportTest(sfWebRequest $request) {
        $this->query   = array('query'=> array('wiki_id'=>array('$exists'=>false)),'sort' => array('_id' => -1));
        $this->imports = new sfMondongoPager('ContentImport', 20);
        $this->imports->setFindOptions($this->query);
        $this->imports->setPage(3);
        $this->imports->init();
        
        foreach ($this->imports->getResults() as $import){
            $inject_content=$import->getInject();
            $content=@simplexml_load_string(trim($inject_content));
            $asset_md = $this->getMetadata($content->Asset->Metadata);
            $asset_asset_md = $this->getMetadata($content->Asset->Asset->Metadata);
            $injects[]=array(
                'Director'=>$asset_asset_md['Director'],
                'Actors'=>$asset_asset_md['Actors'],
                'Year'=>$asset_md['Year'],
                'Description'=>$asset_md['Description'],
            );
            unset($inject_content);
            unset($content);
            unset($asset_md);
            unset($asset_asset_md);
        }
        $this->injects=$injects;
        echo '<pre>';
        print_r($this->injects);
        return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeGetProgramList($channelname) 
    {

        if(file_exists("../tmp/epg/DOX.xml")){
            //$xml = simplexml_load_file("./tmp/epg/".$channelname.".xml");
            $xmlcontent=file_get_contents("../tmp/epg/DOX.xml");
            //$xmlcontent=str_replace(iconv('gbk','utf-8','囧'),'',$xmlcontent);
            $xmlcontent=str_replace(iconv('utf-8','gbk','郞'),iconv('utf-8','gbk','郎'),$xmlcontent);
            //$xml=@simplexml_load_string(iconv('gbk',"utf-8//IGNORE",$xmlcontent));
            $xml=simplexml_load_string($xmlcontent);
            if($xml) {
                echo '成功';
                $events = $xml->SchedulerData->Channel->Event;
                foreach($events as $event) {
                    $title = (string)$event->EventText->Name;
                    $title = str_replace("\n",'',$title);
                    echo $title,"<br/>";
                }
            }else{
                echo '失败';
            }
        }
        return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeCdi($channelname) 
    {
        $mongo = $this->getMondongo();
        $cdi_repo = $mongo->getRepository("ContentCdi"); 
        $cdi = new ContentCdi();
        $cdi->setFrom("cms");
        $cdi->setState(0);
        $cdi->setContent('aaa');            
        $cdi->save();
        usleep(100);

        $cdia = $cdi_repo->findOne(array('query'=>array('_id'=>$cdi->getId())));
        if($cdia){
            echo 3,"<br/>";
            $cdia -> setCommand('1');
            $cdia -> setSubcontentId('1');
            $cdia -> setPageId('1');
            $cdia -> save();
        }
        return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeFtpDel($channelname) 
    {
        $ftpIp = sfConfig::get('app_commonFtp_host');
        $ftpPort = sfConfig::get('app_commonFtp_port');
        $ftpUser = sfConfig::get('app_commonFtp_username');
        $ftpPass = sfConfig::get('app_commonFtp_password');
        $config = array(
        			'hostname' => $ftpIp,
        			'username' => $ftpUser,
        			'password' => $ftpPass,
        			'port' => $ftpPort
        				);
        $delDate = date("Ymd",strtotime("-10 days"));
        $ftp = new Ftp();
        $ftp->connect($config);
        echo './adi/'.$delDate;
        //$ftpFiles = $ftp->filelist("adi");
        $ftp ->delete_dir('./adi/'.$delDate);
        echo '完成';
        return sfView::NONE;
    }
    /*
    * 临时测试
    * @author lifucang
    */  
    public function executeUrlEncode($channelname) 
    {
        $backurl = urlencode('http://172.31.139.17/list');
        $backurla = urlencode(urlencode('http://172.31.139.17/list'));
        echo $backurl,"<br/>";
        echo $backurla;
        $backurl="http://hditv.jsamtv.com/vpg/forsearchindex.do?appid=cep&hd=y&movieassetid=5wNrhLa1ZAwKcbs4SQr2&backurl=http%3A%2F%2F172.31.139.17%2Flist";
        $url=explode('&backurl=',$backurl);
        print_r($url);
        /*
        $backurl = 'http://172.31.139.17/list';
        $this->redirect($backurl);
        */
        return sfView::NONE;
    }
}
