<?php

class tvEpgAdiWeekTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'EpgAdiWeek';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:EpgAdiWeek|INFO] task does things.
Call it with:

[php symfony tv:EpgAdiWeek|INFO]
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
            '综合'=>'News',
            '其他'=>'Other',
        );         
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
		$channels=$mongo->getRepository('SpService')->getServicesByTag();
        foreach($channels as $channel){
            if(!$channel->getChannelCode()) continue; //没有code，继续下一轮循环
            $file='tmp/ADI/ADI_'.iconv("UTF-8","GBK",$channel->getName()).'.xml';
            $target_file=iconv("UTF-8","GBK",$channel->getName()).'.xml';            
            for($i = 0; $i < 3 ; $i ++) {
                @unlink($file);                
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));
                $programs = $program_repo->getDayProgramsWiki($channel->getChannelCode(), $date);
                
                $nodeArray=array();
                //channel 信息
                $nodeArray['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                       'Verb'=>'',
                       'Asset_Class'=>'package',
                       'Asset_ID'=>str_pad((string)$channel->getLogicNumber(),22,"0",STR_PAD_LEFT),
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
                       'Asset_ID'=>str_pad((string)$channel->getLogicNumber(),22,"0",STR_PAD_LEFT),
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
                       'Value'=>$channel->getChannelCode(),
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
                   
                $k=0;
                //节目单
                   
                foreach($programs as $program){
    
                    $wiki = $program->getWiki();     
                    $category_code=array();
                    foreach($wiki->getTags() as $value){
                        if($arr_type[$value]!='')
                            $category_code[]=$arr_type[$value];
                    }
                    $type=!$category_code? '' : implode(',', $category_code);                                   
                    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                           'Product'=>'MOD',
                           'Version_Minor'=>'1',
                           'Version_Major'=>'2',
                           'Description'=>'节目单',
                           'Creation_Date'=>$date,
                           'Provider_ID'=>'ngcp',
                           'Asset_Name'=>$program->getName(),
                           'Asset_ID'=>mb_strcut($program->getId(),2,24,'utf-8'),
                           'Asset_Class'=>'schedule',
                           'Verb'=>'',
                    );
                    $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
    	                   'App'=>'MOD',
                           'Name'=>'Channel_ID',
                           'Value'=>str_pad((string)$channel->getLogicNumber(),22,"0",STR_PAD_LEFT),
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
                       	   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Description',
    	                       'Value'=> mb_substr($wiki->getContent(),0,100,'utf-8'),
    	                   );
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Director',
                               'Value'=>!$wiki->getDirector() ? '' : implode(',', $wiki->getDirector()),
                           );   
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][6][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Actors',
                               'Value'=>!$wiki->getStarring() ? '' : implode(',', $wiki->getStarring()),
                           );    
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][7][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Genre',
                               'Value'=>$type,
                           );   
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][8][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Region',
                               'Value'=>!$wiki->getCountry() ? "" : $wiki->getCountry(),
                           ); 
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][9][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Subtitle_Language',
                               'Value'=>!$wiki->getLanguage() ? "" : $wiki->getLanguage(),
                           );   
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][10][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Released_Date',
                               'Value'=>!$wiki->getReleased() ? '' : $wiki->getReleased(),
                           ); 
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][11][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Original_Source',
                               'Value'=>'',
                           );   
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][12][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Prefer_Source',
                               'Value'=>'',
                           );                 
                           $nodeArray = $this->getWikiVideoSource($wiki, $k, $nodeArray,$date,$program);                         
                    }else{
                       	   $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
    		                   'App'=>'MOD',
    	                       'Name'=>'Description',
    	                       'Value'=>'',
    	                   );
                           /*
                           $nodeArray['Asset'][0]['Asset'][$k]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
                               'App'=>'MOD',
                               'Name'=>'Genre',
                               'Value'=>'',
                           );
                          */
                    }
                    $k++;
                    //break;
                }
                
                $channelXML = DOM::arrayToXMLString($nodeArray,'ADI',array(''=>''));
                file_put_contents($file,$channelXML);

                $opts = array('http'=>array('method'=>"POST",
                                            //'header'=>"Accept-language: en\r\n",
                                            'header'=>"Content-Type:text/html\r\n",
                                            'content'=> $channelXML));	
                $context = stream_context_create($opts);
                $bkjson = @file_get_contents("http://172.31.183.10:8080/icms/content?action=adi11&systemId=epg", false, $context);
                if(!$bkjson)
                    echo $channel->getName()." 导入失败";

                //break;    
            }    

        }        
        echo "finished!";
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
            //第一张海报
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
	                       'Product'=>'MOD',
	                       'Version_Minor'=>'1',
	                       'Version_Major'=>'2',
	                       'Description'=>$program->getName().'海报',
	                       'Creation_Date'=>$date,
	                       'Provider_ID'=>'ngcp',
	                       'Asset_Name'=>$program->getName().'海报',
	                       'Asset_ID'=>str_pad($cover_id.'120160',22,"0",STR_PAD_LEFT),
	                       'Asset_Class'=>'poster',
	                       'Verb'=>'',
	                   );
        	
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Aspect_Ratio',
	                       'Value'=>'120*160',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Content_File_Size',
	                       'Value'=>'1000',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Content_Check_Sum',
	                       'Value'=>'11111111111111111111111111111111',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Color_Type',
	                       'Value'=>'RGB',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Encoding_Profile',
	                       'Value'=>'jpg',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Horizontal_Pixels',
	                       'Value'=>'120',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Metadata'][0]['App_Data'][6][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Vertical_Pixels',
	                       'Value'=>'160',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][0]['Content'][0][DOM::ATTRIBUTES]=array(
        		'Value'=>$this->thumb_url($cover, 120, 160),
        	);
            
            //第二张海报
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
	                       'Product'=>'MOD',
	                       'Version_Minor'=>'1',
	                       'Version_Major'=>'2',
	                       'Description'=>$program->getName().'海报',
	                       'Creation_Date'=>$date,
	                       'Provider_ID'=>'ngcp',
	                       'Asset_Name'=>$program->getName().'海报',
	                       'Asset_ID'=>str_pad($cover_id.'240320',22,"0",STR_PAD_LEFT),
	                       'Asset_Class'=>'poster',
	                       'Verb'=>'',
	                   );
        	
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Aspect_Ratio',
	                       'Value'=>'240*320',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Content_File_Size',
	                       'Value'=>'1000',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Content_Check_Sum',
	                       'Value'=>'11111111111111111111111111111111',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Color_Type',
	                       'Value'=>'RGB',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Encoding_Profile',
	                       'Value'=>'jpg',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Horizontal_Pixels',
	                       'Value'=>'240',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Metadata'][0]['App_Data'][6][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Vertical_Pixels',
	                       'Value'=>'320',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][1]['Content'][0][DOM::ATTRIBUTES]=array(
        		'Value'=>$this->thumb_url($cover, 240, 320),
        	);
            
            
            //第三张海报
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
	                       'Product'=>'MOD',
	                       'Version_Minor'=>'1',
	                       'Version_Major'=>'2',
	                       'Description'=>$program->getName().'海报',
	                       'Creation_Date'=>$date,
	                       'Provider_ID'=>'ngcp',
	                       'Asset_Name'=>$program->getName().'海报',
	                       'Asset_ID'=>str_pad($cover_id.'1240460',22,"0",STR_PAD_LEFT),
	                       'Asset_Class'=>'poster',
	                       'Verb'=>'',
	                   );
        	
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Aspect_Ratio',
	                       'Value'=>'1240*460',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Content_File_Size',
	                       'Value'=>'1000',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Content_Check_Sum',
	                       'Value'=>'11111111111111111111111111111111',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Color_Type',
	                       'Value'=>'RGB',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Image_Encoding_Profile',
	                       'Value'=>'jpg',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Horizontal_Pixels',
	                       'Value'=>'1240',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Metadata'][0]['App_Data'][6][DOM::ATTRIBUTES]=array(
		                   'App'=>'MOD',
	                       'Name'=>'Vertical_Pixels',
	                       'Value'=>'460',
	                   );
        	$nodeArray['Asset'][0]['Asset'][$i]['Asset'][2]['Content'][0][DOM::ATTRIBUTES]=array(
        		'Value'=>$this->thumb_url($cover, 1240, 460),
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
     */
    private function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        
        return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }  
}
