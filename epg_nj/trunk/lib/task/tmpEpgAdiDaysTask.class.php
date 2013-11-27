<?php
/**
 *  @todo  : 给cms发送ADI格式数据，一次发送7天的，或者指定天数的
 *  @author: lifucang  2013-03-29
 */
class tmpEpgAdiDaysTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('dates', null, sfCommandOption::PARAMETER_OPTIONAL, 'dates'),
      new sfCommandOption('channel', null, sfCommandOption::PARAMETER_OPTIONAL, 'channel'),
      new sfCommandOption('channelCode', null, sfCommandOption::PARAMETER_OPTIONAL, 'channelCode'),
      new sfCommandOption('nosend', null, sfCommandOption::PARAMETER_OPTIONAL, 'nosend'),
      new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update'),
      // add your own options here
    ));

    $this->namespace        = 'tmp';
    $this->name             = 'EpgAdiDays';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tmp:EpgAdiDays|INFO] task does things.
Call it with:

  [php symfony tmp:EpgAdiDays|INFO]
EOF;
    //symfony tmp:EpgAdiDays --dates=7 --channel=GuiZhouTV --channelCode=2aeb585ccaca9fa893b0bdfdbc098c7f --nosend=true --update=0  //nosend=true时不发送,update=0发送全部频道
  }

    protected function execute($arguments = array(), $options = array())
    { 
        $crontabStartTime=date("Y-m-d H:i:s");
        $arr_type=Common::englishGenres(); 
        $bkurl = sfConfig::get('app_cmsCenter_bkjson');      
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
		//$channels=$mongo->getRepository('SpService')->getServicesByTag();
        $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epg');
        $channel_num=0;
        if(isset($options['dates'])){
            $dates = $options['dates'];
        }else{
            $dates = 7;
        }
        //*****获取更新的频道开始
        $update = isset($options['update'])?$options['update']:1;
        $weeknow=date("w");
        if($weeknow==6){
            $sleepTime=60;
        }else{
            $sleepTime=10;
        }
        
        if($weeknow!=6&&$update){
            $hour = date('H');
            if($hour<=12){
                $start_time = date('Y-m-d 00:00:00');
                $end_time = date('Y-m-d 12:00:00');
                //$update = 0;  //12点前发全量
            }elseif($hour>12 && $hour<=16){
                $start_time = date('Y-m-d 11:00:00');
                $end_time = date('Y-m-d 16:00:00');
            }else{
                $start_time = date('Y-m-d 16:00:00');
                $end_time = date('Y-m-d 23:59:59');
            }
            $url = sfConfig::get('app_epghuan_url');
            $apikey = sfConfig::get('app_epghuan_apikey');
            $secretkey = sfConfig::get('app_epghuan_secretkey');
            $json_post='{"action": "GetChannelsUpdate","device": {"dnum": "123"},"user": {"userid": "123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param": {"start_time": "'.$start_time.'","end_time":"'.$end_time.'"}}';
            $getinfo = Common::post_json($url,$json_post);
            $channel_codes=array();
            if($getinfo){
                $result = json_decode($getinfo,true);
                if($result){
                    $channel_codes=$result['channel_code'];
                }
            }
        }
        $channel_fail=array();
        $channel_ok=array();
        //*****获取更新的频道结束
        foreach($channels as $channel){
            $channel_code=$channel->getChannelCode();
            if(!$channel_code) continue; //没有code，继续下一轮循环
            if(isset($options['channel'])){
                if($channel->getChannelId()!=$options['channel']) continue; //如果不是广东卫视，继续，只传递一个频道数据过去
            }
            if(isset($options['channelCode'])){
                if($channel_code!=$options['channelCode']) continue; //如果不是广东卫视，继续，只传递一个频道数据过去
            }
            //只获取更新频道的数据,周六除外
            if($weeknow!=6&&$update){
                if(!in_array($channel_code,$channel_codes)) continue; 
            }

            $file='tmp/ADITMP/ADI_'.iconv("UTF-8","GBK",$channel->getName()).'.xml';
            //#########################开始循环
            for($days = 0; $days < $dates ; $days ++) {        
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));
                //$programs = $program_repo->getDayProgramsWiki($channel_code, $date);
                $programs = $program_repo->getDayPrograms($channel_code, $date);
                if(!$programs) continue; //没有program，继续下一轮循环
                @unlink($file);
                
                $startTime = date("Y-m-d 00:00:00",strtotime($date));
                $endTime   = date("Y-m-d 23:59:59",strtotime($date));
                $nodeArray=array();
                //channel 信息
                $nodeArray['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                       'Verb'=>'',
                       'Asset_Class'=>'package',
                       'Asset_ID'=>$channel->getChannelId(),
                       'Asset_Name'=>$channel->getName(),
                       'Provider_ID'=>'ngcp',
                       'Creation_Date'=>$date,
                       'Description'=>$channel->getName(),
                       'Version_Major'=>'1',
                       'Version_Minor'=>'2',
                       'Product'=>'MOD',
                );
                $nodeArray['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
                       'Value'=>'CableLabsVOD 1.1',
                       'Name'=>'Metadata_Spec_Version',
                       'App'=>'MOD',
                );
                $nodeArray['Asset'][0]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                       'Verb'=>'',
                       'Asset_Class'=>'title',
                       'Asset_ID'=>$channel->getChannelId(),
                       'Asset_Name'=>$channel->getName(),
                       'Provider_ID'=>'ngcp',
                       'Creation_Date'=>$date,
                       'Description'=>$channel->getName(),
                       'Version_Major'=>'1',
                       'Version_Minor'=>'2',
                       'Product'=>'MOD',   
                );
                $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
                       'Value'=>'channel',
                       'Name'=>'Show_Type',
                       'App'=>'MOD',
                );
                $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
                       'Value'=>$channel->getChannelId(),
                       'Name'=>'Channel_Code',
                       'App'=>'MOD',
                );
                $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
                       'Value'=>str_pad((string)$channel->getLogicNumber(),3,"0",STR_PAD_LEFT),
                       'Name'=>'Channel_Number',
                       'App'=>'MOD',
                );
                $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
                       'Value'=>$channel->getName(),
                       'Name'=>'Call_Sign',
                       'App'=>'MOD',
                );
                $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
                       'Value'=>$startTime,
                       'Name'=>'Start_Time',
                       'App'=>'MOD',
                );
                $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
                       'Value'=>$endTime,
                       'Name'=>'End_Time',
                       'App'=>'MOD',
                );   
                $k=0;
                //节目单
                $date_program=date("ymd",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));
                $program_ida='EPG'.sprintf("%04d", $channel->getLogicNumber()).$date_program;
                foreach($programs as $program){
                    $wiki = $program->getWiki();        
                    //$program_id=$program_ida.sprintf("%07d",$k+1);   
                    srand((double)microtime()*1000000);
                    $program_id=$program_ida.sprintf("%07d",rand(0,9999999));                          
                    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                           'Product'=>'MOD',
                           'Version_Minor'=>'1',
                           'Version_Major'=>'2',
                           'Description'=>'节目单',
                           'Creation_Date'=>$date,
                           'Provider_ID'=>'ngcp',
                           'Asset_Name'=>$program->getName(),
                           'Asset_ID'=>$program_id,
                           'Asset_Class'=>'schedule',
                           'Verb'=>'',
                    );
                    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
    	                   'App'=>'MOD',
                           'Name'=>'Channel_ID',
                           'Value'=>$channel->getChannelId(),
                    );
                    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
    	                   'App'=>'MOD',
                           'Name'=>'Program_Name',
                           'Value'=>$program->getName(),
                    );
                    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
    	                   'App'=>'MOD',
                           'Name'=>'Start_Time',
                           'Value'=>date("Y-m-d H:i:s",$program['start_time']->getTimestamp()),
                    );
                    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
    	                   'App'=>'MOD',
                           'Name'=>'End_Time',
                           'Value'=>date("Y-m-d H:i:s",$program['end_time']->getTimestamp()),
                    );                
                    if($wiki){
                           if($wiki->getContent()){
                               $content=mb_strcut($wiki->getContent(),0,2048,'utf-8');
                           }else{
                               $content='空';
                           }
                       	   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Description',
                               'Value'=> $content,
    	                       //'Value'=> mb_substr($wiki->getContent(),0,100,'utf-8'),
    	                   );
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Director',
                               'Value'=>!$wiki->getDirector() ? '' : implode(',', $wiki->getDirector()),
                           );   
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][6][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Actors',
                               'Value'=>!$wiki->getStarring() ? '' : mb_strcut(implode(',', $wiki->getStarring()),0,255,'utf-8'),
                           );        
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][7][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Region',
                               'Value'=>!$wiki->getCountry() ? "" : $wiki->getCountry(),
                           ); 
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][8][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Subtitle_Language',
                               'Value'=>!$wiki->getLanguage() ? "" : $wiki->getLanguage(),
                           );   
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][9][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Released_Date',
                               'Value'=>!$wiki->getReleased() ? '' : $wiki->getReleased(),
                           ); 
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][10][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Original_Source',
                               'Value'=>'',
                           );   
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][11][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Prefer_Source',
                               'Value'=>'',
                           );  
                           //写入wiki标签   
                           $tagnum=12; 
                           $model=$wiki->getModel();
                           $arr_tag=array('film'=>'Movie','teleplay'=>'Series','actor'=>'other','television'=>'other','basketball_player'=>'other');
                           if($wiki->getTags()){
                               foreach($wiki->getTags() as $value){
                                   if($arr_type[$value]!=''){
                                       if($model=='film'){
                                           $tagvalue='Movie/'.$arr_type[$value];
                                       }elseif($model=='teleplay'){
                                           $tagvalue='Series/'.$arr_type[$value];
                                       }else{
                                           $tagvalue= $arr_type[$value];
                                       }
                                       $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][$tagnum][DOM::ATTRIBUTES]=array(
                                           'App'=>'MOD',
                                           'Name'=>'Genre',
                                           'Value'=>$tagvalue,
                                       );   
                                       $tagnum++;
                                   }
                               }
                               if($tagnum==12){
                                   $tagvalue=$arr_tag[$model];
                                   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][12][DOM::ATTRIBUTES]=array(
                                       'App'=>'MOD',
                                       'Name'=>'Genre',
                                       'Value'=>$tagvalue,
                                   );   
                               }    
                           }else{
                               $tagvalue=$arr_tag[$model];
                               $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][12][DOM::ATTRIBUTES]=array(
                                   'App'=>'MOD',
                                   'Name'=>'Genre',
                                   'Value'=>$tagvalue,
                               );   
                           }       
                           $nodeArray = $this->getWikiVideoSource($wiki, $k, $nodeArray,$date,$program);                         
                    }else{
                       	   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Description',
    	                       'Value'=>'空',
    	                   );
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Genre',
                               'Value'=>'other',
                           );
                    }
                    $k++;
                    //break;
                }
                $channelXML = DOM::arrayToXMLString($nodeArray,'ADI',array(''=>''));
                file_put_contents($file,$channelXML);
                
                if(!isset($options['nosend'])){
                    $opts = array('http'=>array('method'=>"POST",
                                                //'header'=>"Accept-language: en\r\n",
                                                'header'=>"Content-Type:text/html\r\n",
                                                'timeout' => 5,
                                                'content'=> $channelXML));	
                    $context = stream_context_create($opts);
                    $bkjson = @file_get_contents("http://172.31.183.230:8080/icms/content?action=adi11&systemId=epg", false, $context);
                    if(!$bkjson){
                        echo $channel->getName()." 导入失败";
                        $channel_fail[]=$channel->getName();
                    }else{
                        $channel_ok[]=$channel->getName();
                    }
                    sleep($sleepTime);  //周六停40s,其他时间停10s
                }else{
                    echo "no send\n";
                }
            }
            //#########################结束循环
            //break;  
            $channel_num++;  
            sleep($sleepTime);

        }        
        echo date("Y-m-d H:i:s"),'---',"channel:$channel_num finished!\n";
        if(count($channel_fail)>0){
            $channelFalis=implode(',',$channel_fail);
            $content="dates:$dates---channel:".$channel_num.'---Fail:'.$channelFalis;
        }else{
            $content="dates:$dates---channel:".$channel_num;
        }
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('tmpEpgAdiDays');
        $crontabLog->setContent($content);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save(); 
        
        if($dates==1){
            $epgDate=date('Y-m-d');
        }else{
            $date1 = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$dates,date("Y")));
            $epgDate=date('Y-m-d').'-'.$date1;
        }
        $epgLog=new EpgLog();
        $epgLog->setTo('cms');
        $epgLog->setChannels($channel_ok);
        $epgLog->setDate($epgDate);
        $epgLog->save();
        /*
        if(!isset($options['nosend'])){
            //给cms发送结束标志
            $this->postCallBack('END'); 
        } 
        */
    }
  
    /*
     * wiki对象获取海报
     * @editor lifucang
     */
     private function getWikiVideoSource($wiki,$i,$nodeArray,$date,$program)
     {
        $cover = $wiki->getCover();
        if ($cover) {
            //$cover_id=rtrim($cover,'.jpg');
            $cover_arr=explode('.',$cover);
            $cover_id=$cover_arr[0];
            $cover_standard=array(
                array(96,128),
                array(120,160),
                array(183,104),
                array(164,228),
                array(236,326),
                array(242,336)
            );
            $k=0;
            foreach($cover_standard as $value){
                $width = $value[0];
                $height = $value[1];
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
    	                       'Product'=>'MOD',
    	                       'Version_Minor'=>'1',
    	                       'Version_Major'=>'2',
    	                       'Description'=>$program->getName().'海报',
    	                       'Creation_Date'=>$date,
    	                       'Provider_ID'=>'ngcp',
    	                       'Asset_Name'=>$program->getName().'海报',
    	                       'Asset_ID'=>str_pad($cover_id.$width.$height,20,"0",STR_PAD_RIGHT),
    	                       'Asset_Class'=>'poster',
    	                       'Verb'=>'',
    	                   );
            	
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Image_Aspect_Ratio',
    	                       'Value'=>$width.'*'.$height,
    	                   );
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Content_File_Size',
    	                       'Value'=>'1000',
    	                   );
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Content_Check_Sum',
    	                       'Value'=>'11111111111111111111111111111111',
    	                   );
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Color_Type',
    	                       'Value'=>'RGB',
    	                   );
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Image_Encoding_Profile',
    	                       'Value'=>'jpg',
    	                   );
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Horizontal_Pixels',
    	                       'Value'=>$width,
    	                   );
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Metadata'][0]['App_Data'][6][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Vertical_Pixels',
    	                       'Value'=>$height,
    	                   );
            	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][$k]['Content'][0][DOM::ATTRIBUTES]=array(
            		'Value'=>$this->thumb_url($cover, $width, $height),
            	);
                $k++;
            }     
        }
        return $nodeArray;
     }  
    private function postCallBack($data) {
        $opts = array('http'=>array('method'=>"POST",
                                    'header'=>"Content-Type:text/html\r\n",
                                    'content'=> $data));	
        $context = stream_context_create($opts);
        $bkurl = sfConfig::get('app_cmsCenter_bkjson');
        $bkjson = @file_get_contents("$bkurl?action=adi11&systemId=epg", false, $context);
        return $bkjson;
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
     */
    private function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        
        return sprintf('http://172.31.139.17:81/thumb/'.'%s/%s/%s', $width, $height, $key);
    }  
}
