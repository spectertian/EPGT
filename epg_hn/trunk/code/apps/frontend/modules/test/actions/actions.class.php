<?php
/**
 * user actions.
 *
 * @package    epg
 * @subpackage test
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testActions extends sfActions
{
  
  public function executeIndex(sfWebRequest $request) 
	{
		//define("UCJSON_URL", 'http://pcuser.cedock.com/uc/json');
		//define("UCSJSON_URL", 'https://pcuser.cedock.com/uc/json');
		//define("UCJSON_URL", 'http://61.145.165.154:8080/uc/json');
		define("UCSJSON_URL", 'https://61.145.165.154:443/uc/json');

    $post_data = '{"action":"UserLogin","device":{},"user":{huanid:"1002658",pwd:"123456"}}';

		$opts = array('http'=>array('method'=>"POST",
									'header'=>"Accept-language: en\r\n",
									'content'=>$post_data));	
	
		$context = stream_context_create($opts);
		$bkjson = file_get_contents(UCJSON_URL, false, $context);
		echo $bkjson;
		exit;
  }
  
  /*
   * 临时测试
   * @param sfWebRequest $request
   * @author: lifucang
   */
  public function executeTestdoctrine(sfWebRequest $request) {
    
        /*
        $arrhot = Doctrine::getTable('UserLiving')->createQuery()
            ->select('channel,count(*) as hot')
            ->from('UserLiving')
            ->groupBy('channel');
            ->execute()
            ->toArray();     
        */   
        /*
        $arrhot = Doctrine_Query::create()
            ->select('channel,count(*) as hot')
            ->from('UserLiving')
            ->groupBy('channel');
        echo "查询语句：";    
        echo $arrhot->getSqlQuery();     
        $arrhot->fetchArray();     
        //$arrhot->execute();  
        echo "<pre>";
        print_r($arrhot); 
        
        
        $arrhot = Doctrine_Query::create()
            ->select('channel,count(*) as hot')
            ->from('UserLiving')
            ->groupBy('channel')
            //->fetchArray();
            ->execute();
        echo  getType($arrhot);
        //更新频道表热度
        foreach($arrhot as $value){
            echo $value['channel'].'('.$value['hot'].')';
        } 
        
        */
        /*
        //当前时间-更新时间>5分钟的删除lfc
        echo "<pre>";
        
        //date_default_timezone_set('PRC'); 
        $shijian= date('Y-m-d H:i:s');
        $sql="time_to_sec(timediff('".$shijian."', updated_at)) as shijian";
        $w = Doctrine_Query::create()
            ->select($sql)
            ->from('UserLiving')
            ->fetchArray();
        print_r($w);

        $q = Doctrine_Query::create() 
                 ->delete('UserLiving') 
                 ->where('time_to_sec(timediff(now(), updated_at))>?',300); 
        $numrows = $q->execute();
        //分组统计各频道当前人数，即热度
        $arrhot = Doctrine_Query::create()
            ->select('channel,count(*) as hot')
            ->from('UserLiving')
            ->groupBy('channel')
            ->fetchArray();
        //更新频道表热度
        
        print_r($arrhot);
        foreach($arrhot as $value){
            $q = Doctrine_Query::create() 
                 ->update('channel') 
                 ->set('hot=?',$value['hot']) 
                 ->where('code = ?', $value['channel'])
                 ->execute(); 
        } 
        echo '完成';
        */
        /*
        //当前时间-更新时间>5分钟的删除lfc
        $shijian= date('Y-m-d H:i:s');
        $sql="time_to_sec(timediff('".$shijian."', updated_at))>?";    
        $q = Doctrine_Query::create() 
                 ->delete('UserLiving') 
                 ->where($sql,300);
                 //->where('time_to_sec(timediff(now(), update_at))>?',300); 
        $numrows = $q->execute();
        //分组统计各频道当前人数，即热度
        $arrhot = Doctrine_Query::create()
            ->select('channel,count(*) as hot')
            ->from('UserLiving')
            ->groupBy('channel')
            ->fetchArray();
        //更新频道表热度
        foreach($arrhot as $value){
            $q = Doctrine_Query::create() 
                 ->update('channel') 
                 ->set('hot=?',$value['hot']) 
                 ->where('code = ?', $value['channel'])
                 ->execute(); 
        }        
        */
        $arrhot = Doctrine_Query::create()
            ->select('channel,count(*) as hot')
            ->from('UserLiving')
            ->where('isliving=?',1)  //只统计活动的
            ->groupBy('channel')
            ->fetchArray();
        //更新频道表热度
        foreach($arrhot as $value){
            echo $value['channel'].'('.$value['hot'].')';
            echo "<br/>";
        }        
        return sfView::NONE;
        
  } 
  
  /*
   * 临时测试
   * @param sfWebRequest $request
   * @author: lifucang
   */
  public function executeTestnum(sfWebRequest $request) {
        $num=Doctrine::getTable('UserLiving')->getTotalnum('cctv1');
        echo($num);
        return sfView::NONE;
  }   
  
 public function executeGetRecommendMedia() 
 {

	$size = 10;
    $sort = 'user_like_desc';
    $detail = false;

    $userId='1234';
    $url="http://118.194.161.67:8080/lct-server/api/media/user/$userId/recommendations";
    $url.="?size=$size&sort=$sort&detail=$detail";
    echo $url;
    echo "<br/>";
    
    $contents=Common::get_url_info("http://118.194.161.67:8080/lct-server/api/media/user/1234/recommendations?size=10&sort=user_like_desc&detail=");
    print_r($contents);
    return sfView::NONE;

 } 
  public function executeAbc(sfWebRequest $request) {
        $mogo = $this->getMondongo();
        $video = $mogo->getRepository("video");
			@$queryNul=array(
				'referer'=>'qiyi',
				'config' =>array(),
				'title'  =>'深宫谍影第10集',
			);	
			$resNul = @$video->find(array(
			'query'=>$queryNul
			));	 
			if($resNul)$resNul[0]->delete();
			print_R($resNul)  ;     
        return sfView::NONE;
  }   
  public function executeGetWikiInfoByChannel(sfWebRequest $request) {
        date_default_timezone_set('PRC'); 
        echo "<pre>";
        $channel_code="cctv1";
        $time=new MongoDate(strtotime("2012-5-13 15:22"));

        if(!$channel_code) return false;
        if(!$time) return false;
        //根据频道号和时间查询wiki_id
        $mongo = sfContext::getInstance()->getMondongo();
        $programRes =$mongo->getRepository('program');
        $query = array('query' => array( "wiki_id"=>array('$exists'=>true),"channel_code" => $channel_code,"start_time"=>array('$lt' => $time),"end_time"=>array('$gt' => $time)));
        $program=$programRes->findOne($query);
        if(!$program) return false;
        
        print_r($program);
        $wiki_id=$program->getWikiId();
        //获取wiki信息
        $wikiRes = $mongo->getRepository('wiki');
        $wikiInfo = $wikiRes->getWikiById($wiki_id);
        if(is_array($wikiInfo)) return false;
        
        $wiki = array();
        $wiki['alias'] = $wikiInfo->getAlias();
        $wiki['content'] = $wikiInfo->getContent();
        $wiki['country'] = $wikiInfo->getCountry();
        $wiki['created_at'] = $wikiInfo->getCreatedAt();
        $wiki['html_cache'] = $wikiInfo->getHtmlCache();
        $wiki['coverurl'] = $wikiInfo->getCoverUrl();
        $wiki['tags'] = $wikiInfo->getTags();
        $wiki['title'] = $wikiInfo->getTitle();
        $wiki['updated_at'] = $wikiInfo->getUpdatedAt();
        $wiki['director'] = $wikiInfo->getDirector(); //导演
        $wiki['writer'] = $wikiInfo->getWriter(); //编剧
        $wiki['starring'] = $wikiInfo->getStarring(); //主演
        $wiki['screen'] = $wikiInfo->getScreenshotUrls(); //剧照
        //$wiki['episodes'] = $this->getEpisodes($wikiInfo);
//        $wiki['episodes'] = $wikiInfo->getModel();
        print_r($wiki);
        return sfView::NONE;
  }  
  public function executeWn(sfWebRequest $request) {
        $mogo = $this->getMondongo();
        Reflection::export(new ReflectionClass($mogo));
        $mogodb = $mogo->getMongoDB();
       $resNul=$mogodb->find(array('distinct'=>'wiki','key'=>'director'));

			print_R($resNul)  ;  
			 
        return sfView::NONE;
  }   
  
	public function executeProgram(sfWebRequest $request)
	{
			$channel_code = $request->getParameter('code');
            $day=$request->getParameter('day',date('Y-m-d'));
			$starttime = date('Y-m-d 00:00:00',strtotime($day));
            $endtime = date('Y-m-d 23:59:59',strtotime($day));
            
	    	$mongo = $this->getMondongo();
	    	$ProgramRepository = $mongo->getRepository('Program');
	    	$programs = $ProgramRepository->getProgramsByCode($channel_code,$starttime,$endtime);
	    	
            $program_now = $ProgramRepository->getLiveProgramByCode($channel_code);  //当前正在播放的节目
	        $WikiRepository = $mongo->getRepository('Wiki');
	        foreach($programs as $key =>$program)
	        {
	            $wiki_info = $WikiRepository->findOneById(new MongoId($program['wiki_id']));
                
                $jialiang=0;
                if($program_now){
                    echo $program_now->getWikiId();
                    echo "|";
                    echo $program['wiki_id'];
                    echo "<br/>";
    	            if($program_now->getWikiId()==$program['wiki_id']){
    	               $jialiang=1;
    	            }else{
    	               $jialiang=0;
    	            }
                }
				$result[$key] = array(
					'name' => $program['name'],
					'time' => $program['time'],
//					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
//					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
//					'wiki_id' => $program['wiki_id'],
//					'wiki_cover' => file_url($wiki_info['cover']),
//					'tags' => $wiki_info['tags'],
					'wiki_slug' => $wiki_info['slug'],
                    'jialiang'=>$jialiang
	                );
			}
            echo "<pre>";
            print_r($result);
            return sfView::NONE;
			//return $this->renderText(json_encode($result));
		
	}
    
	public function executeChannel(sfWebRequest $request)
	{
	        $weeks=array('2012-07-09','2012-07-10','2012-07-11','2012-07-12','2012-07-13','2012-07-14','2012-07-15');
            $mongo = $this->getMondongo();
            $program_repo = $mongo->getRepository("Program");
            /*
			$channels = Doctrine::getTable('Channel')->createQuery("c")
                            ->where('c.publish = 1')
                            ->andWhere('c.type = "cctv" OR c.type = "tv"')
                            ->execute();   */         
			$channels = Doctrine::getTable('Channel')->createQuery("c")
                            ->where('c.publish = 1')
                            ->andWhere('c.type = "cctv"')
                            ->execute();
            $jiemu='';      
            echo "<pre>";  
            $br="\r\n";        
            foreach($channels as $channel){
                  $jiemu.=$channel->getName();
                  $jiemu.=$br;
                  foreach($weeks as $week){
                       //$starttime=$week.' 00:00:00';
                       //$endtime=$week.' 23:59:59';
                       //$programs = $program_repo->getPrograms($channel->getCode(),'',$starttime,$endtime);
                       
                       $programs = $program_repo->getDayProgramsWiki($channel->getCode(), $week);
                       $jiemu.=$week.$br;
                       foreach($programs as $program){
                           //$jiemu.=$program->getName().': '.(string)$program->getStartTime().','.(string)$program->getEndTime().chr(13);
                           //$jiemu.=$program['name'].': '.date("H:i",$program['start_time']->getTimestamp()).','.date("H:i",$program['end_time']->getTimestamp()).chr(13);
                           $jiemu.=$program['name'].': ';
                           if($program['start_time'])
                               $jiemu.=date("H:i",$program['start_time']->getTimestamp()).',';
                           else
                               $jiemu.=' ,';
                           if($program['end_time'])   
                               $jiemu.=date("H:i",$program['end_time']->getTimestamp()); 
                           $jiemu.=$br;      
                       }
                  }
                  
                  $file='/www/newepg/web_admin/uploads/'. iconv("UTF-8","GB2312",$channel->getName()).'.txt';
                  $f = fopen($file, 'w');
                  fwrite($f, $jiemu);
                  fclose($f);
                  
            }    
            
            //echo $jiemu;  
            
            return sfView::NONE;
		
	}  
	public function executeVideo()
	{echo Wiki::slugify('快乐星猫（一）23');
		        $mongo = $this->getMondongo();
        $wiki_repos = $mongo->getRepository('Wiki');
         $wiki = $wiki_repos->findOne(array('query' => array('slug' => new mongoRegex('/'.Wiki::slugify('快乐星猫（一）23').'/ims'), 'model' => 'teleplay')));
         print_r($wiki);
		 sfView::none;
	} 
	public function executeExcel()
	{

		$data = new Spreadsheet_Excel_Reader(); 
		$data->setOutputEncoding('utf-8');

		$data->read('/www/sss.xls');

		//error_reporting(E_ALL ^ E_NOTICE);
		//print_r($data->sheets);
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) //行循环
		{
			if($j!=1)
			{
				$video = new video();
				//for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) //列循环
				//{	
				$vod = $data->sheets[0]['cells'][$i][1];
				$title = $data->sheets[0]['cells'][$i][2];
				$tvn = $data->sheets[0]['cells'][$i][3];
				$director = $data->sheets[0]['cells'][$i][4];
				$tag = $data->sheets[0]['cells'][$i][5];
				$model = isset($data->sheets[0]['cells'][$i][6])?$data->sheets[0]['cells'][$i][6]:'';
				$array = array('videoId'=>$vod,'tvn'=>$tvn);
				$video->setTitle($title);
				$video->setModel($model);
				$video->setConfig($array);
				$video->setPublish(true);
				$video->save();
					
				//}
			}
		//以下注释的for循环打印excel表数据

			//echo "\n";
		
		}
		 sfView::none;
	}
	/*
{
   "_id": ObjectId("4ff53260ef868d7d0e000ee4"),
   "wiki_id": "4f7429c6cf63575255000844",
   "model": "teleplay",
   "title": "第26集预告片",
   "url": "http: \/\/www.iqiyi.com\/pianhua\/20120704\/3d2e49b7291bde65.html",
   "config": {
     "pid": "24611",
     "ptype": "2",
     "videoId": "d664b5f0a40e499481adbb4f72fa4c1d",
     "albumId": "192902",
     "tvId": "224271"
  },
   "referer": "qiyi",
   "publish": true,
   "video_playlist_id": "4ff53252ef868d7d0e000eca",
   "time": "07",
   "mark": 26,
   "created_at": ISODate("2012-07-05T06: 21: 20.0Z")
}	

	*/
}
