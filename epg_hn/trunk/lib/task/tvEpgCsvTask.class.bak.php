<?php

class tvEpgCsvTask extends sfMondongoTask
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
    $this->name             = 'EpgCsv';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:EpgCsv|INFO] task does things.
Call it with:

  [php symfony tv:EpgCsv|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $arr_type=array(
            '电视剧'=>'tvplays',
            '电影'=>'movie',
            '体育'=>'sports',
            '娱乐'=>'ent',
            '少儿'=>'children',
            '教育'=>'edu',
            '财经'=>'finance',
            '综合'=>'general',
        );
        //$code=$options['code'];  
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
        
		$channels = Doctrine::getTable('Channel')->createQuery("c")
                        ->where('c.publish = 1')
                        ->andWhere('c.type = "cctv"')
                        ->orWhere('c.type = "tv"')
                        ->execute();
                   
        $conn = @ftp_connect("172.20.224.146") or die("FTP服务器连接失败"); 
        @ftp_login($conn,"ftp146","cep@163#@)!@ftp") or die("FTP服务器登陆失败");   
        //$conna = @ftp_connect("10.20.20.132") or die("FTP服务器连接失败"); 
        //@ftp_login($conna,"wangyong","wangyong") or die("FTP服务器登陆失败"); 
        
        $date = date("Y-m-d");
        $file='tmp/csv/tv_'.date("YmdHis").'.csv';
        $file1='tmp/csv/tv_'.date("YmdHis").'.ctl';
        $target_file='tv_'.date("YmdHis").'.csv';
        $target_file1='tv_'.date("YmdHis").'.ctl'; //标识完成，内容为空
        @unlink($file);   
            
        $tt = 'id,language,is_active,category_code,Title,Description,Channel_name,product_region,director,actors,start_time,end_time,keyword,HDContent,3DContent,recordable,poster,approved_status';
        $tt .= "\n";                  
        foreach($channels as $channel){
            $programs = $program_repo->getDayProgramsWiki($channel->getCode(), $date);
            //节目单
            foreach($programs as $program){
                $wiki = $program->getWiki();
                $program_name='"' .$program['name'].'"';
                $channel_name='"' .$channel->getName().'"';
                if($wiki){
                    $category_code=array();
                    foreach($wiki->getTags() as $value){
                        if($arr_type[$value]!='')
                            $category_code[]=$arr_type[$value];
                    }
                    $type=!$category_code? '' : implode(';', $category_code);
                    $director = !$wiki->getDirector() ? '' : implode(';', $wiki->getDirector());
                    $actors = !$wiki->getStarring() ? '' : implode(';', $wiki->getStarring());
                    $tags = !$wiki->getTags() ? '' : implode(';', $wiki->getTags());
                    $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
                    $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
                    $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
                    $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
                    $cover = $wiki->getCover();
                    $poster=array();
                    if ($cover) {
                        $poster[] = $this->thumb_url($cover, 120, 160);
                        $poster[] = $this->thumb_url($cover, 240, 320);
                        $poster[] = $this->thumb_url($cover, 1240, 460);
                    }
                    $content=mb_strcut($wiki->getContent(), 0, 12288, 'utf-8');
                    $content=str_replace(",","，",$content);
                    $content=str_replace("\"","”",$content);
                    $content=str_replace("\r","",$content);
                    $content=str_replace("\n","",$content);
                    $content='"' .$content.'"';
                    
                    $area='"' .$area.'"';
                    $director='"' .$director.'"';
                    $actors='"' .$actors.'"';
                    $tags='"' .$tags.'"';
                    
                    $tt .= $program->getId().",";
                    $tt .= "zh".",";
                    $tt .= "1".",";
                    $tt .= $type.",";
                    $tt .= $program_name.",";
                    $tt .= $content.",";    
                    $tt .= $channel_name.",";
                    $tt .= str_replace(",",";",$area).",";
                    $tt .= $director.",";
                    $tt .= $actors.",";
                    
                    $tt .= date("Y-m-d H:i",$program['start_time']->getTimestamp()).",";
                    $tt .= date("Y-m-d H:i",$program['end_time']->getTimestamp()).",";  
                    $tt .= $tags.",";
                    $tt .= ",,,";
                    $tt .= implode(';', $poster).",";
                    $tt .= "1\n";
                }else{
                    $tt .= $program->getId().",";
                    $tt .= "zh".",";
                    $tt .= "1".",";
                    $tt .= ",";
                    $tt .= $program_name.",";
                    $tt .= ",";  
                    $tt .= $channel_name.",";
                    $tt .= ",";
                    $tt .= ",";
                    $tt .= ",";
                    $tt .= date("Y-m-d H:i",$program['start_time']->getTimestamp()).",";
                    $tt .= date("Y-m-d H:i",$program['end_time']->getTimestamp()).",";  
                    $tt .= ",";
                    $tt .= ",,,";
                    $tt .= ",";
                    $tt .= "1\n";    
                }
            }

        }        
        //file_put_contents($file,iconv("UTF-8","GBK",$tt));   
        file_put_contents($file,$tt); 
        file_put_contents($file1,''); 
        @ftp_put($conn,$target_file,$file,FTP_ASCII);
        @ftp_put($conn,$target_file1,$file1,FTP_ASCII);
        //@ftp_put($conna,$target_file,$file,FTP_ASCII);          
	    @ftp_close($conn);
        //@ftp_close($conna);
	    echo "finished!";
    }
    /*
     * 返回wiki的类型
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
    /*
     * 获取动态缩略图
     */
    public function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        
        return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }   
}
