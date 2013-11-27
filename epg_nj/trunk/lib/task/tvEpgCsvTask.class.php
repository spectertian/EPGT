<?php
/**
 *  @todo  : 导出csv节目数据给需求方，暂未用
 *  @author: lifucang
 */
class tvEpgCsvTask extends sfMondongoTask
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
            '电视剧'=>'Series',
            '电影'=>'Movie',
            '体育'=>'Sports',
            '娱乐'=>'Entertainment',
            '少儿'=>'Children',
            '教育'=>'Education',
            '财经'=>'Finance',
            '综合'=>'General',
            '其他'=>'Other',
        );
        //$code=$options['code'];  
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
        $channels=$mongo->getRepository('SpService')->getServicesByTag();

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
            
        $tt = 'asset_id,asset_name,asset_description,genre,Channel_id,Channel_name,Channel_description,Channel_sort,usergroup,product_region,director,actors,languages,run_time,re_start_time,re_end_time,start_time,end_time,online_time,offline_time,keyword,HDContent,3DContent,screen_format,provider_id,score,rate,price,valid_time,recordable,url,text,poster,tag1,tag2,tag3,tag4,tag5';
        $tt .= "\n";                  
        foreach($channels as $channel){
            if(!$channel->getChannelCode()) continue; //没有code，继续下一轮循环
            $channel_name='"' .$channel->getName().'"';
            $channel_loginnumber=$channel->getLogicNumber();      
            $channel_tags=!$channel->getTags()? '' : implode(';', $channel->getTags()); 
            $programs = $program_repo->getDayProgramsWiki($channel->getChannelCode(), $date);
            if(!$programs) continue; //没有program，继续下一轮循环
            //节目单
            foreach($programs as $program){
                $wiki = $program->getWiki();
                $program_name=str_replace("\"","”",$program['name']);
                $program_name='"' .$program_name.'"';
                if($wiki){
                    $category_code=array();
                    foreach($wiki->getTags() as $value){
                        if($arr_type[$value]!='')
                            $category_code[]=$arr_type[$value];
                    }
                    $type=!$category_code? '' : implode(';', $category_code);
                    $director = !$wiki->getDirector() ? '' : implode(';', $wiki->getDirector());
                    $actors = !$wiki->getStarring() ? '' : implode(';', $wiki->getStarring());
                    $tags = !$wiki->getTags() ? ' ' : implode(';', $wiki->getTags());
                    $area = !$wiki->getCountry() ? '' : $wiki->getCountry();
                    $language = !$wiki->getLanguage() ? '' : $wiki->getLanguage();
                    $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
                    $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
                    $runtime = !$wiki->getRuntime() ? '' : $wiki->getRuntime();
                    $cover = $wiki->getCover();
                    $poster=array();
                    if ($cover) {
                        $poster[] = $this->thumb_url($cover, 120, 160);
                        $poster[] = $this->thumb_url($cover, 240, 320);
                        $poster[] = $this->thumb_url($cover, 1240, 460);
                        $poster_str=implode(';', $poster);
                    }else{
                        $poster_str='';
                    }
                    $content=mb_strcut($wiki->getContent(), 0, 12288, 'utf-8');
                    $content=str_replace("\"","”",$content);
                    $content=str_replace("\r"," ",$content);
                    $content=str_replace("\n"," ",$content);
                    $content='"' .$content.'"';
                    //加入"，防止中间有,
                    $type='"' .$type.'"';
                    $director='"' .$director.'"';
                    $actors='"' .$actors.'"';
                    $tags='"' .$tags.'"';
                    $area='"' .$area.'"';
                    $language='"' .$language.'"';
                    $score='"' .$score.'"';
                    $playdate='"' .$playdate.'"';
                    $runtime='"' .$runtime.'"';
                    //开始
                    $tt .= $program->getId().",";      //节目id *
                    $tt .= $program_name.",";          //节目名称 *
                    $tt .= $content.",";               //节目描述 *
                    $tt .= $type.",";                  //节目分类 *
                    $tt .= $channel_loginnumber.",";   //频道号 *
                    $tt .= $channel_name.",";          //频道名称 *
                    $tt .= $channel_name.",";          //频道描述
                    $tt .= $channel_tags.",";          //频道分类
                    $tt .= ",";                        //产品可用用户组
                    $tt .= $area.",";                  //原产地 *
                    $tt .= $director.",";              //导演 *
                    $tt .= $actors.",";                //演员 *
                    $tt .= $language.",";              //语言 *
                    $tt .= $runtime.",";               //影片时长
                    $tt .= ",";                        //推荐开始时间
                    $tt .= ",";                        //推荐结束时间
                    $tt .= date("Y-m-d H:i",$program['start_time']->getTimestamp()).","; //影片开始 *
                    $tt .= date("Y-m-d H:i",$program['end_time']->getTimestamp()).",";   //影片结束 *
                    $tt .= ",";                        //上线时间
                    $tt .= ",";                        //下线时间
                    $tt .= $tags.",";                  //搜索关键字 *
                    $tt .= "N".",";                    //是否为高清 *
                    $tt .= "N".",";                    //是否为3D   *            
                    $tt .= ",";                        //展现的终端类型 
                    $tt .= ",";                        //提供商ID
                    $tt .= $score.",";                 //评分
                    $tt .= ",";                        //等级
                    $tt .= ",";                        //价格
                    $tt .= ",";                        //节目保存有效时间
                    $tt .= ",";                        //频道是否可录制
                    $tt .= " ,";                       //推荐位跳转url *
                    $tt .= " ,";                       //推荐位文字描述 *
                    $tt .= $poster_str.",";            //推荐位海报 *
                    $tt .= ",,,,,";                     //标签1-5
                    $tt .= "\n";                       //换行
                }else{
                    $tt .= $program->getId().",";      //节目id *
                    $tt .= $program_name.",";          //节目名称 *
                    $tt .= " ,";                       //节目描述 *
                    $tt .= " ,";                       //节目分类 *
                    $tt .= $channel_loginnumber.",";   //频道号 *
                    $tt .= $channel_name.",";          //频道名称 *
                    $tt .= $channel_name.",";          //频道描述
                    $tt .= $channel_tags.",";          //频道分类
                    $tt .= ",";                        //产品可用用户组
                    $tt .= " ,";                       //原产地 *
                    $tt .= " ,";                       //导演 *
                    $tt .= " ,";                       //演员 *
                    $tt .= " ,";                       //语言 *
                    $tt .= ",";                        //影片时长
                    $tt .= ",";                        //推荐开始时间
                    $tt .= ",";                        //推荐结束时间
                    $tt .= date("Y-m-d H:i",$program['start_time']->getTimestamp()).","; //影片开始 *
                    $tt .= date("Y-m-d H:i",$program['end_time']->getTimestamp()).",";   //影片结束 *
                    $tt .= ",";                        //上线时间
                    $tt .= ",";                        //下线时间
                    $tt .= " ,";                       //搜索关键字 *
                    $tt .= "N".",";                    //是否为高清 *
                    $tt .= "N".",";                    //是否为3D   *            
                    $tt .= ",";                        //展现的终端类型 
                    $tt .= ",";                        //提供商ID
                    $tt .= ",";                        //评分
                    $tt .= ",";                        //等级
                    $tt .= ",";                        //价格
                    $tt .= ",";                        //节目保存有效时间
                    $tt .= ",";                        //频道是否可录制
                    $tt .= " ,";                       //推荐位跳转url *
                    $tt .= " ,";                       //推荐位文字描述 *
                    $tt .= " ,";                       //推荐位海报 *
                    $tt .= ",,,,,";                     //标签1-5
                    $tt .= "\n";                       //换行 
                }
            }

        }        
        //$tt=iconv("UTF-8","GBK",$tt); 
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
        
        return sprintf(sfConfig::get('app_img_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }   
}
