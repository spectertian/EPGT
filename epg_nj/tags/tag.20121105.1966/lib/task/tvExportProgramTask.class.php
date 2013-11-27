<?php
class tvExportProgramTask extends sfMondongoTask
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
    $this->name             = 'ExportProgram';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ExportProgram|INFO] task does things.
Call it with:

  [php symfony tv:ExportProgram|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        //$code=$options['code'];  
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
        
		$channels = Doctrine::getTable('Channel')->createQuery("c")
                        ->where('c.publish = 1')
                        ->andWhere('c.type = "cctv"')
                        ->orWhere('c.type = "tv"')
                        ->execute();
        /*             
    	$channels = array('cctv1' => 'CCTV-1综合频道',
    					'cctv2' => 'CCTV-2财经频道',
    					'cctv3' => 'CCTV-3综艺频道',
    					'cctv4_asia' => 'CCTV-4中文国际频道亚洲版',
                        'cctv4_europe' => 'CCTV-4 欧洲',
                        'cctv4_america' => 'CCTV-4 美洲',
    					'cctv5' => 'CCTV-5体育频道',
    					'cctv6' => 'CCTV-6电影频道',
    					'cctv7' => 'CCTV-7军事农业频道',
    					'cctv8' => 'CCTV-8电视剧频道',
    					'cctv9' => 'CCTV-9记录频道',
    					'cctv10' => 'CCTV-10科教频道',
    					'cctv11' => 'CCTV-11戏曲频道',
    					'cctv12' => 'CCTV-12社会与法频道',
    					'cctv_news' => 'CCTV-13新闻频道',
    					'cctv_kids' => 'CCTV-14少儿频道',
    					'cctv_music' => 'CCTV-15音乐频道',
    					'5dfcaefe6e7203df9fbe61ffd64ed1c4' => '北京电视台-1',
    					'5731a167d79c432575056c4963dc8049' => '重庆卫视',
    					'5ace8ddc54a4151bbcf76e56c8aa582a' => '甘肃卫视',
    					'c8bf387b1824053bdb0423ef806a2227' => '广东卫视',
    					'5cbb108dbf59f2ae1849ec8d1126d1a5' => '广西卫视',
    					'5a7d01661b5d9c64293860531374312b' => '贵州卫视',
    					'0d7b5dfe999fc5fd0140863f6e8910a5' => '旅游卫视',
    					'ef1fce69a9e1b3a587ca734302400107' => '河北卫视',
    					'2c854868563485135dd486801057dd6e' => '河南卫视',
    					'1ce026a774dba0d13dc0cef453248fb7' => '黑龙江卫视',
    					'55fc65ef82e92d0e1ccb2b3f200a7529' => '湖北卫视',
    					'c39a7a374d888bce3912df71bcb0d580' => '湖南卫视',
    					'45392a8be644f5b8903838436870c75d' => '吉林卫视',
    					'322fa7b66243b8d0edef9d761a42f263' => '江苏卫视',
    					'535765a19ab55a12bbf64a1e98ae97dd' => '江西卫视',
    					'9291c40ec1cec1281638720c74c7245f' => '辽宁卫视',
    					'03295de404257fa9653b89bf2d0e47ac' => '内蒙古卫视',
    					'a09ab19928a6b2bd616f7e2eba1056ee' => '宁夏卫视',
    					'4ec095f1d2564f82341275fff64edb5a' => '青海卫视',
    					'28502a1b6bf5fbe7c6da9241db596237' => '山东卫视',
    					'2aeb585ccaca9fa893b0bdfdbc098c7f' => '山西卫视',
    					'eb7330e363ceec8c6895eacc44a1a804' => '陕西卫视',
    					'b82fa4086c9a2c9442279efbb80cce31' => '四川卫视',
    					'5927c7a6dd31f38686fafa073e2e13bc' => '天津卫视',
    					'feccf21eb7e50753355efdab2d54d9e8' => '西藏卫视',
    					'ad291a233f1fd3f24332e41461798a25' => '新疆卫视',
    					'c786da29f0f5cc5973444e3ad49413a6' => '云南卫视',
    					'590e187a8799b1890175d25ec85ea352' => '浙江卫视',
    					'antv' => '安徽卫视',
    					'fjtv' => '东南卫视',
    					'dragontv' => '东方卫视',
                        'jztv_high' => '浙江卫视（高清）'
                        );  
        */             
        $conn = @ftp_connect("10.20.88.211") or die("FTP服务器连接失败"); 
        @ftp_login($conn,"huanwang","huanwang") or die("FTP服务器登陆失败");   
        $conna = @ftp_connect("10.20.20.132") or die("FTP服务器连接失败"); 
        @ftp_login($conna,"wangyong","wangyong") or die("FTP服务器登陆失败");   
        foreach($channels as $channel){
              $nodeArray=array(); 
              $file='log/tmp_'.iconv("UTF-8","GBK",$channel->getName()).'.json';
              $target_file=iconv("UTF-8","GBK",$channel->getName()).'.json';
              @unlink($file);
              for($i = 0; $i < 3 ; $i ++) {
                   $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));	
                   $programs = $program_repo->getDayProgramsWiki($channel->getCode(), $date);
                   $nodeArray[$date]=array();
                   $k=0;
                   foreach($programs as $program){
                       $wiki = $program->getWiki();
                       $nodeArray[$date][$k]= array(
                                             'id'    => $program['wiki_id'],
                                             'title'=>$program['name'],
                                             'start_time'=>date("H:i",$program['start_time']->getTimestamp()),
                                             'end_time'=>date("H:i",$program['end_time']->getTimestamp()),
                                             'channel_code'=>$channel->getCode(),
                                             'channel_name'=>$channel->getName(),
                                             'channel_logourl'=>$this->file_url($channel->getLogo())
                                        ); 
                                        
                       if($wiki){
                            $nodeArray = $this->getWikiVideoSource($wiki, $k, $nodeArray,$date);                         
                       }   
                                 
                       $k++;            
                       
                   }
              }
              $jiemu_json=json_encode($nodeArray);
              /*
              $f = fopen($file, 'w');
              fwrite($f, $jiemu);
              fclose($f);
              */
              file_put_contents($file,$jiemu_json);
              @ftp_put($conn,$target_file,$file,FTP_ASCII);
              @ftp_put($conna,$target_file,$file,FTP_ASCII);
        }           
	    @ftp_close($conn);
        @ftp_close($conna);
	    echo "finished!";
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
     */
     private function getWikiVideoSource($wiki,$i,$nodeArray,$date,$type='',$biaozhi=0){
        $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
        $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
        $tags = !$wiki->getTags() ? '' : implode(',', $wiki->getTags());
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
                $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
            }            
        }
        $whether_mark = (gettype($type) =='array')?true:false;
        $nodeArray[$date][$i]['info'] = array(
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
        $nodeArray[$date][$i]['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if ($cover) {
            $nodeArray[$date][$i]['posters']['num'] = 3;
            $nodeArray[$date][$i]['posters']['poster'][0] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => $this->thumb_url($cover, 120, 160),
            );
            $nodeArray[$date][$i]['posters']['poster'][1] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => $this->thumb_url($cover, 240, 320),
            );
            $nodeArray[$date][$i]['posters']['poster'][2] = array(
                "type" => "max",
                "size" => "1240*460",
                "url" => $this->thumb_url($cover, 1240, 460),
            );
        }
        /*
        //增加剧照显示lifucang(2012-7-18)
	    $screen_num = $wiki->getScreenshotsCount();        
        $nodeArray[$date][$i]['screens']= array(
                        'num'    => $screen_num,
                );
        $screens = $wiki->getScreenshotUrls();   
        foreach($screens as $k => $screen)
        {
            $nodeArray[$date][$i]['screens']['screen'][$k]= array(
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
                            $nodeArray[$date][$i]['episodes'] = array(
                                "source" => "奇艺",
                                "num" => 1
                            );
                            $video_id = (string)$video->getId();
                            $nodeArray[$date][$i]['episodes']['episode'][0] = array(
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
                if ($playLists != NULL) {
                    foreach ($playLists as $playList) {
                       //$nodeArray=$this->addEpisodesTeleplay($i,$playList,$nodeArray);
                        if ($playList->getReferer() == 'qiyi') {
                            $countVideo = $playList->countVideo();
                            $nodeArray[$date][$i]['episodes'] = array(
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
    		                            $nodeArray[$date][$i]['episodes']['episode'][$j] = array(
    		                            	//"markid" => (string)$type['markid'],
    		                            	//"marktime" => $type['marktime'],
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
    	                            $nodeArray[$date][$i]['episodes']['episode'][$j] = array(
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
        */
        return $nodeArray;
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
    public function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        
        //return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
        return sprintf('http://172.31.139.17:81/thumb/'.'%s/%s/%s', $width, $height, $key);
    }  
    private function file_url($key = null)
    {
        if(is_null($key)){
            return false;
        }else{
            //$url =  sfConfig::get('app_static_url');
            $url =  "http://172.31.139.17:81/";
            $url.='%s/%s/%s/%s';
            $key_prefix = explode('.', $key);
            $key_prefix_year = substr($key_prefix[0],-2);
            $key_prefix_month = substr($key_prefix[0],-5,3);
            $key_prefix_day = substr($key_prefix[0],-9,4);
            return sprintf($url,$key_prefix_year,$key_prefix_month,$key_prefix_day,$key);
        }
    }    
}
