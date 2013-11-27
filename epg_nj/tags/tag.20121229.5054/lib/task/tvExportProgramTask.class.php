<?php
/**
 *  @todo  : 导出json节目数据给需求方
 *  @author: lifucang
 */
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
        
		$channels=$mongo->getRepository('SpService')->getServicesByTag();       
        //$conn = @ftp_connect("10.20.88.211") or die("FTP服务器连接失败"); 
        //@ftp_login($conn,"huanwang","huanwang") or die("FTP服务器登陆失败");   
        $conna = @ftp_connect("172.31.198.32") or die("FTP服务器连接失败"); 
        @ftp_login($conna,"huanwang","huanwang") or die("FTP服务器登陆失败");   
        foreach($channels as $channel){
              if(!$channel->getChannelCode()) continue; //没有code，继续下一轮循环
              $nodeArray=array(); 
              $file='tmp/json_use/tmp_'.iconv("UTF-8","GBK",$channel->getName()).'.json';
              $target_file=iconv("UTF-8","GBK",$channel->getName()).'.json';
              @unlink($file);
              for($i = 0; $i < 3 ; $i ++) {
                   $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));	
                   //$programs = $program_repo->getDayProgramsWiki($channel->getChannelCode(), $date);
                   $programs = $program_repo->getDayPrograms($channel->getChannelCode(), $date);
                   $nodeArray[$date]=array();
                   $k=0;
                   foreach($programs as $program){
                       $wiki = $program->getWiki();
                       $nodeArray[$date][$k]= array(
                                             'id'    => $program['wiki_id'],
                                             'title'=>$program['name'],
                                             'start_time'=>date("H:i",$program['start_time']->getTimestamp()),
                                             'end_time'=>date("H:i",$program['end_time']->getTimestamp()),
                                             'channel_code'=>$channel->getChannelCode(),
                                             'channel_name'=>$channel->getName(),
                                             'channel_logourl'=>$this->file_url($channel->getChannelLogo())
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
              //@ftp_put($conn,$target_file,$file,FTP_ASCII);
              @ftp_put($conna,$target_file,$file,FTP_ASCII);
        }           
	    //@ftp_close($conn);
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
