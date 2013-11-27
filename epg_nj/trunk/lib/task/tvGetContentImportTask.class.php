<?php
/**
 *  @todo  : 从huan.tv获取Import
 *  @author: lifucang 2013-05-27
 */
class tvGetContentImportTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('startTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'startTime'),
      new sfCommandOption('endTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'endTime'),
      new sfCommandOption('wikiIdBefore', null, sfCommandOption::PARAMETER_OPTIONAL, 'wikiIdBefore'),
      new sfCommandOption('wikiIdAfter', null, sfCommandOption::PARAMETER_OPTIONAL, 'wikiIdAfter'),
      new sfCommandOption('videoUpdate', null, sfCommandOption::PARAMETER_OPTIONAL, 'videoUpdate'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'GetContentImport';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:GetContentImport|INFO] task does things.
Call it with:

  [php symfony tv:GetContentImport|INFO]
EOF;
    //php symfony tv:GetContentImport --wikiIdBefore=4d0083092f2a241bd7008d01 --wikiIdAfter=4e312cbcedcd887c4c0003a1    根据wiki_id更改单条或多条（电视剧）video匹配错误记录
    //php symfony tv:GetContentImport --videoUpdate=1           根据content_import的wiki_id更改video所有匹配错误的记录
    //php symfony tv:GetContentImport           正常从huan.tv接收content_import信息
  }

  protected function execute($arguments = array(), $options = array())
  {
        //如果设置了wiki_id，则只更换video的信息
        if(isset($options['wikiIdBefore'])&&isset($options['wikiIdAfter'])){
            $wikiIdBefore=$options['wikiIdBefore'];
            $wikiIdAfter=$options['wikiIdAfter'];
            $this->videoUpdate($wikiIdBefore,$wikiIdAfter);
            echo iconv('utf-8','gbk',"video关联的wiki_id已更换\n");
            exit;
        }
        //如果设置了videoUpdate，则更新video表所有记录，保持和content_import的wiki_id一致
        if(isset($options['videoUpdate'])){
            $mongo = $this->getMondongo();
            $video_repo = $mongo->getRepository("Video"); 
            $import_repo = $mongo->getRepository("ContentImport");

            $video_count = $video_repo->count();
            echo "count:",$video_count,"\n";
            sleep(1);
            $i = 0;
            $limit=50;
            while ($i < $video_count) {
                $videos=$video_repo->find(array("sort" => array("_id" => 1), "skip" => $i, "limit" => $limit));
                foreach($videos as $video){
                    $wikiIdBefore=$video->getWikiId();
                    
                    $config=$video->getConfig();
                    $asset_id=$config['asset_id'];
                    $fromId=$config['from_id'];
                    if($fromId){
                        $query=array('from_id'=>$fromId);
                    }else{
                        $query=array('children_id.ID'=>$asset_id);
                    }
                    
                    $importImport = $import_repo->findOne(array('query'=>$query,'sort'=>array('_id'=>-1)));
                    if($importImport){
                        $wikiIdAfter=$importImport->getWikiId();
                    }
                    
                    if($wikiIdAfter&&$wikiIdAfter!=$wikiIdBefore){
                        $this->videoUpdate($wikiIdBefore,$wikiIdAfter);
                        echo (string)$video->getId(),iconv('utf-8','gbk',$video->getTitle()),'|',$wikiIdBefore,'|',$wikiIdAfter,"\n";
                    }
                }
                $i = $i + $limit;
                echo $i,'*************************************',"\n";
                sleep(1);
            }

            echo iconv('utf-8','gbk',"所有video关联的wiki_id已更换\n");
            exit;
        }
        //从huan.tv获取Import，正常情况下执行以下程序
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('GetContentImport');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $url = sfConfig::get('app_epghuan_url');
        $apikey = sfConfig::get('app_epghuan_apikey');
        $secretkey = sfConfig::get('app_epghuan_secretkey');
        
        $startTime=$options['startTime']?$options['startTime']:date("Y-m-d 00:00:00");
        $endTime=$options['endTime']?$options['endTime']:date("Y-m-d 23:59:59");
        
        $json_post='{"action":"GetContentImport","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'"}}';        
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $imports=$result['imports']?$result['imports']:array();   
        echo "count:",count($imports),"\n";
        sleep(1);
        $updateNum=0;
        $importUpdate='';
        foreach($imports as $import){
               $state_match=isset($import['state_match'])?$import['state_match']:1;
               $importLocal = $mongo->getRepository("ContentImport")->findOneById(new MongoId($import['id']));
               if($importLocal){
                    $wikiIdBefore=$importLocal->getWikiId();
                    $importLocal -> setWikiId($import['wiki_id']);
                    $importLocal -> setStateMatch($state_match);  //人工匹配
                    $importLocal -> save();
                    //判断两个wiki_id是否相同，不同则更新video的相应wiki关联
                    if($wikiIdBefore&&$wikiIdBefore!=''&&$wikiIdBefore!=$import['wiki_id']){
                        $this->videoUpdate($wikiIdBefore,$import['wiki_id']);
                        $updateNum++;
                        $importUpdate.=$importLocal->getFromTitle().','.$wikiIdBefore.','.$import['wiki_id'].'|'; //记录更新的content_import的ID
                    }
               }
        }    
        echo date("Y-m-d H:i:s"),"------finished!\r\n";  
        $content="num:".count($imports).'---update:'.$updateNum.'('.$importUpdate.')';
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
  }
  
    private function videoUpdate($wikiIdBefore,$wikiIdAfter)
    {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        $wikiRes = $mongo->getRepository('wiki');
        //更新video
        $videos=$video_repo->find(array('query'=>array('wiki_id'=>$wikiIdBefore)));
        if($videos){
            foreach($videos as $video){
                $video->setWikiId($wikiIdAfter);
                $video->save(); //保存前已自动把相关wiki的has_video设置了，所以不用设置$wikiAfter的has_video值
            }
        }
        //更新videoPlayList
        $videoPlayLists=$videoplaylist_repo->find(array('query'=>array('wiki_id'=>$wikiIdBefore)));
        if($videoPlayLists){
            foreach($videoPlayLists as $videoPlayList){
                $videoPlayList->setWikiId($wikiIdAfter);
                $videoPlayList->save();
            }
        }
        //把之前的wiki的has_video值设为0并更新迅搜
        $wikiBefore = $wikiRes->findOneById(new MongoId($wikiIdBefore));
        if($wikiBefore){
            $wikiBefore->setHasVideo(0);
            $wikiBefore->save();
            //$wikiBefore->updateXunSearchDocument();
        }
        //更新迅搜
        /*
        $wikiAfter = $wikiRes->findOneById(new MongoId($wikiIdAfter));
        if($wikiAfter){
            $wikiAfter->updateXunSearchDocument();
        }
        */
    }
}
