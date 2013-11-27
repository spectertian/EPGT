<?php
/**
 *  导出每天JSON文本wiki数据至FTP服务器wiki目录下
 *  @author: gaobo
 */
class wikiToFtpForNASTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('rebuild', null, sfCommandOption::PARAMETER_OPTIONAL, 'rebuild'),//yes：重建
        ));

        $this->namespace        = 'tv';
        $this->name             = 'wikiToFtpForNAS';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [wikiToFtpForNAS|INFO] task does things.
Call it with:

  [php symfony tv:wikiToFtpForNAS|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $rebuild = strval($options['rebuild']);
        $conna = ftp_connect("ch.f.cedock.com","1001") or die("FTP服务器连接失败");
        ftp_login($conna,"usanas","2wsx@usanas") or die("FTP服务器登陆失败");
        $mongo = $this->getMondongo();
        $WikiRepository = $mongo->getRepository('Wiki');
        
        if($rebuild == 'yes'){
            $startDate = '2010-01-01';
            $limit = 1000;
            $step  = 0;
            $i = 0;
            while (true) {
                $wikis = $WikiRepository->find(array("sort" => array("_id" => 1), "limit" => $limit, "skip" => $step));
                //$wikis = $WikiRepository->find(array("query" => array("_id" => new MongoId('4d007dd22f2a241bd7000017'))));
                if (!$wikis) break;
                $date = date('Ymd',(strtotime($startDate)+$i));
                foreach ($wikis as $wiki) {
                    $nodeArray['media'] = array(
                            'id'    => (string)$wiki->getId(),
                            'title'   => $wiki->getTitle(),
                    );
                    $nodeArray = self::getWikiNoteArray($wiki,0,$nodeArray);
                    $wikiJson = json_encode($nodeArray,true);
                    $filename = $wiki->getId().'.txt';
                    
                    if($wikiJson){
                        echo $filename."\n";
                        file_put_contents($filename, @iconv("UTF-8","GBK//IGNORE",$wikiJson));
                        ftp_pasv($conna,true);
                        @ftp_mkdir($conna,'/wiki/'.$date);
                        $target_file = '/wiki/'.$date.'/'.iconv("UTF-8","GBK//IGNORE",$filename);
                        ftp_put($conna,$target_file,$filename,FTP_ASCII);
                        @unlink($filename);
                        echo "###\n";
                    }
                    unset($wiki);
                }
                $step = $step + $limit;
                $i = $i + 86400;
                echo $step,"\n";
            }
            echo 'done!';
        }
        if(!$rebuild){
            //FIXME
            $starttime = strtotime(date('Y-m-d',strtotime("-1 day"))." 00:00:00");
            $starttime = new MongoDate($starttime);
            $endtime   = strtotime(date('Y-m-d',strtotime("-1 day"))." 23:59:59");
            $endtime   = new MongoDate($endtime);
            $wikis = $WikiRepository->find(array("query" => array("created_at" => array('$gte'=>$starttime,'$lte'=>$endtime),"sort" => array("_id" => 1))));
    
            $date = date('Ymd',strtotime("-1 day"));
            
            foreach ($wikis as $wiki) {
                $nodeArray = self::getWikiNoteArray($wiki,0,$nodeArray);
                $wikiJson = json_encode($nodeArray,true);
                $filename = $wiki->getId().'.txt';
                if($wikiJson){
                    file_put_contents($filename, @iconv("UTF-8","GBK//IGNORE",$wikiJson));
                    ftp_pasv($conna,true);
                    @ftp_mkdir($conna,'/wiki/'.$date);
                    $target_file = '/wiki/'.$date.'/'.iconv("UTF-8","GBK//IGNORE",$filename);
                    ftp_put($conna,$target_file,$filename,FTP_ASCII);
                    @unlink($filename);
                }
                unset($wiki);
            }
        }
    }
    
    private function getWikiNoteArray($wiki)
    {
        $nodeArray = array();
        $mongo = $this->getMondongo();
        if($wiki)
        {
            $wiki_id = (string)$wiki->getId();
            $nodeArray['media'] = array(
                    'id'    => $wiki_id,
                    'title'   => $wiki->getTitle(),
            );
            $nodeArray = $this->getOneWikiVideoSource($wiki, 0, $nodeArray);
            $userRepository = $mongo->getRepository('user');
            $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
            
            if($hasUser)
            {
                $user_id = $hasUser->getId();
                $chipRepository = $mongo->getRepository('singlechip');
                $chip = $chipRepository->getOneChip((string)$user_id,$wiki_id);
                if($chip)
                {
                    $nodeArray['media']['action']= array(
                            'type' => 'favorite',
                            'var'  => '1',
                            'datetime' => date("Y-m-d H:i:s",$chip->getCreatedAt()->getTimestamp()),
                    );
                }
                else
                    $nodeArray['media']['action']= array(
                            'type' => '',
                            'var'  => '',
                            'datetime' => '',
                    );
            }
            else
                $nodeArray['media']['action']= array(
                        'type' => '',
                        'var'  => '',
                        'datetime' => '',
                );
        
            //获取节目信息lfc
            $program_repository = $mongo->getRepository('Program');
            $programs = $program_repository->getdayUnPlayedProgramByWikiId($wiki_id);
            $programs_num=count($programs);
            $nodeArray['media']['programs']= array(
                    'num'    => $programs_num,
            );
            
            foreach($programs as $k => $program)
            {
                $channel_logo = $program->getChannel()->getLogo();
                if (strlen($channel_logo) > 1) {
                    $channel_logo = self::file_url($channel_logo);
                } else {
                    $channel_logo = "";
                }
                
                $endTime = $program->getEndTime();
                $nodeArray['media']['programs']['program'][$k]= array(
                        'channel_code'    =>  $program->getChannelCode(),
                        'channel_logo'    =>  $channel_logo,
                        'channel_name'    =>  $program->getChannelName(),
                        'program_name'    =>  $program->getName(),
                        'start_time'      =>  date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
                        'end_time'        =>  !empty($endTime)?date("Y-m-d H:i:s",$endTime->getTimestamp()):'',
                );
            }
        
        }
        return $nodeArray;
    }
    
    /*
     * wiki对象返回视频源数组
    * @param  mongo object  $wiki
    * @param  array $nodeArray
    * @param  int $type 默认为1 如果为GetEpisodeListByUser调用此函数则传入数组
    * $type['eid']：分集video_id
    * $type['marktime']：标记秒数
    * $type['markid']：mark_id
    * @return $nodeArray
    * @author guoqiang.zhang
    * @editor lifucang
    * 和getWikiVideoSource一样，只是$nodeArray['media'][$i]部分全部换为$nodeArray['media']，目前只有GetWikiInfo调用
    */
    private function getOneWikiVideoSource($wiki,$i,$nodeArray,$type='',$biaozhi=0){
        $model = $wiki->getModel();
        if ($model == 'actor') {
            $nodeArray['media']['info'] = array(
                    "english_name" => $wiki->getEnglishName(),
                    "nickname" => $wiki->getNickname(),
                    "sex" => $wiki->getSex(),
                    "birthday" => $wiki->getBirthday(),
                    "birthplace" => $wiki->getBirthplace(),
                    "occupation" => $wiki->getOccupation(),
                    "nationality" => $wiki->getNationality(),
                    "zodiac" => $wiki->getZodiac(),
                    "bloodType" => $wiki->getBloodType(),
                    "debut" => $wiki->getDebut(),
                    "height" => $wiki->getHeight(),
                    "weight" => $wiki->getWeight(),
                    "region" => $wiki->getRegion(),
            );
        }elseif($model == 'film'){
            $nodeArray['media']['info'] = array(
                    "alias" =>$wiki->getAlias(),
                    "director" => $wiki->getDirector(),
                    "starring" => $wiki->getStarring(),
                    "released" => $wiki->getReleased(),
                    "language" => $wiki->getLanguage(),
                    "country" => $wiki->getCountry(),
                    "writer" => $wiki->getWriter(),
                    "distributor"=>$wiki->getDistributor(),
                    "runtime"=>$wiki->getRuntime(),
                    "produced"=>$wiki->getProduced(),
            );
        }elseif($model == 'teleplay'){
            $nodeArray['media']['info'] = array(
                    "alias" =>$wiki->getAlias(),
                    "director" => $wiki->getDirector(),
                    "starring" => $wiki->getStarring(),
                    "released" => $wiki->getReleased(),
                    "language" => $wiki->getLanguage(),
                    "country" => $wiki->getCountry(),
                    "writer" => $wiki->getWriter(),
                    "distributor"=>$wiki->getDistributor(),
                    "runtime"=>$wiki->getRuntime(),
                    "produced"=>$wiki->getProduced(),
                    "episodes"=>$wiki->getEpisodes(),
            );
        }elseif($model == 'television'){
            $nodeArray['media']['info'] = array(
                    "channel" =>$wiki->getChannel(),
                    "play_time" => $wiki->getPlayTime(),
                    "host" => $wiki->getHost(),
                    "guest" => $wiki->getGuests(),
                    "producer" => $wiki->getProducer(),
                    "alias" => $wiki->getAlias(),
                    "runtime"=>$wiki->getRuntime(),
                    "country"=>$wiki->getCountry(),
                    "language"=>$wiki->getLanguage(),
            );
        }

        $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
        $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
        $tags = !$wiki->getTags() ? '' : $this->getTag($wiki->getTags(),array($this->category[1]['name'],$this->category[2]['name']));
        $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
        $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
        $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
        $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
        $praise = !$wiki->getLikeNum() ? 0 : $wiki->getLikeNum();
        $dispraise = !$wiki->getDislikeNum() ? 0 : $wiki->getDislikeNum();
        $videos = $wiki->getVideos();
        $refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
        $source = '';
        $prefer = "奇艺"; //优选片源
        if ($videos != NULL) {
            foreach ($videos as $video) {
                /*if($refererSource[$video->getReferer()]){
                 $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
                }*/
                //只用qiyi视频源
                if($video->getReferer() == "qiyi"){
                    $source = $prefer;
                }
            }
        }
        $whether_mark = (gettype($type) =='array')?true:false;
        /** modify by tianzhongsheng-ex@huan.tv 2013-09-05 wiki更具具体模型返回数据
         $nodeArray['media']['info'] = array(
         "director" => $director,
         "actors" => $actors,
         "type" => $tags,
         "area" => $area,
         "language" => $language,
         "score" => $score,
         "playdate" => $playdate,
         "praise" => $praise,
         "dispraise" => $dispraise,
         "source" => $source,
         "prefer" => $prefer
         );
         **/
        $nodeArray['media']['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if ($cover) {
            $nodeArray['media']['posters']['num'] = 3;
            $nodeArray['media']['posters']['poster'][0] = array(
                    "type" => "small",
                    "size" => "120*160",
                    "url" => self::thumb_url($cover, 120, 160),
            );
            $nodeArray['media']['posters']['poster'][1] = array(
                    "type" => "big",
                    "size" => "240*320",
                    "url" => self::thumb_url($cover, 240, 320),
            );
            $nodeArray['media']['posters']['poster'][2] = array(
                    "type" => "max",
                    "size" => "1240*460",
                    "url" => self::thumb_url($cover, 1240, 460),
            );
        }
        //增加剧照显示lifucang(2012-7-18)
        $screen_num = $wiki->getScreenshotsCount();
        $nodeArray['media']['screens']= array(
                'num'    => $screen_num,
        );
        $screenshots = $wiki->getScreenshots();
        $screens = array();
        if($screenshots) {
            foreach($screenshots as $screenshot) {
                $screens[] = self::thumb_url($screenshot, 150, 150);
            }
        }
        
        foreach($screens as $k => $screen)
        {
            $nodeArray['media']['screens']['screen'][$k]= array(
                    'url'    =>  $screens[$k],
            );
        }
        if($biaozhi==1){
            if($type['type']==1){
                $xianshi=false;
            }else{
                $xianshi=true;
            }
        }else{
            $xianshi=true;
        }
        
        if($xianshi){  //lfc增加
            $model = $wiki->getModel();
            if ($model == 'film') {
                $videos = $wiki->getVideos();
                if ($videos != NULL) {
                    foreach ($videos as $video) {
                        $tvconfig = $video->getConfig();
                        //$nodeArray=$this->addEpisodesFilm($i,$video,$nodeArray);
                        if ($video->getReferer() == 'qiyi') {
                            $nodeArray['media']['episodes'] = array(
                                    "source" => "奇艺",
                                    "num" => 1
                            );
                            $video_id = (string)$video->getId();
                            $nodeArray['media']['episodes']['episode'][0] = array(
                                    "id" => $video_id,//由于数据由此处获取 无需判断传来的eide是否匹配此video_id
                                    "index" => 1,
                                    "size" => 0,
                                    "length" => 0,
                                    "format" => '',
                                    "rate" => 0,
                                    "vip" => 0,
                                    "url" =>  $this->setVideoUrl($tvconfig['tvId']),
                                    "live" => 0
                            );
                        }
                    }
                }
            }
            if ($model == 'teleplay') {
                $playLists = $wiki->getPlayList();
                if (count($playLists)>0) {
                    foreach ($playLists as $playList) {
                        if ($playList->getReferer() == 'qiyi') {
                            $countVideo = $playList->countVideo();
                            $nodeArray['media']['episodes'] = array(
                                    "source" => "奇艺",
                                    "num" => $countVideo,
                            );
                            $videos = $playList->getVideos();
                            $j = 0;
                            if($whether_mark)
                            {
                                foreach ($videos as $video) {
                                    $tvconfig = $video->getConfig();
                                    if((string)$video->getId()==$type['eid'])
                                        $nodeArray['media']['episodes']['episode'][$j] = array(
                                                "id" => (string)$video->getId(),
                                                "index" => $video->getMark(),
                                                "size" => 0,
                                                "length" => 0,
                                                "format" => '',
                                                "rate" => 0,
                                                "vip" => 0,
                                                "url" => $this->setVideoUrl($tvconfig['tvId']),
                                                "live" => 0
                                        );
                                    $j++;
                                }
                            }
                            else
                            {
                                foreach ($videos as $video) {
                                    $tvconfig = $video->getConfig();
                                    $nodeArray['media']['episodes']['episode'][$j] = array(
                                            "id" => (string)$video->getId(),
                                            "index" => $j,
                                            "size" => 0,
                                            "length" => 0,
                                            "format" => '',
                                            "rate" => 0,
                                            "vip" => 0,
                                            "url" => $this->setVideoUrl($tvconfig['tvId']),
                                            "live" => 0
                                    );
                                    $j++;
                                }
                            }
                        }
                    }
                }
            }
        }
    
        return $nodeArray;
    }
    
    private function setVideoUrl($id=0){
        return "http://proxy.kkttww.net:8080/urlproxy/qiyi/?redirect=1&tv_id=".$id;
    }
    
    /*
     * 返回wiki的类型
    * @param array $tags
    * @return string $tag
    * author lifucang
    */
    public function getTag($tags,$arr){
        $tmpstr = array();
        foreach($tags as $tag){
            if(!array_search($tag, $arr)){
                $tmpstr[] = $tag;
            }
        }
        return implode(",",$tmpstr);
    }
    
    /**
     * 获取动态缩略图
     * @param <string> $key
     * @param <int> $width
     * @param <int> $height
     */
    private function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
    
        return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }
    
    private function file_url($key = null)
    {
        if(is_null($key))
        {
            return false;
        }else{
            $url =  sfConfig::get('app_static_url');
            $url.='%s/%s/%s/%s';
            $key_prefix = explode('.', $key);
            $key_prefix_year = substr($key_prefix[0],-2);
            $key_prefix_month = substr($key_prefix[0],-5,3);
            $key_prefix_day = substr($key_prefix[0],-9,4);
            return sprintf($url,$key_prefix_year,$key_prefix_month,$key_prefix_day,$key);
        }
    }
}
