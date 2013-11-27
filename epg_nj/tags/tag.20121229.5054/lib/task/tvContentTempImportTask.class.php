<?php
/**
 *  @todo  : 将content_temp中的信息导入video表和video_playlist表，同时更新wiki的has_video值
 *  @author: lifucang
 */
class tvContentTempImportTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'ContentTempImport';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ContentTempImport|INFO] task does things.
Call it with:

  [php symfony tv:ContentTempImport|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $importTemp_repo = $mongo->getRepository("ContentTemp");   
        $importTemps=$importTemp_repo->find(array('query'=>array('provider_id'=>array('$ne'=>'dfwx00'),'state'=>0,'wiki_id'=>array('$exists'=>true))));
        $video_num=0;
        foreach($importTemps as $importTemp){
            $video_ok=$this->videoSave($importTemp);
            if($video_ok){
                $importTemp->setState(1);
                $importTemp->save();
                $video_num++;
                echo iconv('utf-8','gbk',$importTemp->getFromTitle()),'------',$importTemp->getWikiId(),"\r\n";  
            }else{
                $importTemp->setState(-1);
                $importTemp->save();
            }
        }
        echo iconv("utf-8","gbk","新增video数:$video_num"),"\r\n";      
    }

    private function videoSave($importTemp) {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        $wikiMetaRepos = $mongo->getRepository('wikiMeta');
        $wikiRes = $mongo->getRepository('wiki');
        $wiki=$wikiRes->findOneById(new MongoId($importTemp->getWikiId()));
        if(!$wiki) return false;
        $title=$wiki->getTitle();
        if($wiki->getModel()=='film'){ //电影
            $url=$this->getVideoUrl($importTemp->getChildrenId(),$importTemp->getProviderId());
            $config=array("asset_id"=>$importTemp->getChildrenId());
            //先查询是否重复,有则只更新
            $query=array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$importTemp->getChildrenId());
            $video=$video_repo->findOne(array('query'=>$query));
            if(!$video){
                $video = new Video();
                $wiki->setHasVideo(true);
                $wiki->save();  
            }    
            $video->setWikiId((string)$wiki->getId());
            $video->setModel($wiki->getModel());
            $video->setTitle($importTemp->getFromTitle());
            $video->setUrl($url);
            $video->setConfig($config);
            $video->setReferer($importTemp->getProviderId());
            $video->setPublish(true);
            //$video->setVideoPlaylistId($videoPlaylistId);
            //$video->setTime();
            //$video->setMark(1);
            $video->save();
        }else{  //电视剧，栏目等
            //写入videoPlayList表,先查询是否重复
            $query=array('referer'=>$importTemp->getProviderId(),'wiki_id'=>(string)$wiki->getId());
            $VideoPlaylist=$videoplaylist_repo->findOne(array('query'=>$query));
            if(!$VideoPlaylist){
                $VideoPlaylist = new VideoPlaylist();
                $VideoPlaylist->setTitle($title);
                //$VideoPlaylist->setUrl('');
                $VideoPlaylist->setReferer($importTemp->getProviderId());
                $VideoPlaylist->setWikiId((string)$wiki->getId());
                $VideoPlaylist->save();
            }
            //继续写入video表
            $videoPlaylistId=(string)$VideoPlaylist->getId();
            $url=$this->getVideoUrl($importTemp->getChildrenId(),$importTemp->getProviderId());
            $config=array("asset_id"=>$importTemp->getChildrenId());
            //匹配集数
            preg_match ("/\d+/", $importTemp->getFromTitle(), $marks);
            $mark=$marks[0]?$marks[0]:1;
            //先查询video是否重复
            $query=array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$importTemp->getChildrenId());
            $video=$video_repo->findOne(array('query'=>$query));
            if(!$video){
                $video = new Video();
                $wiki->setHasVideo(true);
                $wiki->save();
            }
            $video->setWikiId((string)$wiki->getId());
            $video->setModel($wiki->getModel());
            $video->setTitle($importTemp->getFromTitle());
            $video->setUrl($url);
            $video->setConfig($config);
            $video->setReferer($importTemp->getProviderId());
            $video->setPublish(true);
            $video->setVideoPlaylistId($videoPlaylistId);
            //$video->setTime();
            $video->setMark($mark);
            $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => (int) $mark)));
            if ($wikiMeta) {
                $video->setWikiMataId((string) $wikiMeta->getId());
            }
            $video->save();    
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
}
