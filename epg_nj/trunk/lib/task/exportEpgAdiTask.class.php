<?php
/**
 *  @todo  :  导出ADI节目数据给cms
 *  @author:  lifucang 2013-09-11
 *  @example: symfony export:EpgAdi --days=9 --channel=GuiZhouTV --nosend=true //nosend=true时不发送
 *  @example: symfony export:EpgAdi --date=2013-07-31
 *  @example: symfony export:EpgAdi --tomorrow=1
 *  @example: symfony export:EpgAdi --update=1 //只发更新的
 */
class exportEpgAdiTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('days', null, sfCommandOption::PARAMETER_OPTIONAL, 'days'),
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_OPTIONAL, 'date'),
      new sfCommandOption('channel', null, sfCommandOption::PARAMETER_OPTIONAL, 'channel'),
      new sfCommandOption('tomorrow', null, sfCommandOption::PARAMETER_OPTIONAL, 'tomorrow'),  
      new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update',0),
      new sfCommandOption('nosend', null, sfCommandOption::PARAMETER_OPTIONAL, 'nosend'),
    ));

    $this->namespace        = 'export';
    $this->name             = 'EpgAdi';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [export:EpgAdi|INFO] task does things.
Call it with:

  [php symfony export:EpgAdi|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('exportEpgAdi');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        
        //获取更新的频道
        $update = isset($options['update'])?$options['update']:0;
        $channel_codes=array();
        if($update){
            $hour = date('H');
            if($hour<=12){
                $start_time = date('Y-m-d 00:00:00');
                $end_time = date('Y-m-d 12:00:00');
            }elseif($hour>12 && $hour<=16){
                $start_time = date('Y-m-d 12:00:00');
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
            if($getinfo){
                $result = json_decode($getinfo,true);
                if($result){
                    $channel_codes=$result['channel_code'];
                }
            }
        }
        //连接ftp
        $ftpIp = sfConfig::get('app_commonFtp_host');
        $ftpPort = sfConfig::get('app_commonFtp_port');
        $ftpUser = sfConfig::get('app_commonFtp_username');
        $ftpPass = sfConfig::get('app_commonFtp_password');
        if(!isset($options['nosend'])){
            $config = array(
            			'hostname' => $ftpIp,
            			'username' => $ftpUser,
            			'password' => $ftpPass,
            			'port' => $ftpPort
            				);
            $ftp = new Ftp();
            $ftp->connect($config);
        }           

        $date = date("Y-m-d");	   
        $channelNum=0;      //记录发送的频道数
        $daysNum=0;         //记录发送的天数或者具体日期
        $path = 'adi/'.date("Ymd").'/'.date('H');
        if(!isset($options['nosend'])){
            $ftp ->mkdirs($path,775);
        }
        
        $arr_type=Common::englishGenres(); 
        //循环频道发送节目数据
        $program_repo = $mongo->getRepository("Program");      
        $channels=$mongo->getRepository('SpService')->getServicesByTag();           
        //$channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epgbak');   //只发送回看监测中的频道  
        foreach($channels as $channel){
            $channelCode=$channel->getChannelCode();
            $channelName=$channel->getName();
            $channelInfo =array(
                'code' => $channelCode,
                'name' => $channelName,
                'id' => $channel->getChannelId(),
                'number' => $channel->getLogicNumber(),
                'num' => $channel->getChannelNum()
            );
            if(!$channelCode) continue; //没有code，继续下一轮循环
            if(isset($options['channel'])){
                if($channel->getChannelId()!=$options['channel']) continue; //如果设置的channelId，继续，只传递一个频道数据过去
            }
            //只发送$channel_codes里更新的频道
            if($update&&!in_array($channelCode,$channel_codes)) {
                continue;
            }
            if(isset($options['days'])){
                $days=$options['days'];
                $daysNum=$days;
                for($i = 0; $i < $days ; $i ++) {
                    $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));
                    $dateName = date("Ymd",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));
                    $channelXML=$this->getPrograms($date,$channelInfo,$program_repo,$arr_type);
                    if(!$channelXML) continue; //没有节目数据，继续下一轮循环
                    
                    $file='tmp/ADI/ADI_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.xml';
                    $target_file='ADI_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.xml';        
                    file_put_contents($file,$channelXML);
                    if(!isset($options['nosend'])){
                        $ftp->chgdir($path);
                        $ftp->upload($file,$target_file,'ascii');
                        @unlink($file);
                    }else{
                        echo "no send\n";
                    }
                }
            }else{
                if($options['tomorrow']==1){
                    $date = date("Y-m-d",strtotime("+1 day"));
                }elseif(isset($options['date'])){
                    $date = $options['date'];
                }
                $dateName = date("Ymd",strtotime($date));
                $daysNum=$date;
                $channelXML=$this->getPrograms($date,$channelInfo,$program_repo,$arr_type);
                if(!$channelXML) continue; //没有节目数据，继续下一轮循环
                
                $file='tmp/ADI/ADI_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.xml';
                $target_file='ADI_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.xml';        
                file_put_contents($file,$channelXML);
                if(!isset($options['nosend'])){
                    $ftp->chgdir($path);
                    $ftp->upload($file,$target_file,'ascii'); 
                    @unlink($file);
                }else{
                    echo "no send\n";
                }
            }
            $channelNum++; 
            //echo iconv('UTF-8','GBK',$channelName),"\n";
        }           
        if(!isset($options['nosend'])){
            $ftp->close();
        }
	    echo date("Y-m-d H:i:s"),"---finished!";
        
        $content="date:$daysNum---channel:".$channelNum;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
    }
    
    private function getPrograms($date,$channelInfo,&$program_repo,$arr_type){
        //$mongo = $this->getMondongo();
        //$program_repo = $mongo->getRepository("Program");       
        $programs = $program_repo->getDayPrograms($channelInfo['code'], $date);
        if(!$programs) return null; 
        
        $startTime = date("Y-m-d 00:00:00",strtotime($date));
        $endTime   = date("Y-m-d 23:59:59",strtotime($date));
        $nodeArray=array();
        //channel 信息
        $nodeArray['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
               'Verb'=>'',
               'Asset_Class'=>'package',
               'Asset_ID'=>$channelInfo['id'],
               'Asset_Name'=>$channelInfo['name'],
               'Provider_ID'=>'ngcp',
               'Creation_Date'=>$date,
               'Description'=>$channelInfo['name'],
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
               'Asset_ID'=>$channelInfo['id'],
               'Asset_Name'=>$channelInfo['name'],
               'Provider_ID'=>'ngcp',
               'Creation_Date'=>$date,
               'Description'=>$channelInfo['name'],
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
               'Value'=>$channelInfo['id'],
               'Name'=>'Channel_Code',
               'App'=>'MOD',
        );
        $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
               'Value'=>str_pad((string)$channelInfo['number'],3,"0",STR_PAD_LEFT),
               'Name'=>'Channel_Number',
               'App'=>'MOD',
        );
        $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
               'Value'=>$channelInfo['name'],
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
        //$date_program=date("ymd",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));
        //$program_ida='EPG'.sprintf("%04d", $channelInfo['number']).$date_program;
        $program_ida='EPG_'.$channelInfo['num'].'20';
        foreach($programs as $program){
            $wiki = $program->getWiki();        
            //srand((double)microtime()*1000000);
            //$program_id=$program_ida.sprintf("%07d",rand(0,9999999));                     
            $program_id = $program_ida.date("ymdHis",$program['start_time']->getTimestamp());          
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
                   'Value'=>$channelInfo['id'],
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
                           if(isset($arr_type[$value])){
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
                   $cover = $wiki->getCover();   
                   $programName=$program->getName();
                   if($cover){
                       $nodeArray = $this->getWikiCover($cover, $k, $nodeArray,$date,$programName);         
                   }                
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
        }
        try{
            $channelXML = DOM::arrayToXMLString($nodeArray,'ADI',array(''=>''));
        }catch(Exception $e){
            return null; 
        }
        return $channelXML;
    }    
    /*
     * wiki对象获取海报
     * @editor lifucang
     */
    private function getWikiCover($cover,$i,$nodeArray,$date,$programName)
    {
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
	                       'Description'=>$programName.'海报',
	                       'Creation_Date'=>$date,
	                       'Provider_ID'=>'ngcp',
	                       'Asset_Name'=>$programName.'海报',
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
        return $nodeArray;
    }  
    /*
     * 获取动态缩略图
     */
    private function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        
        return sprintf('http://172.31.139.17:81/thumb/'.'%s/%s/%s', $width, $height, $key);
    }   
}
