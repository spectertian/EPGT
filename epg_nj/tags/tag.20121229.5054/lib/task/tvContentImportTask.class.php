<?php
/**
 *  @todo  : 将content_inject中的信息导入content_import表，匹配上wiki的同时导入video表和video_playlist表，更新wiki的has_video值
 *  @author: lifucang
 */
class tvContentImportTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('state', null, sfCommandOption::PARAMETER_OPTIONAL, 'state'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'ContentImport';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:ContentImport|INFO] task does things.
Call it with:

[php symfony tv:ContentImport|INFO]
EOF;

        $this->acceptTypes = array("program","series","column");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $mystate=$options['state']?intval($options['state']):0;
        $mongo = $this->getMondongo();
        $inject_repo = $mongo->getRepository("ContentInject"); 
        $import_repo = $mongo->getRepository("ContentImport");      
		$wiki_repository = $mongo->getRepository('wiki');
        $i = 0;
        
        $del_num=0;
        $update_num=0;
        $add_num=0;
        $error_num=0;
        $type_num=0;
        $execute_num=0;
        $showType_num=0;
        $video_num=0;
        $teleplay_num=0;
        $movie_num=0;
        $inject_count = $inject_repo->count(array("state"=>$mystate));
        echo $inject_count,"\n"; 
        while ($i < $inject_count) 
        {
            $injects = $inject_repo->find(array("query"=>array("state"=>$mystate),"limit" => 100));
            
            if(!$injects){  
                echo "finished!";                       
            }else{
                foreach($injects as $inject) {
                    $execute_num++;
                    if($content = @simplexml_load_string(trim($inject->getContent()))) {
                    
                        $adi_md = $this->getMetadata($content->Metadata);
                        $asset_md = $this->getMetadata($content->Asset->Metadata);
                        //先给cms反馈
                        //$backxmlstring = $this->getBackXmlString($adi_md['Asset_ID'], $ContentImport->getID(), $asset_md['Show_Type'], 1, ''); 
                        $backxmlstring = $this->getBackXmlString($adi_md['Asset_ID'], $adi_md['Asset_ID'], $asset_md['Show_Type'], 0, 'ok'); 
                        $this->postCallBack($backxmlstring);
                        
                        if(!isset($asset_md['Show_Type'])) {
                            $inject->setState(-1);
                            $inject->save();
                            $showType_num++; 
                            continue;
                        }
                        if(in_array($asset_md['Show_Type'],$this->acceptTypes)) {             
                            //先把之前的电视剧过滤掉，等正式以后这段程序可不用开始
                            /*
                            if(preg_match("/第.*集/",$adi_md['Asset_Name'])){
                                $inject->setState(-1);
                                $inject->save();
                                $teleplay_num++;
                                continue;
                            }
                            */
                            //先把之前的电视剧过滤掉，等正式以后这段程序可不用结束
                            //没有movie信息的过滤掉--开始
                            if(!isset($content->Asset->Asset)){
                                $inject->setState(-1);
                                $inject->save();
                                $movie_num++;
                                continue;
                            }else{
                                if($content->Asset->Asset->Metadata->AMS->attributes()->Asset_Class[0]!='movie'){
                                    $inject->setState(-1);
                                    $inject->save();
                                    $movie_num++;
                                    continue;  
                                }
                            }
                            //没有movie信息的过滤掉--结束
                        	$ContentImport = $import_repo->findOne(array("query"=>array("from_id"=>$adi_md['Asset_ID'],"from"=>$inject->getFrom())));
                        	if ($ContentImport){
                        	    if(strtolower($adi_md['Verb'])=='delete'){
                        	        $ContentImport -> delete(); 
                                    echo iconv("utf-8","gbk",$adi_md['Asset_ID'].'删除'),"\n"; 
                                    $del_num++;
                        	    }else{
        	                        $ContentImport -> setInjectId($inject->getId());
        	                        //$ContentImport -> setFrom($inject->getFrom());
        	                        //$ContentImport -> setFromId($adi_md['Asset_ID']);
        	                        $ContentImport -> setFromTitle($adi_md['Asset_Name']);
        	                        $ContentImport -> setProviderId($adi_md['Provider_ID']);
        	                        $ContentImport -> setFromType($asset_md['Show_Type']);
        	                        //$ContentImport -> setState(0);
                                    //如果是电视剧写children_id
                                    $videos=$this->getVideos($content->Asset->Asset);
                                    $arr_video=array();
                                    if($asset_md['Show_Type']=='series'){
                                        foreach($videos as $video){
                                            $arr_video[]=$video['Asset_ID'];
                                        }
                                        $ContentImport ->setChildrenId($arr_video);
                                    }else{ //电影
                                        $arr_video[]=$videos[0]['Asset_ID']?$videos[0]['Asset_ID']:null;
                                        $ContentImport ->setChildrenId($arr_video);
                                    }
        	                        $ContentImport -> save(); 
                                    echo iconv("utf-8","gbk",$adi_md['Asset_ID'].'更新'),"\n"; 
                                    $update_num++;
                                    
                                    $inject->setState(2); //2是更新的
                                    $inject->save(); 
                        	    }
                        	}else {
    	                    	$ContentImport = new ContentImport();
    	                        $ContentImport -> setInjectId($inject->getId());
    	                        $ContentImport -> setFrom($inject->getFrom());
    	                        $ContentImport -> setFromId($adi_md['Asset_ID']);
    	                        $ContentImport -> setFromTitle($adi_md['Asset_Name']);
    	                        $ContentImport -> setProviderId($adi_md['Provider_ID']);
    	                        $ContentImport -> setFromType($asset_md['Show_Type']);
    	                        $ContentImport -> setState(0);
                                //如果是电视剧写children_id
                                $videos=$this->getVideos($content->Asset->Asset);
                                $arr_video=array();
                                if($asset_md['Show_Type']=='series'){
                                    foreach($videos as $video){
                                        $arr_video[]=$video['Asset_ID'];
                                    }
                                    $ContentImport ->setChildrenId($arr_video);
                                }else{ //电影
                                    $arr_video[]=$videos[0]['Asset_ID']?$videos[0]['Asset_ID']:null;
                                    $ContentImport ->setChildrenId($arr_video);
                                }
                                //关联wiki并写入video表
    	                        $title = $this->getSubTitle($adi_md['Asset_Name']);
    	                        $wiki = $wiki_repository->getWikiByTitle($title);
    	                        if($wiki){
    	                            $ContentImport->setWikiId((string)$wiki->getId());
    	                            //写入video表
                                    $video_ok=$this->videoSave($wiki,$adi_md,$asset_md,$videos);
                                    if($video_ok){
                                        $video_num++;
                                    }
                                    $inject->setState(3); //3是有wiki的
                                    $inject->save(); 
    	                        }else{
                                    $inject->setState(1);
                                    $inject->save(); 
    	                        }           
    	                        $ContentImport -> save();
                                echo iconv("utf-8","gbk",$adi_md['Asset_ID'].'保存'),"\n"; 
                                $add_num++;
                        	}
                            

                            //更新inject状态
                            //$inject->setState(1);
                            //$inject->save(); 
                        }else {
                            $type_num++;
                            $inject->setState(-1);
                            $inject->save();
                        }                    
                    }else{
                        $error_num++;
                        $inject->setState(-1);
                        $inject->save();
                    }
                }
            }
            
            $i = $i + 100;
            echo $i,'*************************************',"\n"; 
            sleep(1);  
        } 
        echo iconv("utf-8","gbk","总数:$inject_count | 未成功解析xml内容数:$error_num | 未设置showType：$showType_num | 未知类型:$type_num | 无movie数:$movie_num | 删除数：$del_num | 更新数:$update_num | 新增数:$add_num | 新增video数:$video_num | 电视剧数:$teleplay_num"),"\n";      
    }  

    private function videoSave($wiki,$adi_md,$asset_md,$videos) {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        $wikiMetaRepos = $mongo->getRepository('wikiMeta');
        $title=$wiki->getTitle();
        if($wiki->getModel()=='film'){ //电影
            $url=$this->getVideoUrl($videos[0]['Asset_ID'],$adi_md['Provider_ID']);
            $config=array("asset_id"=>$videos[0]['Asset_ID']);
            //先查询是否重复,有则只更新
            $query=array('referer'=>$adi_md['Provider_ID'],'config.asset_id'=>$videos[0]['Asset_ID']);
            $video=$video_repo->findOne(array('query'=>$query));
            if(!$video){
                $video = new Video();
                $wiki->setHasVideo(true);
                $wiki->save();  
            } 
            $video->setWikiId((string)$wiki->getId());
            $video->setModel($wiki->getModel());
            $video->setTitle($videos[0]['Asset_Name']);
            $video->setUrl($url);
            $video->setConfig($config);
            $video->setReferer($adi_md['Provider_ID']);
            $video->setPublish(true);
            //$video->setVideoPlaylistId($videoPlaylistId);
            $video->setTime($videos[0]['Run_Time']);
            //$video->setMark(1);
            $video->save();   
        }else{  //电视剧，栏目等
            //写入videoPlayList表,先查询是否重复
            $query=array('referer'=>$adi_md['Provider_ID'],'wiki_id'=>(string)$wiki->getId());
            $VideoPlaylist=$videoplaylist_repo->findOne(array('query'=>$query));
            if(!$VideoPlaylist){
                $VideoPlaylist = new VideoPlaylist();
                $VideoPlaylist->setTitle($title);
                //$VideoPlaylist->setUrl('');
                $VideoPlaylist->setReferer($adi_md['Provider_ID']);
                $VideoPlaylist->setWikiId((string)$wiki->getId());
                $VideoPlaylist->save();
            }
            //继续写入video表
            $videoPlaylistId=(string)$VideoPlaylist->getId();
            foreach($videos as $video_md){
                $url=$this->getVideoUrl($video_md['Asset_ID'],$video_md['Provider_ID']);
                $config=array("asset_id"=>$video_md['Asset_ID']);
                //匹配集数
                if($video_md['Chapter']){
                    $mark=$video_md['Chapter'];
                }else{
                    preg_match ("/\d+/", $video_md['Asset_Name'], $marks);
                    $mark=$marks[0];
                }
                //先查询video是否重复
                $query=array('referer'=>$video_md['Provider_ID'],'config.asset_id'=>$video_md['Asset_ID']);
                $video=$video_repo->findOne(array('query'=>$query));
                if(!$video){
                    $video = new Video();
                    $wiki->setHasVideo(true);
                    $wiki->save();
                }
                $video->setWikiId((string)$wiki->getId());
                $video->setModel($wiki->getModel());
                $video->setTitle($video_md['Asset_Name']);
                $video->setUrl($url);
                $video->setConfig($config);
                $video->setReferer($video_md['Provider_ID']);
                $video->setPublish(true);
                $video->setVideoPlaylistId($videoPlaylistId);
                $video->setTime($video_md['Run_Time']);
                $video->setMark($mark);
                $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => (int) $mark)));
                if ($wikiMeta) {
                    $video->setWikiMataId((string) $wikiMeta->getId());
                }
                $video->save();    
            }
        }
        return true;
    }
    private function getVideoUrl($asset_id,$provider_id) {
        $urlvod = "http://172.31.155.22:9080/core/ContentLinksQuery.do?spcode=SP_BOSS&assetid=$asset_id&movieassetid=$asset_id&usercode=11111&stbno=10000";
        $urlpptv = "http://172.31.155.22:9080/core/ContentLinksQuery.do?spcode=SP1N02A08_003&assetid=$asset_id&usercode=11111&stbno=10000&movieassetid=$asset_id";
        $url1905="http://172.31.155.22:9080/core/ContentLinksQuery.do?spcode=SP1N02M04_030&assetid=$asset_id&usercode=11111&stbno=10000&movieassetid=$asset_id";
        switch($provider_id){
            case 'yang.com':
                $url=$urlvod;
                break;
            case '2A08_003':
            case 'CP1N02A08_003':
                $url=$urlpptv;
                break;
            case '1905yy00':
                $url=$url1905;
                break;
            default:
                $url='';
                return '';
        }
        $data=Common::get_url_content($url);
        if($data){
    		$result = json_decode($data,true);
            return $result['BackURL'];  
        }else{
            return '';
        }
    }     
    //获取电视剧或电影movie信息
    private function getVideos($Metadata) {
        $p = array();
        if(isset($Metadata)){
            foreach($Metadata as $val) {
                $movies=$this->getMetadata($val->Metadata);
                if($movies['Asset_Class']=='movie'){ //只获取有movie的
                    $p[]=  $movies;
                }
            }  
        }   
        return $p;
    }
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

    private function getAttrs($s) {
        $arr=array();
        if(isset($s)){
            foreach($s->attributes() as $key => $val) {
               $arr[$key] = (string)$val;
            }  
        }
        return $arr;
    } 

    private function postCallBack($data) {
        $opts = array('http'=>array('method'=>"POST",
                                    'header'=>"Content-Type:text/html\r\n",
                                    'content'=> $data));
        //@url = "http://10.20.20.209/inject";
        $url = "http://172.31.183.8:8080/icms/content?action=adi1synccallback";
        return @file_get_contents($url, false, stream_context_create($opts));
    }
   
    private function getBackXmlString($asset_id, $import_id, $type, $status, $desc = '') {
        $xml = "<?xml version = \"1.0\" encoding=\"utf-8\"?>\n";
        $xml .= "\t<SyncContentsResult Time_Stamp=\"".date("Y-m-d H:i:s")."\"  System_ID=\"znepg\">\n"; 
        $xml .= "\t<Asset ID=\"".$asset_id."\"  Current_ID=\"".$import_id."\" Type=\"".$type."\"  Status=\"".$status."\" Desc=\"".$desc."\"></Asset>\n";
        $xml .= "</SyncContentsResult>\n";
        return $xml;
    }
    
    /**
     * 对节目名称进行过滤
     * @param void $ftp_conn
     */ 
    private function getSubTitle($str){
        //替换
        $patterns = array('/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/剧场/',
                          '/第.*集/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/-.*/');
        $str = preg_replace($patterns, "", $str);
        //替换
        $patterns = array('/法治中国/','/视野/','/爱探险的朵拉/',
                          '/欧美流行.*/','/舌尖上的中国.*/');
        $repatt = array('法治中国（江苏）','视野（辽宁）','爱探险的Dora',
                        '欧美流行','舌尖上的中国');
        $str = preg_replace($patterns, $repatt, $str);
        return $str;
    }    
}
