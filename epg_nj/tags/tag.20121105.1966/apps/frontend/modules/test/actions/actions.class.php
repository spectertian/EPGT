<?php

/**
 * test actions.
 *
 * @package    epg2.0
 * @subpackage test
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }

  public function executeGetPrograms(sfWebRequest $request)
  {
        $mongo = $this->getMondongo();
        $channels = Doctrine::getTable('Channel')->getAllChannelByTv('cctv');
        $start_time=date('Y-m-d 00:00:00');
        $end_time=date('Y-m-d 23:59:59');
        $today=date('Y-m-d');
        $today_next=date('Y-m-d',mktime(0,0,0,date('m'),date('d')+1,date('Y')));
        $url='http://www.epg.huan.tv/json';
        
        
        
        
        set_time_limit(0); 
        //ob_end_clean();
        foreach($channels as $channel){
              $json_post='{"action":"GetProgramsByChannel","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"channel_code":"'.$channel->getCode().'","start_time":"'.$start_time.'","end_time":"'.$end_time.'"}}';
              //$json_post=array('action'=>'GetProgramsByChannel','device'=>array('dnum'=>'123'),'user'=>array('userid'=>'123'),'param'=>array('channel_code'=>$channel->getCode(),'start_time'=>$start_time,'end_time'=>$end_time));
              //$json_post=json_encode($json_post); 
              $getinfo = Common::post_json($url,$json_post);
		      $result = json_decode($getinfo,true);
              $programs=$result['program'];
              foreach($programs as $program_arr){
                   $start_timea=$program_arr['date'].' '.$program_arr['start_time'];
                   $end_timea=$today.' '.$program_arr['end_time'];
                   if(strtotime($end_timea)<strtotime($start_timea)){
                       $end_timea=$today_next.' '.$program_arr['end_time'];
                   }
                   /*
                   $program = new Program();
                   $program->setName($program_arr['name']);
                   $program->setTags($program_arr['tags']);
                   $program->setStartTime(new DateTime($start_timea));
                   $program->setEndTime(new DateTime($end_timea));
                   $program->setPublish(true);
                   $program->setWikiId($program_arr['wiki_id']);
                   $program->setTime($program_arr['start_time']);
                   $program->setDate($program_arr['date']);
                   $program->setChannelCode($channel->getCode());
                   $program->save();
                   */
                   //开始判断wiki表里是否存在该wiki，如果不存在，从接口导入该wiki
                   echo $program_arr['name'],'<br/>';
                   
                   if($program_arr['wiki_id']){
                       //$wiki = $mongo->getRepository("Wiki")->findOneById(new MongoId($program_arr['wiki_id']));
                       //if(!$wiki){
                            $this->importWiki($program_arr['wiki_id']);
                       //}
                   }
                   echo '*************************************','<br/>';
                   //ob_flush();
                   //flush();
                   //sleep(1);
              }
              exit;
        }       
        return sfView::NONE;
  }  
  
  //导入wiki
  private function importWiki($wiki_id){
        $url='http://www.epg.huan.tv/json';
        $json_post='{"action":"GetWikiInfo","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wiki_id.'"}}';
        //$url='http://www.epg.vm/json';
        //$json_post='{"action":"GetWikiInfoGd","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wiki_id.'"}}';
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true);
        $wikiinfo=$result['media'];  //正式服务器上
        //$wikiinfo=$result['wiki'];  
        if($wikiinfo){
            //echo '<pre>';
            //print_r($wikiinfo);
            /*
            $wiki=new Wiki();
            $wiki->setId(new mongoId($wikiinfo['id']));
            $wiki->setTitle($wikiinfo['title']);
            $wiki->setSlug($wikiinfo['slug']);
            $wiki->setModel($wikiinfo['model']);
            $wiki->setDescription($wikiinfo['description']);
            $wiki->setCover($wikiinfo['cover']);
            $wiki->setScreens($wikiinfo['screens']);
            if ($wikiinfo['model'] == 'actor') {
                $wiki->setSex($wikiinfo['info']['sex']);
                $wiki->setBirthday($wikiinfo['info']['birthday']);
                $wiki->setBirthplace($wikiinfo['info']['birthplace']);
                $wiki->setOccupation($wikiinfo['info']['occupation']);
                $wiki->setZodiac($wikiinfo['info']['zodiac']);
                $wiki->setBloodType($wikiinfo['info']['bloodType']);
                $wiki->setNationality($wikiinfo['info']['nationality']);
                $wiki->setRegion($wikiinfo['info']['region']);
                $wiki->setHeight($wikiinfo['info']['height']);
                $wiki->setWeight($wikiinfo['info']['weight']);
                $wiki->setDebut($wikiinfo['info']['debut']);
            }else{
                $wiki->setDirector($wikiinfo['info']['director']);
                $wiki->setStarring($wikiinfo['info']['starring']);
                $wiki->setTags($wikiinfo['info']['tags']);
                $wiki->setCountry($wikiinfo['info']['country']);
                $wiki->setLanguage($wikiinfo['info']['language']);
                $wiki->setReleased($wikiinfo['info']['released']);
                $wiki->setLikeNum($wikiinfo['info']['like_num']);
                $wiki->setDislikeNum($wikiinfo['info']['dislike_num']);
                $wiki->setSource($wikiinfo['info']['source']);
            }
            $wiki->save();
            */
            //继续写入wiki_meta信息
            //$json='{"action":"GetWikiMetasGd","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wiki_id.'"}}';
            //$info = Common::post_json($url,$json);
    		//$resulta = json_decode($info,true);
            //$metas=$resulta['wikimetas'];
            //print_r($metas);
            /*
            foreach($metas as $meta){
                $wikimeta=new WikiMeta();
                $wikimeta->setWikiId($wikiinfo['id']);
                $wikimeta->setTitle($meta['title']);
                $wikimeta->setContent($meta['content']);
                $wikimeta->setHtmlCache($meta['html_cache']);
                $wikimeta->setMark($meta['mark']);
                $wikimeta->save();
            }
            */
            echo $wikiinfo['id'],'|',$wikiinfo['title'],'已导入<br/>';
        }else{
            echo $wiki_id,'未找到<br/>';
        } 
  }  

  public function executeGetWikisDay(sfWebRequest $request)
  {
        $mongo = $this->getMondongo();
        $url='http://www.5i.test.cedock.net/json';
        $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"}}';
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $wikis=$result['wiki']?$result['wiki']:array(); 
        echo '<pre>';      
        foreach($wikis as $wikiinfo){
               $wiki_exists = $mongo->getRepository("Wiki")->findOneById(new MongoId($wikiinfo['id']));
               if(!$wiki_exists){
                    //$this->importWiki($wikiinfo,$options);
                    $wiki=new Wiki();
                    $wiki->setId(new mongoId($wikiinfo['id']));
                    $wiki->setTitle($wikiinfo['title']);
                    $wiki->setSlug($wikiinfo['slug']);
                    $wiki->setModel($wikiinfo['model']);
                    $wiki->setDescription($wikiinfo['description']);
                    $wiki->setCover($wikiinfo['cover']);
                    $wiki->setScreens($wikiinfo['screens']);
                    if ($wikiinfo['model'] == 'actor') {
                        $wiki->setSex($wikiinfo['info']['sex']);
                        $wiki->setBirthday($wikiinfo['info']['birthday']);
                        $wiki->setBirthplace($wikiinfo['info']['birthplace']);
                        $wiki->setOccupation($wikiinfo['info']['occupation']);
                        $wiki->setZodiac($wikiinfo['info']['zodiac']);
                        $wiki->setBloodType($wikiinfo['info']['bloodType']);
                        $wiki->setNationality($wikiinfo['info']['nationality']);
                        $wiki->setRegion($wikiinfo['info']['region']);
                        $wiki->setHeight($wikiinfo['info']['height']);
                        $wiki->setWeight($wikiinfo['info']['weight']);
                        $wiki->setDebut($wikiinfo['info']['debut']);
                    }else{
                        $wiki->setDirector($wikiinfo['info']['director']);
                        $wiki->setStarring($wikiinfo['info']['starring']);
                        $wiki->setTags($wikiinfo['info']['tags']);
                        $wiki->setCountry($wikiinfo['info']['country']);
                        $wiki->setLanguage($wikiinfo['info']['language']);
                        $wiki->setReleased($wikiinfo['info']['released']);
                        $wiki->setLikeNum($wikiinfo['info']['like_num']);
                        $wiki->setDislikeNum($wikiinfo['info']['dislike_num']);
                        $wiki->setSource($wikiinfo['info']['source']);
                    }
                    $wiki->save();
                    
                    //继续写入wiki_meta信息
                    $json='{"action":"GetWikiMetasGd","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wikiinfo['id'].'"}}';
                    $info = Common::post_json($url,$json);
            		$resulta = json_decode($info,true);
                    $metas=$resulta['wikimetas'];
                    print_r($resulta);
                    foreach($metas as $meta){
                        $wikimeta=new WikiMeta();
                        $wikimeta->setWikiId($wikiinfo['id']);
                        $wikimeta->setTitle($meta['title']);
                        $wikimeta->setContent($meta['content']);
                        $wikimeta->setHtmlCache($meta['html_cache']);
                        $wikimeta->setMark($meta['mark']);
                        $wikimeta->save();
                    }                    
               }
        }   
  } 
  public function executeWikiTest(sfWebRequest $request)
  {
        $mongo = $this->getMondongo();
        $wiki = $mongo->getRepository("Wiki")->findOneById(new MongoId('50110df7069c0a3936000caf'));
        echo '<pre>';
        print_r($wiki);
        return sfView::NONE;
  } 
  public function executeWikiNew(sfWebRequest $request)
  {
        $model='actor';
        //$mongo = $this->getMondongo();
        $wiki=new Wiki();
        $wiki->setId(new mongoId('505685095570ecde3300077e'));
        $wiki->setTitle('测试标题');
        $wiki->setSlug('测试标题');
        $wiki->setModel('actor');
        $wiki->setDescription('这是描述');
        $wiki->setCover('');
        $wiki->setScreens();
        if ($model == 'actor') {
            $wiki->setSex('男');
            $wiki->setBirthday('1980-1-1');
            $wiki->setBirthplace('河北');
            $wiki->setOccupation('演员');
            $wiki->setZodiac('');
            $wiki->setBloodType('');
            $wiki->setNationality('');
            $wiki->setRegion('');
            $wiki->setHeight('178');
            $wiki->setWeight('68');
            $wiki->setDebut('');
        }else{
            $wiki->setDirector('导游');
            $wiki->setStarring('{"韩雪","丁子光"}');
            $wiki->setTags('{"电视剧","商战"}');
            $wiki->setCountry('中国');
            $wiki->setLanguage('汉语');
            $wiki->setReleased('2012');
            $wiki->setLikeNum(0);
            $wiki->setDislikeNum(0);
            $wiki->setSource('{"奇艺"}');
        }
        $wiki->save();
        echo '完成';
        return sfView::NONE;
  } 
  
  public function executeSpNew(sfWebRequest $request)
  {

        $wiki=new Sp();
        $wiki->setId(new mongoId('50110df7069c0a0000000111'));
        $wiki->setName('测试标题');
        $wiki->save();
        echo '完成';
        return sfView::NONE;
  }  
  public function executeWiki(sfWebRequest $request)
  {

        $mongo = $this->getMondongo();
        $url='http://www.epg.huan.tv/json';
        $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"}}';
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $wikis=$result['wiki']?$result['wiki']:array();     
        echo '<pre>';  
        foreach($wikis as $wikiinfo){
               print_r($wikiinfo);     
        }     
        echo '完成';
        return sfView::NONE;
  } 
  
  public function executeExportProgram(sfWebRequest $request)
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
        //$conn = ftp_connect("10.20.88.211") or die("FTP服务器连接失败"); 
        //ftp_login($conn,"huanwang","huanwang") or die("FTP服务器登陆失败");         
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
                                             'channel_logourl'=>$channel->getLogoUrl()
                                        ); 
                       /*                 
                       if($wiki){
                           //$nodeArray = $this->getWikiVideoSource($wiki, $k, $nodeArray,$date);
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
                                    $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
                                }            
                            }
                            $whether_mark = (gettype($type) =='array')?true:false;
                            $nodeArray[$date][$k]['info'] = array(
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
                            $nodeArray[$date][$k]['description'] = $wiki->getContent();
                            $cover = $wiki->getCover();
                            if ($cover) {
                                $nodeArray[$date][$k]['posters']['num'] = 3;
                                $nodeArray[$date][$k]['posters']['poster'][0] = array(
                                    "type" => "small",
                                    "size" => "120*160",
                                    "url" => thumb_url($cover, 120, 160),
                                );
                                $nodeArray[$date][$k]['posters']['poster'][1] = array(
                                    "type" => "big",
                                    "size" => "240*320",
                                    "url" => thumb_url($cover, 240, 320),
                                );
                                $nodeArray[$date][$k]['posters']['poster'][2] = array(
                                    "type" => "max",
                                    "size" => "1240*460",
                                    "url" => thumb_url($cover, 1240, 460),
                                );
                            }                               
                       }     
                       */         
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
              //ftp_put($conn,$target_file,$file,FTP_ASCII);
        }           
	    //ftp_close($conn);
	    echo "finished!";
        return sfView::NONE;
  }  
  

    
    
    public function executeExportProgramXML(sfWebRequest $request)
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
        $conn = ftp_connect("10.20.88.211") or die("FTP服务器连接失败"); 
        ftp_login($conn,"huanwang","huanwang") or die("FTP服务器登陆失败");         
        foreach($channels as $channel){
              $nodeArray=array(); 
              $file='log/tmp_'.iconv("UTF-8","GBK",$channel->getName()).'.xml';
              $target_file=iconv("UTF-8","GBK",$channel->getName()).'.xml';
              @unlink($file);
              for($i = 0; $i < 3 ; $i ++) {
                   $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));	
                   $programs = $program_repo->getDayProgramsWiki($channel->getCode(), $date);
                   
                   $nodeArray['ADI']=array();
                   //channel 信息
                   $nodeArray['ADI']['Metadata']['AMS'][DOM::ATTRIBUTES]=array(
                       'Product'=>'MOD',
                       'Version_Minor'=>'',
                       'Version_Major'=>'',
                       'Description'=>'',
                       'Creation_Date'=>'',
                       'Provider_ID'=>'',
                       'Asset_Name'=>$channel->getName(),
                       'Asset_ID'=>'',
                       'Asset_Class'=>'packages',
                       'Verb'=>'',
                   );
                   $nodeArray['ADI']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
                       'App'=>'MOD',
                       'Name'=>'Metadata_Spec_Version',
                       'Value'=>'CableLabsVOD 1.1'
                   );
                   $nodeArray['ADI']['Asset']['Metadata']['AMS'][DOM::ATTRIBUTES]=array(
                       'Product'=>'MOD',
                       'Version_Minor'=>'',
                       'Version_Major'=>'',
                       'Description'=>'',
                       'Creation_Date'=>'',
                       'Provider_ID'=>'',
                       'Asset_Name'=>$channel->getName(),
                       'Asset_ID'=>'',
                       'Asset_Class'=>'title',
                       'Verb'=>'',
                   );
                   $nodeArray['ADI']['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
                       'App'=>'MOD',
                       'Name'=>'Show_Type',
                       'Value'=>'channel',
                   );
                   $nodeArray['ADI']['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
                       'App'=>'MOD',
                       'Name'=>'Channel_Code',
                       'Value'=>'CCTV1',
                   );
                   $nodeArray['ADI']['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
                       'App'=>'MOD',
                       'Name'=>'Channel_Number',
                       'Value'=>'1',
                   );
                   $nodeArray['ADI']['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
                       'App'=>'MOD',
                       'Name'=>'Call_Sign',
                       'Value'=>$channel->getName(),
                   );
                   
                   $k=0;
                   //节目单
                   foreach($programs as $program){
                       $wiki = $program->getWiki();
                       /*$nodeArray[$date][$k]= array(
                                             'id'    => $program['wiki_id'],
                                             'title'=>$program['name'],
                                             'start_time'=>date("H:i",$program['start_time']->getTimestamp()),
                                             'end_time'=>date("H:i",$program['end_time']->getTimestamp()),
                                             'channel_code'=>$channel->getCode(),
                                             'channel_name'=>$channel->getName(),
                                        ); */
                       
                       $nodeArray['ADI']['Asset'][$k]['Asset']['Metadata']['AMS'][DOM::ATTRIBUTES]=array(
	                       'Product'=>'MOD',
	                       'Version_Minor'=>'',
	                       'Version_Major'=>'',
	                       'Description'=>'节目单',
	                       'Creation_Date'=>'',
	                       'Provider_ID'=>'',
	                       'Asset_Name'=>$channel->getName(),
	                       'Asset_ID'=>'',
	                       'Asset_Class'=>'schedule',
	                       'Verb'=>'',
	                   );
	                   $nodeArray['ADI']['Asset'][$k]['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Channel_ID',
	                       'Value'=>$channel->getName(),
	                   );
	                   $nodeArray['ADI']['Asset'][$k]['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Program_Name',
	                       'Value'=>$program['name'],
	                   );
	                   $nodeArray['ADI']['Asset'][$k]['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Start_Time',
	                       'Value'=>date("H:i",$program['start_time']->getTimestamp()),
	                   );
	                   $nodeArray['ADI']['Asset'][$k]['Asset']['Metadata']['App_Data'][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'End_Time',
	                       'Value'=>date("H:i",$program['end_time']->getTimestamp()),
	                   );
	                  
                                    
                       if($wiki){
                            $nodeArray = $this->getWikiVideoSourceXML($wiki, $k, $nodeArray,$date);                         
                       }   
                                 
                       $k++;            
                       
                   }
              }
              //$jiemu_json=json_encode($nodeArray);
              if (!empty($nodeArray)){
              	print_r($nodeArray);
              //$channelXML = DOM::arrayToXMLString($nodeArray,"response",null);
              /*
              $f = fopen($file, 'w');
              fwrite($f, $jiemu);
              fclose($f);
              */
              //file_put_contents($file,$channelXML);
              //ftp_put($conn,$target_file,$file,FTP_ASCII);
	          break;
              }
        }           
	    //ftp_close($conn);
	    //echo "finished!";
    
    }
    
    
      public function executeGetWikiTags(sfWebRequest $request)
      {
    
            $mongo = $this->getMondongo();
            $wikiRep = $mongo->getRepository('wiki');
            $tags=$wikiRep->getTagBySlug('战争不相信眼泪');
            $tags= array_splice($tags, 1);  //删除第一个元素，也就是把“电视剧”，“电影”等删除
            shuffle($tags);  //按随机顺序重新排列
            $result = $wikiRep->xun_search("tag:".$tags[0], $total, 0, 9,null,1);
            $total = count($result);
            if($result){
                foreach($result as $res) 
                {
                    $arr[] = $res->getTitle();
                }
            }   
            echo '<pre>';
            print_r($arr);
            return sfView::NONE;
      }
  public function executeEpgJson(sfWebRequest $request)
  {

        //$code=$options['code'];  
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
        
		$channels = Doctrine::getTable('Channel')->createQuery("c")
                        ->where('c.publish = 1')
                        ->andWhere('c.type = "cctv"')
                        ->orWhere('c.type = "tv"')
                        ->execute();
        $conn = @ftp_connect("10.20.88.211") or die("FTP服务器连接失败"); 
        @ftp_login($conn,"huanwang","huanwang") or die("FTP服务器登陆失败");   
        $conna = @ftp_connect("10.20.20.132") or die("FTP服务器连接失败"); 
        @ftp_login($conna,"wangyong","wangyong") or die("FTP服务器登陆失败");   
        foreach($channels as $channel){
            $nodeArray=array(); 
            $file='log/tmp_'.iconv("UTF-8","GBK",$channel->getName()).'.json';
            $target_file=iconv("UTF-8","GBK",$channel->getName()).'.json';
            @unlink($file);

            $date = date("Y-m-d");
            $programs = $program_repo->getDayProgramsWiki($channel->getCode(), $date);
            $nodeArray['channel']=array(
                'name'=>$channel->getName(),
                'code'=>$channel->getCode(),
                'logourl'=>$channel->getLogoUrl()
            );
            $nodeArray['total']=count($programs);
            $k=0;
            foreach($programs as $program){
               $wiki = $program->getWiki();
               $nodeArray['programs'][$k]= array(
                					'name' => $program['name'],
                					'date' => $program['date'],
                					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
                					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
                					'wiki_id' => $program['wiki_id'],
                					'wiki_cover' => $this->file_url($wiki['cover']),
                					'tags' => $wiki['tags']?$wiki['tags']:'',
                                ); 
                                
               if($wiki){
                    $nodeArray = $this->getWikiVideoSource($wiki, $k, $nodeArray);                         
               }   
                         
               $k++;            
               
            }

            $jiemu_json=json_encode($nodeArray);
            print_r($jiemu_json);
            
            /*
            $f = fopen($file, 'w');
            fwrite($f, $jiemu);
            fclose($f);
            */
            //file_put_contents($file,$jiemu_json);
            //@ftp_put($conn,$target_file,$file,FTP_ASCII);
            //@ftp_put($conna,$target_file,$file,FTP_ASCII);
            break;
        }           
	    @ftp_close($conn);
        @ftp_close($conna);
	    echo "finished!";
        return sfView::NONE;
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
            $nodeArray['programs'][$i]['poster'][0] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => $this->thumb_url($cover, 120, 160),
            );
            $nodeArray['programs'][$i]['poster'][1] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => $this->thumb_url($cover, 240, 320),
            );
            $nodeArray['programs'][$i]['poster'][2] = array(
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
     * @param <int> $height
     */
    public function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        
        return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }  
    
    function file_url($key = null)
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
      public function executeGetProgramByChannel(sfWebRequest $request)
      {
    
    		$mongo = $this->getMondongo();
            $channels = Doctrine::getTable('Channel')->getChannels();
            $programRes = $mongo->getRepository('program');
    		//$this->program_list = $programRes->getLiveProgramByTag('', $channels,10);
            $k=0;
            foreach($channels as $channel){
                echo $channel->getCode(),'<br/>';
                if($k>=3) break;
                $program=$programRes->getLiveProgramByChannel($channel->getCode());
                if($program){
                    echo '有','<br/>';
                    $program_list[] = $program;
                    $k++;
                }
            } 
            echo '<pre>';
            foreach($program_list as $programa){
                echo $programa->getName(),'<br/>';
            }
            
            return sfView::NONE;
      }
      public function executeGetLiveProgramByChannel(sfWebRequest $request)
      {
    
    		$mongo = $this->getMondongo();
            $channels = Doctrine::getTable('Channel')->getChannels();
            $programRes = $mongo->getRepository('program');
    		//$this->program_list = $programRes->getLiveProgramByTag('', $channels,10);
            $k=0;
            foreach($channels as $channel){
                if($k>=10) break;
                $program=$programRes->getLiveProgramByChannel($channel->getCode());
                if($program){
                     $arr_program[]= $program;
                     $k++;
                }
            }
            echo "<table border=1>";
            foreach($arr_program as $program){
                $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getStartTime()->format("Y-m-d H:i:s"));
                $plan = time() - strtotime($program->getStartTime()->format("Y-m-d H:i:s"));
                $width = round($plan/$all,2) * 100;
                echo '<tr>';
                echo '<td>',$program->getId(),'</td>';
                echo '<td>',$program->getChannelName(),'</td>';
                echo '<td>',$program->getName(),'</td>';
                echo '<td>',$program->getStartTime()->format("Y-m-d H:i:s"),'</td>';
                echo '<td>',$program->getEndTime()->format("Y-m-d H:i:s"),'</td>';
                echo '<td>',$program->getTime(),'</td>';
                echo '<td>',$all,'</td>';
                echo '<td>',$plan,'</td>';
                echo '<td>',$width,'</td>';
                echo '</tr>';
            }
            echo "</table>";
            return sfView::NONE;
      }
      
      public function executeAdiSave(sfWebRequest $request)
      {

            $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=EventFeedback&feedback_type=watch_start&uid=123&cid=111111111111111111111111";
            $contents=file_get_contents($url);
            if($contents){
                $arr_contents=json_decode($contents);
                echo "<pre>";
                print_r($arr_contents);
            }else{
                return $this->renderText(-1);
            }     
                
            return sfView::NONE;
      } 
      
      public function executeAdiTag(sfWebRequest $request)
      {
            $wikis=array();
            //$url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=8&uid=123&genre=".iconv("UTF-8","GBK",'电视剧');
            //$url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=123";
            $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=8&uid=123&genre=电视剧";
            $contents=file_get_contents($url);
            print_r($contents);
            
            if($contents){
                $arr_contents=json_decode($contents);
                foreach($arr_contents[3]->recommend as $value){
                    $wiki_id = $value->contid_id;  
                    $wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
                }
            }
            echo "<pre>";
            print_r($wikis);
            return sfView::NONE;
       }
}
