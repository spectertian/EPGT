<?php
/**
 *  @todo  : 将content_import中的信息导入video表和video_playlist表，同时更新wiki的has_video值
 *  @readme: 该计划任务已不用，因为现在是以上下线为准，不能把 ContentImport的全部内容导入到video表，再者ContentImport里也没有记录page_id
 *  @author: lifucang
 */
class tvContentImportVideoTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
          new sfCommandOption('id', null, sfCommandOption::PARAMETER_OPTIONAL, 'id'),
          new sfCommandOption('movieid', null, sfCommandOption::PARAMETER_OPTIONAL, 'movieid'),
          // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'ContentImportVideo';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $importImport_repo = $mongo->getRepository("ContentImport");   
        if(isset($options['id'])){
            $from_id=(string)$options['id'];
            $importImport = $importImport_repo->findOne(array('query'=>array('from_id'=>$from_id,'wiki_id'=>array('$exists'=>true)),'sort'=>array('_id'=>-1)));
            if($importImport){
                $this->videoSave($importImport);
                $importImport->setStateEdit(1);
                $importImport->save();
            }
        }elseif(isset($options['movieid'])){
            $children_id=(string)$options['movieid'];
            $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id,'wiki_id'=>array('$exists'=>true)),'sort'=>array('_id'=>-1)));
            if($importImport){
                $this->videoSave($importImport);
                $importImport->setStateEdit(1);
                $importImport->save();
            }
        }else{
            $add_num = $update_num = 0;
            $query=array('state'=>1,'state_edit'=>null,'wiki_id'=>array('$exists'=>true));
            $count = $importImport_repo->count($query);
            echo $count,"\n";
            $limit = 200; 
            $i = 0;
            while ($i < $count) 
            {
                //state改为记录上下线，state=1为上线，state=0为下线
                $importImports = $importImport_repo->find(array('query'=>$query,"sort" => array("_id"=>1),"limit" => $limit));
               
                foreach($importImports as $importImport){
                    $num = $this->videoSave($importImport);
            
                    $importImport->setStateEdit(1);
                    $importImport->save();
        
                    $add_num += $num['add'];
                    $update_num += $num['update'];
                    if($num['add'] > 0)
                        echo iconv('utf-8','gbk',$importImport->getFromTitle()),'------',$importImport->getWikiId(),"\r\n";  
                }
                $i = $i + $limit;
                echo $i,'*************************************',"\n"; 
                sleep(1); 
            }
            echo iconv("utf-8","gbk","新增video数:$add_num; 更新video数:$update_num"),"\r\n";      
        }    
    }
    
    private function videoSave($importTemp) 
    {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        //$wikiMetaRepos = $mongo->getRepository('wikiMeta');
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
            $fromId = $importTemp->getFromId();
            $url = ""; //$this->getVideoUrl($childrenid,$importTemp->getProviderId());
            $config = array("asset_id" => $childrenid,"from_id"=>$fromId);
            //先查询是否重复,有则只更新
            $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid);
            $video=$video_repo->findOne(array('query'=>$query));
            if(!$video){
                $video = new Video();
                //$wiki->setHasVideo(true);
                //$wiki->save();  
                $add_num++;
            }else{
                $update_num++;
            }    
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
        }else{  
            //电视剧，栏目等
            //写入videoPlayList表,先查询是否重复
            $fromId = $importTemp->getFromId();
            $query = array('referer'=>$importTemp->getProviderId(),'wiki_id'=>(string)$wiki->getId());
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
                $url = '';
                $config = array("asset_id" => $childrenid['ID'],"from_id"=>$fromId);
                $mark = $childrenid['Chapter'] ? $childrenid['Chapter'] : 0;
                
                $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid['ID']);
                $video=$video_repo->findOne(array('query'=>$query));
                if(!$video){
                    $video = new Video();
                    //$wiki->setHasVideo(true);
                    //$wiki->save(); 
                    $add_num++;
                }else{
                    $update_num++;
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
                $video->save(); 
            } 
        }
        $num=array('add'=>$add_num,'update'=>$update_num);
        return $num;
    }
    
    private function getVideoUrl($asset_id,$provider_id) 
    {
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
