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
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        //
    }
   
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
    
    
    
    
    
    
    
    
	public function executeCleanXs(sfWebRequest $request) {
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
        $xs = new XS('epg_wiki');
        $index = $xs->index; 
        $index->clean();  //清除所有索引
        echo "已成功清除训搜索引!";
		return sfView::NONE;
	}
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
                       //->setSort('updated_at')
					   ->search();
        $i=0;
		foreach($objs as $obj){
			echo $obj['id'],'---',$obj['title'],'---',$obj['tag'],'---',$obj['hasvideo'],'---',$obj['first_letter'],'<br/>';
		    $i++;
        }
        echo "count:$i";
		return sfView::NONE;
	}
    
    
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
    private function getNewDir($ftp_conn) {
        $path = iconv("utf-8","gbk","/");
        $ftp_files = ftp_nlist($ftp_conn,$path);
        if(count($ftp_files) > 0) {
            return $ftp_files;
        }else{
            return false;
        }
    }
	public function executeGetWikiById(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wikis = $mongo->getRepository("Wiki"); 
        $wiki = $wikis->findOneById(new MongoId('50bd71245570ec9453001600'));
        echo $wiki->getUpdatedAt()->getTimestamp();
		return sfView::NONE;
	}
	public function executeTest1(sfWebRequest $request) {
        //$mongo = $this->getMondongo();
        preg_match ("/\d+/", "攻心 第24集", $m);
        print_r($m);
		return sfView::NONE;
	}
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
	
	public function executeFindNoCover(sfWebRequest $request) {
		$mongo = $this->getMondongo();
        $wiki = $mongo->getRepository("wiki"); 
        //$wikis = $wiki->find(array('query'=>array('has_video'=>array('$gt'=>0),'cover'=>array('$exists'=>false))));
        $wikis = $wiki->find(array('query'=>array('has_video'=>array('$gt'=>0),'cover'=>'')));
        //print_r($wikis);
        foreach ($wikis as $wiki) {
        	echo $wiki->getTitle().'<br>';
        }
        return sfView::NONE;
	}

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
	public function executeTestUrl(sfWebRequest $request) {
	    echo "getUri",$request->getUri(),"<br/>";
        echo "getReferer",$request->getReferer(),"<br/>";
        echo "getHost",$request->getHost(),"<br/>";
        echo "getUrlParameter",$request->getUrlParameter(),"<br/>";
        echo "getRemoteAddress",$request->getRemoteAddress(),"<br/>";
 		return sfView::NONE;
	} 
	public function executeGetChannelLogo(sfWebRequest $request) {
	    sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
        $mongo = $this->getMondongo();
        $channels = $mongo->getRepository('SpService')->getServicesByTag();
        echo "<table bgcolor='#999999' border=1>";
        foreach($channels as $channel){
    		if ($channel->getChannelLogo()){
    		    $channel_logoa=thumb_url($channel->getChannelLogo());
                $channel_logos=explode('/',$channel_logoa);
                $channel_logo="http://".$channel_logos[2].'/2012/12/12/'.$channel_logos[6];
    		}else{
    		    $channel_logo='';
                $channel_logoa='';
    		}  
            echo "<tr><td>",$channel->getName(),"</td><td><img src='",$channel_logo,"'></img></td><td><img src='",$channel_logoa,"'></img></td></tr>";
        }
        echo "</table>";
		return sfView::NONE;
	}
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
    //获取重复title的wiki
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
	public function executeVideoConfig(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Video');
        $video=$repository->findOne(array('query'=>array('title'=>'妻子的诱惑062')));
        $config=$video->getConfig();
        $asset_id=$config['asset_id'];
        echo $asset_id;
		return sfView::NONE;
	}
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
    /**
     * 对节目名称进行过滤
     * @param void $ftp_conn
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
	/**
	 * 统计节目信息
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	public function executeCountProgram(sfWebRequest $request) {
        //sfConfig::set('app_photo1_config', array('hosts' => '172.31.201.101:6001', 'domain' => 'epg', 'class' => 'image'));
        //sfConfig::set('app_photo1_type', 'MogilefsStorage');
        //sfConfig::set('app_static1_url','http://image.epg.huan.tv/');
        for($i=0;$i<28;$i++){
            
            //$date = $request->getParameter('date',date("Y-m-d"));
            $date=date("Y-m-d",strtotime("-$i days"));
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
            
            $storage = StorageService::get('photo');
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
            sleep(1);
            
        }

        return sfView::NONE;
	}
	/**
	 * 统计央视节目信息
	 * Enter description here ...
	 * @param unknown_type $param
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
    
    public function executeCdi(sfWebRequest $request)
    {
        $xml='<?xml version="1.0" encoding="UTF-8"?><message module="iCMS" version="1.0"><header timestamp="2010-11-16T00:00:00.0Z" sequence="1000001" component-id="iCMS_01" component-type="iCMS" action="REQUEST" command="ONLINE_TASK_DONE" /><body><tasks><task subcontent-id="10825042" page-id="10825042" target-system-id="portal1" target-site-id="nanjing" sp-id="1905" status="6"></task><task subcontent-id="10825043" page-id="10825042" target-system-id="portal1" target-site-id="nanjing" sp-id="1905" status="6"></task></tasks></body></message>';
        if($content = @simplexml_load_string($xml)) {
            echo '<pre>';
            print_r($content);
            $header=$content->header->attributes();
            foreach($content->body->tasks->task as $val){
                $attr=$val->attributes();
                print_r($attr);
            }
            //echo $header['command'];
            //echo $task['subcontent-id'];
            exit;         
        }
                
        return sfView::NONE;
    } 
    public function executeGetxml(sfWebRequest $request)
    {
        echo '<pre>';
        $ftp_dir = $this->getNewDir1('/www/newepg/tmp/epg/');
        print_r($ftp_dir);
        $programdays = $this->getProgramList('欢笑剧场');    
        
        print_r($programdays);
        return sfView::NONE;
    } 
    /**
     * 找到最新的一个目录
     * @param void $ftp_conn
     */    
    private function getNewDir1($path) {
        $ftp_files = scandir($path);
        if(count($ftp_files) > 0) {
            return $ftp_files;
        }else{
            return false;
        }
    }
    /**
     * 遍历一个xml，获取节目信息
     * @param void $ftp_conn
     */ 
    private function getProgramList($channelname) {
        global $ftp_conn,$ftp_path,$argv;
        $programs = array();
        $channelname=iconv("utf-8","gbk",$channelname);
        if(file_exists("/www/newepg/tmp/epg/".$channelname.".xml")) {
            echo '存在';
            $xml = simplexml_load_file("/www/newepg/tmp/epg/".$channelname.".xml");
            if($xml) {
                $events = $xml->SchedulerData->Channel->Event;
                foreach($events as $event) {
                    $day = date("Y-m-d",strtotime($event['begintime']));      
                    $starttime = date("Y-m-d H:i:s",strtotime($event['begintime']));
                    //$endtime = $this->getEndTimeByDuration($starttime,$event['duration']);
                    $title = (string)$event->EventText->Name;
                    $time = date("H:i",strtotime($event['begintime']));
                    $programs[$day][] = array("title" => $title,
                                        "starttime" => $starttime,
                                        "time" => $time,
                                        "endtime" => $endtime);
                }
            }
            return $programs;
        }
    }
    //敏感词日志测试
    public function executeWordsTest(sfWebRequest $request){
        
        $id = $request -> getParameter('id');
        $mongo = $this->getMondongo();
        $wiki_res = $mongo->getRepository("wiki"); 
        $wikiinfo = $wiki_res -> findOneById(new MongoId($id));
        if($wikiinfo){
            $patterns = $this -> getSensitiveWords();
            echo '<pre>';
            $wikititle = preg_replace($patterns, "*", $wikiinfo['title']);
            $content = preg_replace($patterns, "*", $wikiinfo['content']);
            //敏感词日志记录
            if($wikititle!=$wikiinfo['title']){
                /*
                $words=new WordsLog();
                $words->setWord($wikiinfo['title']);
                $words->setReword($wikititle);
                $words->setFrom('wiki');
                $words->save();
                */
                echo "在标题中有敏感词<br/>";
            }
            if($content!=$wikiinfo['content']){
                /*
                $words=new WordsLog();
                $words->setWord($wikiinfo['content']);
                $words->setReword($content);
                $words->setFrom('wiki');
                $words->save();
                */
                echo "在内容中有敏感词<br/>";
            }
        }
        echo "完成<br/>";
        return sfView::NONE;
    } 
    private function getSensitiveWords(){
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('words');
        $words_res = $repository->find();
        $arr=array();
        foreach($words_res as $rs){
            $arr[] = $rs->getWord();
        }
        $words=implode(',',$arr);
        return Common::getSensitiveWords($words);
    }
    //敏感词日志测试
    public function executeGetWikiByTitle(sfWebRequest $request){
        
        $title = $request -> getParameter('title');
        $mongo = $this->getMondongo();
        $wiki_res = $mongo->getRepository("wiki"); 
        $wikiinfo = $wiki_res -> getWikiByTitle($title);
        echo '<pre>';
        print_r($wikiinfo);
        echo count($wikiinfo),'<br/>';
        echo "完成<br/>";
        return sfView::NONE;
    } 
    //敏感词日志测试
    public function executeAddWords(sfWebRequest $request){
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Setting');
        $query = array('query' => array( "key" => 'sensitiveWords' ));
        $rs = $repository->findOne($query);
        if($rs){
            $value=$rs->getValue();
            $arr = explode(',',$value);
            foreach($arr as $rsa){
        		$word = new Words();
        		$word -> setWord($rsa);
        		$word -> save();
            }
        }
        echo "完成<br/>";
        return sfView::NONE;
    } 
}
