<?php
/**
 * list actions.
 * @package    epg2.0
 * @subpackage list
 * @author     Huan lifucang
 * @version    1.0
 */
class listActions extends sfActions
{
    /**
    * Executes index action
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $this->tag = $request->getParameter('type','电视剧');
        $this->page = $request->getParameter('page',1);
        $this->types = array("电视剧", "电影", "体育", "综艺", "动漫", "文化","综合","点播");
        /*
        $mongo = $this->getMondongo();
        $channels = Doctrine::getTable('Channel')->getChannels();
        $programs = $mongo->getRepository("program");
        $this->programList = $programs->getLiveProgramByTagPage($this->tag, $channels,$this->page,8);   
        if($this->programList){
            $this->programTop = $this->programList[0];  
            $this->is_have=true; 
        }else{
            $this->programTop = NULL; 
            //获取推荐节目
            $wrRepo = $mongo->getRepository("WikiRecommend");
			$this->wikiList = $wrRepo->getWikiByPageAndSize($this->page,8,$this->tag); 
            $this->is_have=false;              
        }
        */
    }
    /**
    * ajax调用
    * @author lifucang
    */
    public function executeShowProgram(sfWebRequest $request)
    {
        $tag = $request->getParameter('type','电视剧');
        $page = $request->getParameter('page',1);
        $scrollpage = $request->getParameter('scrollpage',0);
        $mongo = $this->getMondongo();
        $memcache = tvCache::getInstance(); 
        $local=0;
        //为了给接口提供分类
        $this->arr_type=array(
            '电视剧'=>'Series',
            '电影'=>'Movie',
            '体育'=>'Sports',
            '综艺'=>'Entertainment',
            '动漫'=>'Children',
            '文化'=>'Culture',
            '财经'=>'Finance',
            '综合'=>'General',
            '其他'=>'Other',
        ); 
        $interface='tcl';  //默认接口是tcl
        
        if($tag=='文化'){
            $tags=array('教育','科教','人文','文化','科学');
        }elseif($tag=='体育'){
            $tags=array('体育','田径','足球','篮球');
        }elseif($tag=='动漫'){
            $tags=array('少儿','动漫','动画');
        }elseif($tag=='综艺'){
            $tags=array('综艺','娱乐','戏剧');
        }elseif($tag=='综合'){
            $tags=array('综合','历史','民生','财经');
        }else{
            $tags=$tag;
        }
        
        $wikis=array();
	    $cardId = $request->getParameter("cardId");  //智能卡号
        $stbId  = $request->getParameter("stbId");   //机顶盒号
        /*
        if($cardId=='8250102886999238'||$cardId=='8250102886999246'){
            $user_id="99586611250057372_0";
        }else{
            $user_id="99766609340071223_0";
        } 
        */
        if($interface=='tcl'){
            $user_id=substr($cardId,0,strlen($cardId)-1);
        }elseif($interface=='tongzhou'){
            $user_id=$cardId;
        }else{
            $user_id=$stbId;
        }
        if($tag=='点播'){
            //从tcl接口获取
            if($interface=='tcl'){
                $wiki_repository = $mongo->getRepository("Wiki");
                $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=".$user_id;
                $contents=Common::get_url_content($url);
                if($contents){
                    $arr_contents=json_decode($contents);
                    foreach($arr_contents->recommend as $value){
                        $wiki_id = $value->contid_id;  
                        $wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
                    }
                }
                $refer='tcl'; 
            }    
            //从运营中心获取
            if($interface=='center'){
                $recomUrl = 'http://172.20.224.146:9090/ie/interface?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=10&lang=zh&urltype=1&alg=CF&uid='.$user_id.'&backurl=http://'.$request->getHost().'/list';
                $recomTxt = Common::get_url_content($recomUrl);
                if($recomTxt){
                    $recomJson = json_decode($recomTxt,true);
                    if($recomJson)
                        $wikis = $recomJson['recommend'];
                }
                $refer='center';  
            }
            //从同洲获取
            if($interface=='tongzhou'){
                $wikis = $memcache->get("tongzhou_recomwikis");
                if(!$wikis) {
                    $recomUrl = 'http://172.31.178.6:10080/recommand/recommand/epgAction.action?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid='.$user_id.'&backurl=http://'.$request->getHost().'/';
                    $recomTxt = Common::get_url_content($recomUrl);
                    if($recomTxt){
                        $recomJson = json_decode($recomTxt,true);
                        if($recomJson){
                            $wikis = $recomJson['recommend'][0]['recommand'];
                            $memcache->set("tongzhou_recomwikis",$wikis,3600);    
                        }
                    }
                } 
                $refer='tongzhou';  
            }
            //获取不到从本地获取
            if(count($wikis)==0||!$wikis){
                $wrRepo = $mongo->getRepository("WikiRecommend");
        		$wikiRecommends = $wrRepo->getWikiByPageAndSize($page,32,''); 
                foreach($wikiRecommends as $recommend){
                    $wikis[]=$recommend->getWiki();
                }  
                $refer='local';    
            }
            $programList=false;
        }else{
            $programs = $mongo->getRepository("program"); 
            $programList=array();
            if($interface=='tcl'){
                //直播信息从tcl获取
                $sp_repository = $mongo->getRepository('SpService');
                $programs = $mongo->getRepository('program');
                //$url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=32&uid=123";
                $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=145&genre=".$this->arr_type[$tag]."&uid=".$user_id;
                $contents=Common::get_url_content($url);
                if($contents){
                    //$channelCodes=array();
                    $arr_contents=json_decode($contents);
                    $k=0;
                    foreach($arr_contents->recommend as $value){
                        if($k>=30) break;
                        $sp=$sp_repository->findOne(array('query'=>array('channel_id'=>$value->contid_id)));
                        //$channelCodes[] = $sp->getChannelCode(); 
                        $channelCode = $sp->getChannelCode(); 
                        $program=$programs->getLiveProgramByChannel($channelCode);
                        if($program){
                             $programList[]= $program;
                             $k++;
                        }         
                    }
                    //$programList=$programs->getLiveProgramByChannelCode($channelCodes,$tags,32);
                }
            }
            if($interface=='tongzhou'){
                //直播信息从同洲电子获取
                $sp_repository = $mongo->getRepository('SpService');
                $programs = $mongo->getRepository('program');
                //$url="http://172.31.178.6:10080/recommand/recommand/epgAction.action?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=20&filter=".$this->arr_type[$tag]."&uid=".$user_id;
                $url="http://172.31.178.6:10080/recommand/recommand/epgAction.action?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=20&filter=Entertainment&uid=".$user_id;
                $contents=Common::get_url_content($url);
                if($contents){
                    $arr_contents=json_decode($contents,true);
                    $k=0;
                    foreach($arr_contents['recommend'][0]['recommand'] as $value){
                        if($k>=30) break;
                        $sp=$sp_repository->findOne(array('query'=>array('channel_id'=>$value['Channel_ID'])));
                        $channelCode = $sp->getChannelCode(); 
                        $program=$programs->getLiveProgramByChannel($channelCode);
                        if($program){
                             $programList[]= $program;
                             $k++;
                        }         
                    }
                }
            }
            if(!$programList||count($programList)==0){
                //从本地获取直播信息
                //$programList = $programs->getLiveProgramByTagsGd($tags, 32); 
                //改为按频道热度获取直播节目  
                $channels=$mongo->getRepository('SpService')->getServicesByTag(null,'hot',-1);
                $k=0;
                foreach($channels as $channel){
                    if($k>=30) break;
                    $program=$programs->getLiveProgramByChannelTag($channel->getChannelCode(),$tags);
                    if($program){
                         $programList[]= $program;
                         $k++;
                    }
                }
            }
            //直播内容不足以点播填充，本为8，防止有没有图片的
            $programCount=count($programList);
            if($programCount<=10){  
                //从tcl接口获取
                if($interface=='tcl'){
                    $wiki_repository = $mongo->getRepository("Wiki");
                    $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=10&genre=".$this->arr_type[$tag]."&uid=".$user_id;
                    $contents=Common::get_url_content($url);
                    if($contents){
                        $arr_contents=json_decode($contents);
                        foreach($arr_contents->recommend as $value){
                            $wiki_id = $value->contid_id;  
                            $wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
                        }
                    }
                    $refer='tcl'; 
                }   
                //从运营中心获取
                if($interface=='center'){
                    $recomUrl = 'http://172.20.224.146:9090/ie/interface?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&ctype=vod&count=10&alg=&group=1&situation=&lang=zh&uid=99766609340071223_0&rtype=recommend.rs.v1&filter=&backurl=http://'.$request->getHost().'/list';
                    $recomTxt = Common::get_url_content($recomUrl);
                    if($recomTxt){
                        $recomJson = json_decode($recomTxt,true);
                        if($recomJson)
                            $wikis = $recomJson['recommend'];
                    }
                    $refer='center';  
                }
                //获取不到从本地获取
                if(count($wikis)==0||!$wikis){       
                    $wrRepo = $mongo->getRepository("WikiRecommend");
        			$wikiRecommends = $wrRepo->getWikiByPageAndSize($page,10,$tag); 
                    foreach($wikiRecommends as $recommend){
                        $wikis[]=$recommend->getWiki();
                    } 
                    $refer='local';                  
                } 
            }
        }
        return $this->renderPartial('showProgram', array('programList'=>$programList,'wikis'=>$wikis,'refer'=>$refer)); 
    }
    
    
    /**
     * ajax调用
     * @author lifucang
     */
    public function executeAll(sfWebRequest $request)
    {
    	$mongo = $this->getMondongo();
    	$programRep = $mongo->getRepository('program');
    	$spsRep=$mongo->getRepository('SpService');
    	
    	$chanels = $spsRep -> find(
    		array('query'=>array(
    					'channel_code'=>array('$exists'=>"true",'$ne'=>null)
    		))
    	);
    	
    	foreach ($chanels as $channel) {
    		$channel_codes[] = $channel->getChannelCode();
    	}
    	//$channel_codes = trim($channel_codes,',');
    	
    	//echo $channel_codes;die();
    	
    	$date = date('Y-m-d',time());
    	$programs = $programRep -> find(
            array(
                'query' => array(
            		'channel_code' => array('$in' => $channel_codes),
                    'date' => $date,
                    'wiki_id'=>array('$exists'=>"true",'$ne'=>null)
                ),
                'sort' => array('time' => 1)
            )
        );
        
        foreach ($programs as $program) {
        	$wikiArr[] = $program->getWikiId();
        }
        
        $wikiArr = array_unique($wikiArr);
        
        print_r($wikiArr);
        die();
    }
}
