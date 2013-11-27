<?php
/**
 *  @todo  : 定期要做的工作
 *  @author: lifucang  2013-09-24
 *  @example: php symfony tv:Do
 */
class tvDoTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));
        
        $this->namespace        = 'tv';
        $this->name             = 'Do';
        $this->briefDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        echo '----------',date("Y-m-d H:i:s"),"\n";
        //设置wiki的has_video值和video一致
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');
        $wiki_repo = $mongo->getRepository('Wiki');
        $wikiNum = 0;
        $querya = array('has_video'=>array('$gt'=>0));
        $wikis=$wiki_repo->find(array('query'=>$querya));
        foreach($wikis as $wiki){
            $wiki_id=(string)$wiki->getId();
            $has_video=$wiki->getHasVideo();
            $videoCount=$video_repo->count(array('wiki_id'=>$wiki_id));
            if($videoCount!=$has_video){
                $wiki->setHasVideo($videoCount);
                $wiki->save();
                $wikiNum++;
            }
        }
        echo 'wiki has_video Num:',$wikiNum,"\n";
        sleep(1);
        
        //检查video中有数据，但wiki的has_video值没数据的
        $videoCounts=$video_repo->count();
        $wikiIds=array();
        $videoId = '519b02c67b5fbd8f2000010b';
        $i=0;
        $videoNum = 0;
        while ($i < $videoCounts) 
        {
            $videos=$video_repo->find(array("_id"=>array('$gte'=>$videoId),"sort" => array("_id" => 1), "limit" => 200));
            foreach($videos as $video){
                $wikiIds[]=(string)$video->getWikiId();
                $videoId = (string)$video->getId();
            }
            $i = $i + 200;
        }        
        $wikiIds=array_unique($wikiIds);
        foreach($wikiIds as $wikiId){
            if($wikiId){
                $wiki=$wiki_repo->findOneById(new MongoId($wikiId));
                $has_video=$wiki->getHasVideo();
                if($has_video==0||!$has_video){
                    $videoCount=$video_repo->count(array('wiki_id'=>$wikiId));
                    $wiki->setHasVideo($videoCount);
                    $wiki->save();
                    $videoNum++;
                }
            }
        }
        echo 'video Num:',$videoNum,"\n";
        sleep(1);
        
        //检查videoPlayList有但是video没有相关视频数据的
        $videoPlayListNum = 0;
        $videoPlayList_repo = $mongo->getRepository('VideoPlayList');
        $videoPlayLists=$videoPlayList_repo->find();
        foreach($videoPlayLists as $videoPlayList){
            $id=(string)$videoPlayList->getId();
            $videoCount=$video_repo->count(array('video_playlist_id'=>$id));
            if($videoCount==0){
                $videoPlayList->delete();
                $videoPlayListNum++;
            }
        }
        echo 'videoPlayList Num:',$videoPlayListNum,"\n";
        sleep(1);
        
        //删除123ftp上的老的数据
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
        $ftp -> connect($config);
        $ftp -> delete_dir('./adi/'.$delDate);
        $ftp -> delete_dir('./json/'.$delDate);
        $ftp -> delete_dir('./xml/'.$delDate);
        $ftp -> close();
        echo $ftpIp," del date:",$delDate,"\n";
        sleep(1);
        
        //删除用户行为数据
        $ftpIp = sfConfig::get('app_DataWarehouse_ip');
        $ftpUser = sfConfig::get('app_DataWarehouse_username');
        $ftpPass = sfConfig::get('app_DataWarehouse_password');
        
        $config = array(
        			'hostname' => $ftpIp,
        			'username' => $ftpUser,
        			'password' => $ftpPass,
        			'port' => 21
        				);
        $delDate = date("Ymd",strtotime("-3 days"));
        $ftp = new Ftp();
        $ftp -> connect($config);
        $ftp -> delete_file('./CHECK_STATUS_'.$delDate.'.txt');
        $ftp -> delete_file('./STRD_VOD_'.$delDate.'.csv');
        $ftp -> delete_file('./STRD_TV_'.$delDate.'.csv');
        $ftp -> close();
        echo $ftpIp," del date:",$delDate,"\n";
        sleep(1);
        
        //删除维基
        $url=sfConfig::get('app_epghuan_url');
        $apikey = sfConfig::get('app_epghuan_apikey');
        $secretkey = sfConfig::get('app_epghuan_secretkey');
        $json_post='{"action":"GetWikisDel","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{}}';
        $getinfo = Common::post_json($url,$json_post);
        $result = json_decode($getinfo,true); 
        $wikiIds=$result['wikiIds']?$result['wikiIds']:null;      
        if($wikiIds){
            foreach($wikiIds as $wikiId){
                //删除视频
                $video_repo->remove(array('wiki_id'=>$wikiId));
                //删除playList
                $videoPlayList_repo->remove(array('wiki_id'=>$wikiId));
                //删除维基
                $wiki=$wiki_repo->findOneById(new MongoId($wikiId));
                if($wiki){
                    $wiki->delete();
                }
            }
            echo implode(',',$wikiIds)," is Delete\n";
        }else{
            echo "No Wiki is Delete\n";
        }
        sleep(1); 
    }
}
