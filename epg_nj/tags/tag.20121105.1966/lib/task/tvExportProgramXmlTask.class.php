<?php

class tvExportProgramXmlTask extends sfMondongoTask
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
    $this->name             = 'ExportProgramXml';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ExportProgramXml|INFO] task does things.
Call it with:

  [php symfony tv:ExportProgramXml|INFO]
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
                   
        $conn = @ftp_connect("10.20.88.211") or die("FTP服务器连接失败"); 
        @ftp_login($conn,"huanwang","huanwang") or die("FTP服务器登陆失败");   
        $conna = @ftp_connect("10.20.20.132") or die("FTP服务器连接失败"); 
        @ftp_login($conna,"wangyong","wangyong") or die("FTP服务器登陆失败");         
        foreach($channels as $channel){
              $nodeArray=array(); 
              $file='log/tmp_'.iconv("UTF-8","GBK",$channel->getName()).'.xml';
              $target_file=iconv("UTF-8","GBK",$channel->getName()).'.xml';
              @unlink($file);

               $date = date("Y-m-d");	
               $programs = $program_repo->getDayProgramsWiki($channel->getCode(), $date);
               
               $nodeArray=array();
               //channel 信息
               $nodeArray['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                   'Product'=>'MOD',
                   'Version_Minor'=>'',
                   'Version_Major'=>'',
                   'Description'=>'',
                   'Creation_Date'=>$date,
                   'Provider_ID'=>'',
                   'Asset_Name'=>$channel->getName(),
                   'Asset_ID'=>'',
                   'Asset_Class'=>'packages',
                   'Verb'=>'',
               );
               $nodeArray['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
                   'App'=>'MOD',
                   'Name'=>'Metadata_Spec_Version',
                   'Value'=>'CableLabsVOD 1.1'
               );
               $nodeArray['Asset'][0]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                   'Product'=>'MOD',
                   'Version_Minor'=>'',
                   'Version_Major'=>'',
                   'Description'=>'',
                   'Creation_Date'=>$date,
                   'Provider_ID'=>'',
                   'Asset_Name'=>$channel->getName(),
                   'Asset_ID'=>'',
                   'Asset_Class'=>'title',
                   'Verb'=>'',
               );
               $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
                   'App'=>'MOD',
                   'Name'=>'Show_Type',
                   'Value'=>'channel',
               );
               $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
                   'App'=>'MOD',
                   'Name'=>'Channel_Code',
                   'Value'=>'CCTV1',
               );
               $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
                   'App'=>'MOD',
                   'Name'=>'Channel_Number',
                   'Value'=>'1',
               );
               $nodeArray['Asset'][0]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
                   'App'=>'MOD',
                   'Name'=>'Call_Sign',
                   'Value'=>$channel->getName(),
               );
               
               $k=0;
               //节目单
               
               foreach($programs as $program){

                   $wiki = $program->getWiki();
                   
                   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                       'Product'=>'MOD',
                       'Version_Minor'=>'',
                       'Version_Major'=>'',
                       'Description'=>'节目单',
                       'Creation_Date'=>$date,
                       'Provider_ID'=>'',
                       'Asset_Name'=>$channel->getName(),
                       'Asset_ID'=>'',
                       'Asset_Class'=>'schedule',
                       'Verb'=>'',
                   );
                   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
	                   'App'=>'MOD',
                       'Name'=>'Channel_ID',
                       'Value'=>$channel->getName(),
                   );
                   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
	                   'App'=>'MOD',
                       'Name'=>'Program_Name',
                       'Value'=>$program['name'],
                   );
                   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
	                   'App'=>'MOD',
                       'Name'=>'Start_Time',
                       'Value'=>date("H:i",$program['start_time']->getTimestamp()),
                   );
                   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
	                   'App'=>'MOD',
                       'Name'=>'End_Time',
                       'Value'=>date("H:i",$program['end_time']->getTimestamp()),
                   );
                  
                   if($wiki){
                   	    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Description',
	                       'Value'=>$wiki->getContent(),
	                   );
        
                        $nodeArray = $this->getWikiVideoSource($wiki, $k, $nodeArray,$date);                         
                   }         
                   $k++;            
                   
               }
              //$jiemu_json=json_encode($nodeArray);
              $channelXML = DOM::arrayToXMLString($nodeArray,'ADI',array(''=>''));
              
              /*
              $f = fopen($file, 'w');
              fwrite($f, $jiemu);
              fclose($f);
              */
              file_put_contents($file,$channelXML);
              @ftp_put($conn,$target_file,$file,FTP_ASCII);
              @ftp_put($conna,$target_file,$file,FTP_ASCII);
              //break;
        }           
	    @ftp_close($conn);
        @ftp_close($conna);
	    echo "finished!";
  }
  
    /*
     * wiki对象返回视频源数组    获取海报
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
        
        $cover = $wiki->getCover();
        if ($cover) {
        	
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
	                       'Product'=>'MOD',
	                       'Version_Minor'=>'',
	                       'Version_Major'=>'',
	                       'Description'=>'节目单',
	                       'Creation_Date'=>$date,
	                       'Provider_ID'=>'',
	                       'Asset_Name'=>$wiki->getName(),
	                       'Asset_ID'=>'',
	                       'Asset_Class'=>'poster',
	                       'Verb'=>'',
	                   );
        	
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Aspect_Ratio',
	                       'Value'=>'240*320',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Encoding_Profile',
	                       'Value'=>'bmp',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Content'][0][DOM::ATTRIBUTES]=array(
        		'Value'=>$this->thumb_url($cover, 240, 320),
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
        
        return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }  
}
