<?php

class tvContentImportTask extends sfMondongoTask
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
        $this->name             = 'ContentImport';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:ContentImport|INFO] task does things.
Call it with:

[php symfony tv:ContentImport|INFO]
EOF;

        $this->acceptTypes = array("program","series");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $mongo = $this->getMondongo();
        $inject_repo = $mongo->getRepository("ContentInject"); 
        $import_repo = $mongo->getRepository("ContentImport");      
		$wiki_repository = $mongo->getRepository('wiki');
        $i = 0;
        $del_num=0;
        $update_num=0;
        $add_num=0;
        $error_num=0;
        $type_num=0;
        $execute_num=0;
        $showType_num=0;
        $inject_count = $inject_repo->count(array("state"=>0));
        echo $inject_count,"\n"; 
        while ($i < $inject_count) 
        {
            $injects = $inject_repo->find(array("query"=>array("state"=>0),"limit" => 100));
            
            if(!$injects){  
                echo "finished!";                       
            }else{
                foreach($injects as $inject) {
                    $execute_num++;
                    if($content = @simplexml_load_string(trim($inject->getContent()))) {
                    
                        $adi_md = $this->getMetadata($content->Metadata);
                        $asset_md = $this->getMetadata($content->Asset->Metadata);
                        if(!isset($asset_md['Show_Type'])) {
                            $inject->setState(-1);
                            $inject->save();
                            $showType_num++; 
                            continue;
                        }
                        if(in_array($asset_md['Show_Type'],$this->acceptTypes)) {
                            //$wiki_id = getWikiIdByAssetId($asset_id);                
    
                        	$ContentImport = $import_repo->findOne(array("query"=>array("from_id"=>$adi_md['Asset_ID'],"from"=>$inject->getFrom())));
                        	if ($ContentImport){
                        	    if(strtolower($adi_md['Verb'])=='delete'){
                        	        $ContentImport -> delete(); 
                                    echo iconv("utf-8","gbk",$adi_md['Asset_ID'].'删除'),"\n"; 
                                    $del_num++;
                        	    }else{
        	                        $ContentImport -> setInjectId($inject->getId());
        	                        //$ContentImport -> setFrom($inject->getFrom());
        	                        //$ContentImport -> setFromId($adi_md['Asset_ID']);
        	                        $ContentImport -> setFromTitle($adi_md['Asset_Name']);
        	                        $ContentImport -> setProviderId($adi_md['Provider_ID']);
        	                        $ContentImport -> setFromType($asset_md['Show_Type']);
        	                        $ContentImport -> setState(0);
        	                        $title = $this->getSubTitle($adi_md['Asset_Name']);
        	                        $wiki = $wiki_repository->getWikiByTitle($title);
        	                        if($wiki){
        	                            $ContentImport->setWikiId((string)$wiki->getId());
        	                            //echo iconv("utf-8","gbk",$title),"\n"; 
        	                        }               
        	                        $ContentImport -> save(); 
                                    echo iconv("utf-8","gbk",$adi_md['Asset_ID'].'更新'),"\n"; 
                                    $update_num++;
                        	    }
                        	}else {
    	                    	$ContentImport = new ContentImport();
    	                        $ContentImport -> setInjectId($inject->getId());
    	                        $ContentImport -> setFrom($inject->getFrom());
    	                        $ContentImport -> setFromId($adi_md['Asset_ID']);
    	                        $ContentImport -> setFromTitle($adi_md['Asset_Name']);
    	                        $ContentImport -> setProviderId($adi_md['Provider_ID']);
    	                        $ContentImport -> setFromType($asset_md['Show_Type']);
    	                        $ContentImport -> setState(0);
    	                        $title = $this->getSubTitle($adi_md['Asset_Name']);
    	                        $wiki = $wiki_repository->getWikiByTitle($title);
    	                        if($wiki){
    	                            $ContentImport->setWikiId((string)$wiki->getId());
    	                            //echo iconv("utf-8","gbk",$title),"\n"; 
    	                        }               
    	                        $ContentImport -> save();
                                echo iconv("utf-8","gbk",$adi_md['Asset_ID'].'保存'),"\n"; 
                                $add_num++;
                        	}
                            
                            $backxmlstring = $this->getBackXmlString($adi_md['Asset_ID'], $ContentImport->getID(), $asset_md['Show_Type'], 1, ''); 
                            $this->postCallBack($backxmlstring);
                            //更新inject状态
                            $inject->setState(1);
                            $inject->save(); 
                        }else {
                            $type_num++;
                            //echo $inject->getId()."----\n";
                            $inject->setState(-1);
                            $inject->save();
                        }                    
                    }else{
                        $error_num++;
                        $inject->setState(-1);
                        $inject->save();
                    }
                }
            }
            $i = $i + 100;
            echo $i,'*************************************',"\n"; 
            sleep(1);  
        } 
        echo iconv("utf-8","gbk","总数:$inject_count | 未设置showType：$showType_num | 删除数：$del_num | 更新数:$update_num | 新增数:$add_num | 未知类型:$type_num | 错误数:$error_num"),"\n";      
    }  
    
    private function getMetadata($Metadata) {
        $p = array();
        if(isset($Metadata)){
            $p = $this->getAttrs($Metadata->AMS);
            if(isset($Metadata->App_Data)){
                foreach($Metadata->App_Data as $key => $val) {
                    list($name,$value) = $this->getArrayByAttrs($val);
                    $p[$name] = $value;
                }  
            }
        }
        return $p;
    }

    private function getArrayByAttrs($s) {
        foreach($s->attributes() as $key => $val) {
            if($key == "Name"){
                $Name = (string)$val;
            }
            if($key == "Value"){
                $Value = (string)$val;
            }
        }
        return array($Name,$Value);
    }

    private function getAttrs($s) {
        $arr=array();
        if(isset($s)){
            foreach($s->attributes() as $key => $val) {
               $arr[$key] = (string)$val;
            }  
        }
        return $arr;
    } 

    private function postCallBack($data) {
        $opts = array('http'=>array('method'=>"POST",
                                    'header'=>"Content-Type:text/html\r\n",
                                    'content'=> $data));
        //@url = "http://10.20.20.209/inject";
        $url = "http://172.31.183.10:8080/icms/content?action=adi1synccallback";
        return @file_get_contents($url, false, stream_context_create($opts));
    }
   
    private function getBackXmlString($asset_id, $import_id, $type, $status, $desc = '') {
        $xml = "<?xml version = \"1.0\" encoding=\"utf-8\"?>\n";
        $xml .= "\t<SyncContentsResult Time_Stamp=\"".date("Y-m-d H:i:s")."\"  System_ID=\"epg\">\n"; 
        $xml .= "\t<Asset ID=\"".$asset_id."\"  Current_ID=\"".$import_id."\" Type=\"".$type."\"  Status=\"".$status."\" Desc=\"".$desc."\"></Asset>\n";
        $xml .= "</SyncContentsResult>\n";
        return $xml;
    }
    
    /**
     * 对节目名称进行过滤
     * @param void $ftp_conn
     */ 
    private function getSubTitle($str){
        //替换
        $patterns = array('/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/剧场/',
                          '/第.*集/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/-.*/');
        $str = preg_replace($patterns, "", $str);
        //替换
        $patterns = array('/法治中国/','/视野/','/爱探险的朵拉/',
                          '/欧美流行.*/','/舌尖上的中国.*/');
        $repatt = array('法治中国（江苏）','视野（辽宁）','爱探险的Dora',
                        '欧美流行','舌尖上的中国');
        $str = preg_replace($patterns, $repatt, $str);
        return $str;
    }    
}
