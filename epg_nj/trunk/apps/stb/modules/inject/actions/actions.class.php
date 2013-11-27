<?php

/**
 * json actions.
 *
 * @package    epg
 * @subpackage json
 * @author     superwen
 */

class injectActions extends sfActions
{		
    /**
    * Executes index action
    *
    * @param sfRequest $request
    * @author superwen
    * @final 2012-07-09
    */
    public function executeIndex(sfWebRequest $request) 
	{
        if ($request->getMethod() == 'POST') {
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
            file_put_contents("../log/inject.".date("YmdHis").".txt", $jsonstr, FILE_APPEND);
            
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText("{'stats' : 'ok'}");            
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }     
    }
    
    public function executeEpg(sfWebRequest $request) 
	{
         if ($request->getMethod() == 'POST') {
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
            file_put_contents("../log/inject.".date("YmdHis").".txt",$jsonstr);
            
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText("{'stats' : 'ok'}");            
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }     
    }
    
    /**
     * cdi上下线
     * @param sfRequest $request
     * @author lifucang
     * @final 2013-03-07
     */
    public function executeCdi(sfWebRequest $request) 
	{
        if ($request->getMethod() == 'POST') { 
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
            $inject = new ContentCdi();
            $inject->setFrom("cms");
            $inject->setState(1);
            $inject->setContent($jsonstr);            
            $inject->save();
            
            //反馈给cms
            $content = @simplexml_load_string(trim($jsonstr));     
            $header=$content->header->attributes();
            $this->getResponse()->setContentType('text/plain');
            if($header['command']=='ONLINE_TASK_DONE'){
                foreach($content->body->tasks->task as $val){
                    $attr=$val->attributes();
                    $children_id=$attr['subcontent-id'];
                    break;
                    //$backxmlstring = $this->getBackCdiString($attr['subcontent-id'], $attr['subcontent-id'], 'program', 0, 'ok'); 
                    //$this->postCallBack($backxmlstring);
                }
                //上线
                /*
                $mongo = $this->getMondongo();
                $importImport_repo = $mongo->getRepository("ContentImport"); 
                $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id,'wiki_id'=>array('$exists'=>true))));
                if($importImport){
                    $this->videoSave($importImport);
                    $importImport->setStateEdit(1);
                    $importImport->save();
                } 
                */
                exec("/bin/php /www/newepg/symfony tv:ContentImport");
                exec("/bin/php /www/newepg/symfony tv:ContentImportVideo --id=".$children_id);
                //更新迅搜
                exec("/bin/php /www/newepg/symfony tv:wikiToXunSearch --update=date");
                $backxmlstring=$this->getBackCdiString($header['sequence'],$header['component-id'],'ONLINE_TASK_DONE');
                return $this->renderText($backxmlstring);
            }elseif($header['command']=='CONTENT_OFFLINE'){
                foreach($content->body->contents->content as $val){
                    $attr=$val->attributes();
                    //下线
                    $this -> videoDel($attr['subcontent-id']);
                    //$backxmlstring = $this->getBackCdiString($attr['subcontent-id'], $attr['subcontent-id'], 'program', 0, 'ok'); 
                    //$this->postCallBack($backxmlstring);
                }
                //更新迅搜
                exec("/bin/php /www/newepg/symfony tv:wikiToXunSearch --update=date");
                $backxmlstring=$this->getBackCdiString($header['sequence'],$header['component-id'],'CONTENT_STATUS_UPDATED');
                return $this->renderText($backxmlstring);
            }elseif($header['command']=='DELIVERY_TASK_DONE'){
                $backxmlstring=$this->getBackCdiString($header['sequence'],$header['component-id'],'DELIVERY_TASK_DONE');
                return $this->renderText($backxmlstring);
            }
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }    
        return sfView::NONE;  
    }
    //上线，和ContentImportVideo中的videoSave()一样
    private function videoSave($importTemp) 
    {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $videoplaylist_repo = $mongo->getRepository("VideoPlaylist");
        $wikiMetaRepos = $mongo->getRepository('wikiMeta');
        $wikiRes = $mongo->getRepository('wiki');
        $wiki = $wikiRes->findOneById(new MongoId($importTemp->getWikiId()));
        if(!$wiki) return false;
        $title = $wiki->getTitle();
        $add_num=0;
        $update_num=0;
        if($wiki->getModel()=='film'){ 
            //电影
            $childrenids = $importTemp->getChildrenId();
            $childrenid = $childrenids[0]["ID"];
            $url = ""; //$this->getVideoUrl($childrenid,$importTemp->getProviderId());
            $config = array("asset_id" => $childrenid);
            //先查询是否重复,有则只更新
            $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid);
            $video=$video_repo->findOne(array('query'=>$query));
            if(!$video){
                $video = new Video();
                $wiki->setHasVideo(true);
                $wiki->save();  
                $add_num++;
            }else{
                $update_num++;
            }    
            $video->setWikiId((string)$wiki->getId());
            $video->setModel($wiki->getModel());
            $video->setTitle($childrenids[0]['Title']);
            $video->setUrl($url);
            $video->setConfig($config);
            $video->setReferer($importTemp->getProviderId());
            $video->setPublish(true);
            //$video->setVideoPlaylistId($videoPlaylistId);
            //$video->setTime();
            //$video->setMark(1);
            $video->save();
        }else{  
            //电视剧，栏目等
            //写入videoPlayList表,先查询是否重复
            $query = array('referer'=>$importTemp->getProviderId(),'wiki_id'=>(string)$wiki->getId());
            $VideoPlaylist = $videoplaylist_repo->findOne(array('query'=>$query));
            if(!$VideoPlaylist){
                $VideoPlaylist = new VideoPlaylist();
                $VideoPlaylist->setTitle($title);
                //$VideoPlaylist->setUrl('');
                $VideoPlaylist->setReferer($importTemp->getProviderId());
                $VideoPlaylist->setWikiId((string)$wiki->getId());
                $VideoPlaylist->save();
                
                $wiki->setHasVideo(true);
                $wiki->save(); 
            }
            //继续写入video表
            $videoPlaylistId = (string)$VideoPlaylist->getId();
            $childrenids = $importTemp->getChildrenId();
            foreach($childrenids as $childrenid)
            {
                $url = '';
                $config = array("asset_id" => $childrenid['ID']);
                $mark = $childrenid['Chapter'] ? $childrenid['Chapter'] : 0;
                
                $query = array('referer'=>$importTemp->getProviderId(),'config.asset_id'=>$childrenid['ID']);
                $video=$video_repo->findOne(array('query'=>$query));
                if(!$video){
                    $video = new Video();
                    $add_num++;
                }else{
                    $update_num++;
                }
                $video->setWikiId((string)$wiki->getId());
                $video->setModel($wiki->getModel());
                $video->setTitle($childrenid['Title']);
                $video->setUrl($url);
                $video->setConfig($config);
                $video->setReferer($importTemp->getProviderId());
                $video->setPublish(true);
                $video->setVideoPlaylistId($videoPlaylistId);
                //$video->setTime();
                $video->setMark($mark);
                $video->save(); 
            } 
        }
        $num=array('add'=>$add_num,'update'=>$update_num);
        return $num;
    }    
    //下线
    private function videoDel($asset_id) {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video");  
        $wiki_repo = $mongo->getRepository("Wiki");
        
        $video = $video_repo->findOne(array('query'=>array('config.asset_id'=>$asset_id)));
        if($video){
            $wiki_id=$video->getWikiId();
            $video -> delete();
            $wiki=$wiki_repo->findOneByID(new MongoId($wiki_id));
            $wiki->setHasVideo(false);
            $wiki->save();
        }
    }
    

    /**
     * vod数据接收
     * @param sfRequest $request
     * @author superwen
     * @final 2012-07-09
     */
    public function executeVod(sfWebRequest $request) 
	{
        if ($request->getMethod() == 'POST') { 
			if($request->getPostParameter('xmlString')){
				$jsonstr = $request->getPostParameter('xmlString');
			}else {
				$jsonstr = file_get_contents('php://input');
			}
            //$mongo = $this->getMondongo();
            $inject = new ContentInject();
            $inject->setFrom("cms");
            $inject->setState(0);
            $inject->setContent($jsonstr);            
            $inject->save();
            //$this->getResponse()->setContentType('text/plain');		
            //return $this->renderText("{'stats' : 'ok'}");

            $content = @simplexml_load_string(trim($jsonstr));                
            $adi_md = $this->getMetadata($content->Metadata);
            $asset_md = $this->getMetadata($content->Asset->Metadata);
            //异步反馈
            $backxmlstring = $this->getBackXmlString($adi_md['Asset_ID'], $adi_md['Asset_ID'], $asset_md['Show_Type'], 0, 'ok'); 
            $this->postCallBack($backxmlstring);  
            //同步响应
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText('0 | ok');
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }  
        return sfView::NONE;    
    }
    /**
     * Executes vod action
     *
     * @param sfRequest $request
     * @author superwen
     * @final 2012-07-09
     */
    public function executeTest(sfWebRequest $request) 
	{
        if ($request->getMethod() == 'POST') { 
			$jsonstr = $request->getPostParameter('xmlString');
			
            $content = file_get_contents($jsonstr);	
            return $this->renderText($content);
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }      
    }  
    
    
    
    

    //获取Meta信息
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

    //将属性写到数组中
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
                                    'header'=>"Content-Type:text/plain\r\n",
                                    'content'=> $data));
        //@url = "http://10.20.20.209/inject";
        $bkUrl = sfConfig::get('app_cmsCenter_bkjsonVod');
        $url = "$bkUrl?action=adi1synccallback";
        return @file_get_contents($url, false, stream_context_create($opts));
    }
   
    private function getBackXmlString($asset_id, $import_id, $type, $status, $desc = '') {
        $xml = "<?xml version = \"1.0\" encoding=\"utf-8\"?>";
        $xml .= "<SyncContentsResult Time_Stamp=\"".date("Y-m-d H:i:s")."\"  System_ID=\"epgdb\">"; 
        $xml .= "<Asset ID=\"".$asset_id."\"  Current_ID=\"".$import_id."\" Type=\"".$type."\"  Status=\"".$status."\" Desc=\"".$desc."\"></Asset>";
        $xml .= "</SyncContentsResult>";
        return $xml;
    }

    private function getBackCdiString($sequence, $componentid, $command) {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<message module="iCMS" version="1.0">'; 
        $xml .= '<header timestamp="'.date("Y-m-d H:i:s").'" sequence="'.$sequence.'" component-id="'.$componentid.'" component-type="iCMS" action="RESPONSE" command="'.$command.'" />';
        $xml .= '<body>';
        $xml .= '<result code="1" description="OK" />';
        $xml .= '</body>';
        $xml .= '</message>';
        return $xml;
    }
    /*
    private function getBackCdiString($asset_id, $import_id, $type, $status, $desc = '') {
        $xml = "<?xml version = \"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>";
        $xml .= "<SyncContentsResult Time_Stamp=\"".date("Y-m-d H:i:s")."\"  System_ID=\"epgdb\">"; 
        $xml .= "<Assets>";
        $xml .= "<Asset ID=\"".$asset_id."\"  Current_ID=\"".$import_id."\" Type=\"".$type."\"  Status=\"".$status."\" Desc=\"".$desc."\"></Asset>";
        $xml .= "</Assets>";
        $xml .= "</SyncContentsResult>";
        return $xml;
    }
    */
}    
?>
