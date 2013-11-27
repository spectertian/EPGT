<?php
/**
 *  @todo  : 导出json节目数据给需求方，暂未用
 *  @author: lifucang
 */
class tvEpgJsonTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'EpgJson';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:EpgJson|INFO] task does things.
Call it with:

  [php symfony tv:EpgJson|INFO]
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
        $ip = sfConfig::get('app_epgJson_ip');
        $username = sfConfig::get('app_epgJson_username');
        $password = sfConfig::get('app_epgJson_password');
        $conna = @ftp_connect("$ip") or die("FTP服务器连接失败"); 
        @ftp_login($conna,"$username","$password") or die("FTP服务器登陆失败"); 
        foreach($channels as $channel){
            if(!$channel->getChannelCode()) continue; //没有code，继续下一轮循环
            $nodeArray=array(); 
            $file='tmp/json/'.iconv("UTF-8","GBK",$channel->getName()).'.json';
            $target_file=iconv("UTF-8","GBK",$channel->getName()).'.json';
            @unlink($file);
            
            $date = date("Y-m-d");
            $programs = $program_repo->getDayProgramsWiki($channel->getChannelCode(), $date);
            
            $nodeArray['channel']=array(
                'name'=>$channel->getName(),
                'code'=>$channel->getChannelCode(),
                'logourl'=>$this->file_url($channel->getChannelLogo())
            );
            $nodeArray['total']=count($programs);
            $k=0;

            foreach($programs as $program){
               $wiki = $program->getWiki();
               if($wiki){
                   $nodeArray['programs'][$k]= array(
                    					'name' => $program['name'],
                    					'date' => $program['date'],
                    					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
                    					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
                    					'wiki_id' => $program['wiki_id'],
                                        'wiki_cover' => $this->file_url($wiki->getCover()),
                                        'tags' => !$wiki->getTags() ? '' : implode(',', $wiki->getTags()),
                                    ); 
                   $nodeArray = $this->getWikiVideoSource($wiki, $k, $nodeArray);      
               }else{
                   $nodeArray['programs'][$k]= array(
                    					'name' => $program['name'],
                    					'date' => $program['date'],
                    					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
                    					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
                    					'wiki_id' => $program['wiki_id'],
                                    ); 
               }       
               $k++;            
               
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
     * wiki对象返回wiki数组
     * @editor lifucang
     */
     private function getWikiVideoSource($wiki,$i,$nodeArray)
     {
        $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
        $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
        $tags = !$wiki->getTags() ? '' : implode(',', $wiki->getTags());
        $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
        $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
        $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
        $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
        $praise = !$wiki->getLikeNum() ? 0 : $wiki->getLikeNum();
        $dispraise = !$wiki->getDislikeNum() ? 0 : $wiki->getDislikeNum();
        $nodeArray['programs'][$i]['info'] = array(
            "director" => $director,
            "actors" => $actors,
            "type" => $tags,
            "area" => $area,
            "language" => $language,
            "score" => $score,
            "playdate" => $playdate
        );
        $nodeArray['programs'][$i]['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if ($cover) {
            $nodeArray['programs'][$i]['posters'][0] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => $this->thumb_url($cover, 120, 160),
            );
            $nodeArray['programs'][$i]['posters'][1] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => $this->thumb_url($cover, 240, 320),
            );
            $nodeArray['programs'][$i]['posters'][2] = array(
                "type" => "max",
                "size" => "1240*460",
                "url" => $this->thumb_url($cover, 1240, 460),
            );
        }
        return $nodeArray;
     }    
    /*
     * 返回wiki的类型
     * author lifucang
     */
    private function getTag($tags,$arr){
        $tmpstr = array();
        foreach($tags as $tag){
            if(!array_search($tag, $arr)){
                $tmpstr[] = $tag;
            }
        }
       return implode(",",$tmpstr);
    }    
    /*
     * 获取动态缩略图
     * @param <int> $height
     */
    private function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        $imgUrl = sfConfig::get('app_img_url');
        //return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
        return sprintf($imgUrl.'thumb/'.'%s/%s/%s', $width, $height, $key);
    }  
    
    private function file_url($key = null)
    {
        if(is_null($key)){
            return false;
        }else{
            //$url =  sfConfig::get('app_static_url');
            $imgUrl = sfConfig::get('app_img_url');
            $url.='%s/%s/%s/%s';
            $key_prefix = explode('.', $key);
            $key_prefix_year = substr($key_prefix[0],-2);
            $key_prefix_month = substr($key_prefix[0],-5,3);
            $key_prefix_day = substr($key_prefix[0],-9,4);
            return sprintf($url,$key_prefix_year,$key_prefix_month,$key_prefix_day,$key);
        }
    }   
}
