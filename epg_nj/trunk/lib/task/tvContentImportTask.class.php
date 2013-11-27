<?php
/**
 *  @todo  : 将content_inject中的信息导入content_import表
 *  @author: lifucang
 */
class tvContentImportTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('state', null, sfCommandOption::PARAMETER_OPTIONAL, 'state',0),
            new sfCommandOption('id', null, sfCommandOption::PARAMETER_OPTIONAL, 'id',null),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'ContentImport';
        $this->briefDescription = '';
        $this->detailedDescription = '';
        $this->acceptTypes = array("program","series","column");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $mystate = $options['state'] ? intval($options['state']) : 0;
        if(isset($options['id'])){
            $query=array("_id"=>new MongoId($options['id']));
        }else{
            $query=array("state"=>$mystate);
        }
        $i = $del_num = $update_num = $error_num = $add_num = 0;        
        $type_num = $execute_num = $showType_num = $video_num = 0;
        $teleplay_num = $movie_num = $wiki_num = 0;
        $execPath = sfConfig::get('app_exec_path');
        
        $mongo = $this->getMondongo();
        $inject_repo = $mongo->getRepository("ContentInject"); 
        $import_repo = $mongo->getRepository("ContentImport");      
		$wiki_repository = $mongo->getRepository('wiki');
        
        $inject_count = $inject_repo->count($query);
        echo $inject_count,"\n";
        $limit = 200; 
        while ($i < $inject_count) 
        {
            $injects = $inject_repo->find(array("query"=>$query,"sort" => array("_id"=>1),"limit" => $limit));
            if($injects){ 
                foreach($injects as $inject) {
                    $execute_num++;
                    if($content = @simplexml_load_string(trim($inject->getContent()))) {                    
                        unset($asset_md);
                        unset($adi_md);
                        $adi_md = $this->getMetadata($content->Metadata);
                        $asset_md = $this->getMetadata($content->Asset->Metadata);
                        
                        //未设置Show_Type的过滤掉
                        if(!isset($asset_md['Show_Type'])) {
                            $inject->setState(-1);  
                            $inject->save();
                            $showType_num++; 
                            continue;
                        }
                        //Show_Type类型符合的处理
                        if(in_array($asset_md['Show_Type'],$this->acceptTypes)) {             
                            switch($asset_md['Show_Type']) {
                                case "program":
                                    $model = "film";
                                    $videos = $this->getProgramVideos($content);
                                    break;
                                case "series":
                                    $model = "teleplay";
                                    $videos = $this->getSeriesVideos($content);
                                    break;
                                case "column":
                                    $model = "television";
                                    $videos = $this->getSeriesVideos($content);
                                    break;
                            }
                            //没有movie信息的过滤掉--继续处理下一个
                            if(!$videos) {
                                $inject->setState(-2); //没有video
                                $inject->save();
                                $movie_num++;
                                continue;
                            }
                            //$ContentImport = null;
                        	$ContentImportOld = $import_repo->findOne(array("query"=>array("from_id"=>$adi_md['Asset_ID'],"from"=>$inject->getFrom())));
                            $is_del=strtolower($adi_md['Verb']);
                            if ($ContentImportOld){
                                //如果是删除标记，则同时删除video信息
                                if($is_del=='delete'){
                                    $wiki_id = $ContentImportOld->getWikiId();
                                    if($wiki_id&&$wiki_id!=''){
                                        $this -> videoDelete($wiki_id);
                                        echo iconv("utf-8","gbk",$adi_md['Asset_Name'].'---'.$wiki_id."删除"),"\n";
                                        $del_num++;
                                        
                                        //更新迅搜中的单条wiki--通过inject/vod 过来的
                                        /*
                                        $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));
                                        if($wiki){
                                            $wiki->updateXunSearchDocument();
                                        }
                                        */
                                    }
                                }else{
                                    //更新开始
        	                        $ContentImportOld -> setInjectId($inject->getId());
        	                        $ContentImportOld -> setFrom($inject->getFrom());
        	                        $ContentImportOld -> setFromId($adi_md['Asset_ID']);
        	                        $ContentImportOld -> setFromTitle($adi_md['Asset_Name']);
        	                        $ContentImportOld -> setProviderId(str_replace("www.dayang.com","yang.com",$adi_md['Provider_ID']));
        	                        $ContentImportOld -> setFromType($asset_md['Show_Type']);
        	                        //$ContentImportOld -> setState(0);
                                    
                                    $arr_video = array();
                                    if($videos) {
                                        foreach($videos as $key => $video){
                                            $arr_video[$key]["ID"] = $video['Asset_ID'];
                                            $arr_video[$key]["Title"] = $video['Asset_Name'];
                                            $arr_video[$key]["Chapter"] = $video['Chapter'];
                                            $arr_video[$key]["HD_Content"] = $video['HD_Content'];
                                        }
                                        $ContentImportOld ->setChildrenId($arr_video);
                                    }
                                    $ContentImportOld -> save();
                                    //每当发送ADI数据过来的时候如果content_import有该数据，则更新video表
                                    $this->videoUpdate($ContentImportOld); 
                                    $update_num++;
                                }
                        	}else{
                        	    
    	                    	$ContentImport = new ContentImport();
    	                        $ContentImport -> setInjectId($inject->getId());
    	                        $ContentImport -> setFrom($inject->getFrom());
    	                        $ContentImport -> setFromId($adi_md['Asset_ID']);
    	                        $ContentImport -> setFromTitle($adi_md['Asset_Name']);
    	                        $ContentImport -> setProviderId(str_replace("www.dayang.com","yang.com",$adi_md['Provider_ID']));
    	                        $ContentImport -> setFromType($asset_md['Show_Type']);
    	                        $ContentImport -> setState(0);
                                
                                $arr_video = array();
                                if($videos) {
                                    foreach($videos as $key => $video){
                                        $arr_video[$key]["ID"] = $video['Asset_ID'];
                                        $arr_video[$key]["Title"] = $video['Asset_Name'];
                                        $arr_video[$key]["Chapter"] = $video['Chapter'];
                                        $arr_video[$key]["HD_Content"] = $video['HD_Content'];
                                    }
                                    $ContentImport ->setChildrenId($arr_video);
                                }
                                $title = $this->getSubTitle($adi_md['Asset_Name']);
    	                        $wikiId = $wiki_repository->getWikiIdByTitle($title,$model);
                                //$wiki = $wiki_repository->findOne(array("query"=>array("title"=>$title,"model"=>$model)));
    	                        if($wikiId){
                                    $ContentImport -> setWikiId($wikiId);
                                    $ContentImport -> setStateMatch(-1); //自动匹配
                                    $wiki_num++;
                                }
                                $ContentImport -> save();
                                $add_num++;
                                //echo iconv("utf-8","gbk",$adi_md['Asset_ID'].'保存'),"\n"; 
                        	}
                            //更新inject状态
                            $inject->setState(1);
                            $inject->save(); 
                        }else {
                            $type_num++;
                            $inject->setState(-3); //未知类型
                            $inject->save();
                        }                    
                    }else{
                        $error_num++;
                        $inject->setState(-4);  //未成功解析
                        $inject->save();
                    }
                }
            }
            
            $i = $i + $limit;
            echo $i,'*************************************',"\n"; 
            sleep(1);  
        } 
        $add_num1 = $add_num-$update_num;
        echo iconv("utf-8","gbk","总数:$inject_count | 未成功解析xml内容数:$error_num | 未设置showType：$showType_num | 未知类型:$type_num | 无movie数:$movie_num | 新增数：$add_num | 删除数：$del_num | 更新数:$update_num | 匹配wiki数:$wiki_num"),"\n";      
    }  
    
    //根据wiki_id删除视频
    private function videoDelete($wiki_id) {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video");  
        $videoPlaylist_repo = $mongo->getRepository("VideoPlaylist");  
        $videos = $video_repo->find(array('query'=>array('wiki_id'=>$wiki_id)));
        if($videos){
            foreach($videos as $video){
                $video -> delete();
            }  
        }
        $videoPlaylist = $videoPlaylist_repo->findOne(array('query'=>array('wiki_id'=>$wiki_id)));
        if($videoPlaylist)
            $videoPlaylist -> delete();
        return true;
    }
    //根据content_import对象更新video表
    private function videoUpdate(&$importTemp) 
    {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        $wikiRes = $mongo->getRepository('wiki');
        if(!$importTemp->getWikiId()||$importTemp->getWikiId()=='') return false;
        $wiki = $wikiRes->findOneById(new MongoId($importTemp->getWikiId()));
        if(!$wiki) return false;
        $title = $wiki->getTitle();
        $add_num=0;
        $update_num=0;
        if($wiki->getModel()=='film'){ 
            //电影
            $childrenids = $importTemp->getChildrenId();
            $childrenid = $childrenids[0]["ID"];
            $hdContent = $childrenids[0]["HD_Content"]?$childrenids[0]["HD_Content"]:'N';
            $fromId = $importTemp->getFromId();
            $url = ""; 
            $config = array("asset_id" => $childrenid,"from_id"=>$fromId,"hd_content"=>$hdContent);
            //先查询是否存在,有则只更新
            $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid);
            $video=$video_repo->findOne(array('query'=>$query));
            if($video){
                $video->setWikiId((string)$wiki->getId());
                $video->setModel($wiki->getModel());
                $video->setTitle($childrenids[0]['Title']);
                $video->setUrl($url);
                $video->setConfig($config);
                $video->setReferer($importTemp->getProviderId());
                $video->setPublish(true);
                //$video->setVideoPlaylistId($videoPlaylistId);
                //$video->setTime();
                //$video->setMark(1);
                $video->save();
            }    
        }else{  
            //电视剧，栏目等
            //写入videoPlayList表,先查询是否存在
            $fromId = $importTemp->getFromId();
            //$query = array('referer'=>$importTemp->getProviderId(),'wiki_id'=>(string)$wiki->getId());
            $query = array('referer'=>$importTemp->getProviderId(),'from_id'=>$fromId);
            $VideoPlaylist = $videoplaylist_repo->findOne(array('query'=>$query));
            //继续更新video表
            if($VideoPlaylist){
                $videoPlaylistId = (string)$VideoPlaylist->getId();
                $childrenids = $importTemp->getChildrenId();
                foreach($childrenids as $childrenid){
                    $url = '';
                    $hdContent = $childrenid["HD_Content"]?$childrenid["HD_Content"]:'N';
                    $config = array("asset_id" => $childrenid['ID'],"from_id"=>$fromId,"hd_content"=>$hdContent);
                    $mark = $childrenid['Chapter'] ? $childrenid['Chapter'] : 0;
                    
                    $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid['ID']);
                    $video=$video_repo->findOne(array('query'=>$query));
                    if($video){
                        $update_num++;
                        $video->setWikiId((string)$wiki->getId());
                        $video->setModel($wiki->getModel());
                        $video->setTitle($childrenid['Title']);
                        $video->setUrl($url);
                        $video->setConfig($config);
                        $video->setReferer($importTemp->getProviderId());
                        $video->setPublish(true);
                        $video->setVideoPlaylistId($videoPlaylistId);
                        $video->setMark($mark);
                        $video->save(); 
                    }
                } 
            }
        }
        $num=array('add'=>$add_num,'update'=>$update_num);
        return $num;
    }    
    
    //获取电影movie信息
    private function getProgramVideos($xml_strs) {
        $p = array();   
        $asset = $xml_strs->Asset->Asset;
        $adist_md = $this->getMetadata($xml_strs->Asset->Metadata);
        for($i = 0; $i < $asset->count(); $i ++) {
            $meta = $this->getMetadata($asset[$i]->Metadata);
            if($meta['Asset_Class'] == "movie"&&$meta['Screen_Format'] == 1){
                $meta['Chapter'] = $adist_md['Chapter'] ? $adist_md['Chapter'] : 0;
                //$p[] = $meta;
                $p[]=array(
                    'Asset_ID' => $meta['Asset_ID'],
                    'Asset_Name' => $meta['Asset_Name'],
                    'Chapter' => $meta['Chapter'],
                    'HD_Content' => $meta['HD_Content']
                );
            }
        }    
        return $p;
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
    
    //获取Meta信息
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
    //将属性写到数组中
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
    //获取xml某一元素的属性
    private function getAttrs($s) {
        $arr=array();
        if(isset($s)){
            foreach($s->attributes() as $key => $val) {
               $arr[$key] = (string)$val;
            }  
        }
        return $arr;
    } 
    
    //对节目名称进行过滤
    private function getSubTitle($str){
        $patterns = array('/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/','/HD/','/_out/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/剧场/',
                          '/第.*集/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/-.*/');
        $str = preg_replace($patterns, "", $str);
        //$patterns = array('/法治中国/','/视野/','/爱探险的朵拉/','/欧美流行.*/','/舌尖上的中国.*/');
        //$repatt = array('法治中国（江苏）','视野（辽宁）','爱探险的Dora','欧美流行','舌尖上的中国');
        //$str = preg_replace($patterns, $repatt, $str);
        return $str;
    }    
}
