<?php
/**
 *  @todo  : 根据content_cdi中的上下线信息更新video数据
 *  @author: lifucang 
 *  @update: 2013-9-17
 */
class tvCdiToVideoTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('id', null, sfCommandOption::PARAMETER_REQUIRED, 'id'),
      new sfCommandOption('idstart', null, sfCommandOption::PARAMETER_REQUIRED, 'idstart'),
      new sfCommandOption('idend', null, sfCommandOption::PARAMETER_REQUIRED, 'idend'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'CdiToVideo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:CdiToVideo|INFO] task does things.
Call it with:

  [php symfony tv:CdiToVideo|INFO]
EOF;
  //symfony tv:CdiToVideo --id=51bffdd67b5fbd54120000b4      单独重发某一个上线或下线消息，id为content_cdi的ID
  //symfony tv:CdiToVideo --idstart=51bffdd67b5fbd54120000b4 --idend=51bffdd67b5fbd54120000b4    重发某id到某id的上下线信息，不要超过200条
  }
    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('CdiToVideo');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        
        $id = $options['id'];
        $idstart = $options['idstart'];
        $idend = $options['idend'];
        if($idstart&&$idend){
            $query=array("_id"=>array('$gte'=>new MongoId($idstart),'$lte'=>new MongoId($idend)));
        }elseif($id){
            $query=array("_id"=>new MongoId($id));
        }else{
            $query=array("state"=>0);
        }
        $onlineNum=0;
        $offlineNum=0;
        
        $cdi_repo = $mongo->getRepository("ContentCdi"); 
        $importImport_repo = $mongo->getRepository("ContentImport");      
        $wiki_repository = $mongo->getRepository('wiki');
        $video_repo = $mongo->getRepository("Video"); 
        $count = $cdi_repo->count($query);
        echo $count,"\n";
        $limit = 200; 
        $i = 0;
        while ($i < $count) {
            
            $cdis = $cdi_repo->find(array("query"=>$query,"sort" => array("_id"=>1),"limit" => $limit));
            if($cdis){ 
                foreach($cdis as $cdi) {
                    $command = $cdi->getCommand();
                    $children_id = $cdi->getSubcontentId();
                    $page_id = $cdi->getPageId();

                    if($command=='ONLINE_TASK_DONE'){
                        $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id,'wiki_id'=>array('$exists'=>true,'$ne'=>'')),'sort'=>array('_id'=>-1)));
                        if($importImport){
                            $this->videoSave($importImport,$children_id,$page_id); //只更新一个task，因不会有多个task的情况
                            $importImport->setStateEdit(1);
                            $importImport->setState(1);
                            $importImport->save();
                            $onlineNum++;
                            echo iconv("utf-8","gbk",$importImport->getFromTitle()."上线"),"\n";       
                        }
                    }elseif($command=='CONTENT_OFFLINE'){
                        $this -> videoDel($children_id);
                        $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id)));
                        if($importImport){
                            $importImport->setState(0);
                            $importImport->save();
                            $offlineNum++;
                            echo iconv("utf-8","gbk",$importImport->getFromTitle()."下线"),"\n"; 
                        } 
                    }
                    $cdi ->setState(1);
                    $cdi ->save();
                }
            }
            $i = $i + $limit;
            echo $i,'*************************************',"\n"; 
            sleep(1);  
        } 
        echo '------------------------',"\n";  
        echo date('Y-m-d H:i:s'),"finished!\n";    
        echo '------------------------',"\n";   
        
        $content="online:$onlineNum---offline:".$offlineNum;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
    }
    //注：和inject模块中的函数基本一样，只是不更新迅搜
    private function videoDel($asset_id) {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video");
        $video = $video_repo->findOne(array('query'=>array('config.asset_id'=>(string)$asset_id)));
        if($video){
            $wiki_id=$video->getWikiId();
            $video -> delete();
            //更新wiki数量
            $wikiRepos = $mongo->getRepository('Wiki');
            $wiki = $wikiRepos->findOneById(new MongoId($wiki_id));
            if ($wiki) {
			    $videos = $wiki->getVideoCount();
                $wiki->setHasVideo($videos);
                $wiki->save();
            }
        }
    }
    //注：和inject模块中的函数基本一样
    private function videoSave($importTemp,$children_id,$page_id) 
    {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        $wikiRes = $mongo->getRepository('wiki');
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
            //先查询是否重复,有则只更新
            $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid);
            $video=$video_repo->findOne(array('query'=>$query));
            if(!$video){
                $video = new Video();
                $add_num++;
            }else{
                $update_num++;
                //判断wiki_id是否前后一样，不一样则把之前的wiki的has_video值置为0
                $wikiIdBefore=$video->getWikiId();
                $wikiIdAfter=$importTemp->getWikiId();
                if($wikiIdBefore!=$wikiIdAfter){
                    $wikiBefore = $wikiRes->findOneById(new MongoId($wikiIdBefore));
                    if($wikiBefore){
                        $wikiBefore->setHasVideo(0);
                        $wikiBefore->save();
                    }
                }
            }    
            /*
            $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid);
            $video_repo->remove($query);
            $video = new Video();
            */
            $video->setWikiId((string)$wiki->getId());
            $video->setModel($wiki->getModel());
            $video->setTitle($childrenids[0]['Title']);
            $video->setUrl($url);
            $video->setConfig($config);
            $video->setReferer($importTemp->getProviderId());
            $video->setPublish(true);
            $video->setPageId($page_id);
            //$video->setVideoPlaylistId($videoPlaylistId);
            //$video->setTime();
            //$video->setMark(1);
            $video->save();
        }else{  
            //电视剧，栏目等
            //写入videoPlayList表,先查询是否重复
            $fromId = $importTemp->getFromId();
            //$query = array('referer'=>$importTemp->getProviderId(),'wiki_id'=>(string)$wiki->getId());
            $query = array('referer'=>$importTemp->getProviderId(),'from_id'=>$fromId);
            $VideoPlaylist = $videoplaylist_repo->findOne(array('query'=>$query));
            if(!$VideoPlaylist){
                $VideoPlaylist = new VideoPlaylist();
                $VideoPlaylist->setTitle($title);
                //$VideoPlaylist->setUrl('');
                $VideoPlaylist->setReferer($importTemp->getProviderId());
                $VideoPlaylist->setWikiId((string)$wiki->getId());
                $VideoPlaylist->setFromId($fromId);
                $VideoPlaylist->save();
            }
            //继续写入video表
            $videoPlaylistId = (string)$VideoPlaylist->getId();
            $childrenids = $importTemp->getChildrenId();
            foreach($childrenids as $childrenid)
            {
                //只判断一个即可，目前上线的tasks里不会有多个task
                if($children_id==$childrenid['ID']){
                    $url = '';
                    $hdContent = $childrenid["HD_Content"]?$childrenid["HD_Content"]:'N';
                    $config = array("asset_id" => $childrenid['ID'],"from_id"=>$fromId,"hd_content"=>$hdContent);
                    $mark = $childrenid['Chapter'] ? $childrenid['Chapter'] : 0;
                    
                    $querya = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid['ID']);
                    //第一种，先删除，再新建（之所以不用remove的方法，因为它不会更新wiki的has_video值）
                    /*
                    $videos=$video_repo->find(array('query'=>$querya));
                    foreach($videos as $rs){
                        $rs->delete();
                    }
                    $video = new Video();
                    */
                    //第二种
                    $video=$video_repo->findOne(array('query'=>$querya));
                    if(!$video){
                        $video = new Video();
                        $add_num++;
                    }else{
                        $update_num++;
                        //判断wiki_id是否前后一样，不一样则把之前的wiki的has_video值置为0
                        $wikiIdBefore=$video->getWikiId();
                        $wikiIdAfter=$importTemp->getWikiId();
                        if($wikiIdBefore!=$wikiIdAfter){
                            $wikiBefore = $wikiRes->findOneById(new MongoId($wikiIdBefore));
                            if($wikiBefore){
                                $wikiBefore->setHasVideo(0);
                                $wikiBefore->save();
                            }
                        }
                    }
                    $video->setWikiId((string)$wiki->getId());
                    $video->setModel($wiki->getModel());
                    $video->setTitle($childrenid['Title']);
                    $video->setUrl($url);
                    $video->setConfig($config);
                    $video->setReferer($importTemp->getProviderId());
                    $video->setPublish(true);
                    $video->setVideoPlaylistId($videoPlaylistId);
                    //$video->setTime();
                    $video->setMark($mark);
                    $video->setPageId($page_id);
                    $video->save(); 
                }
            } 
        }
        $num=array('add'=>$add_num,'update'=>$update_num);
        return $num;
    }
}
