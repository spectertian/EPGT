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
        $this->types = array("电视剧", "电影", "体育", "娱乐", "少儿", "科教", "综合","点播");
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
        $mongo = $this->getMondongo();
        /*
        if($tag=='综合'){
            $tag=array('综合','财经');
        }
        */
        $wikis=array();
        if($tag=='点播'){
            //先从tcl接口获取

            $wiki_repository = $mongo->getRepository("Wiki");
            $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=8&uid=123";
            //$contents=file_get_contents($url);
            $contents=Common::get_url_content($url);
            if($contents){
                $arr_contents=json_decode($contents);
                foreach($arr_contents[3]->recommend as $value){
                    $wiki_id = $value->contid_id;  
                    $wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
                }
            }

            //获取不到从本地获取
            if(count($wikis)==0){
                $wrRepo = $mongo->getRepository("WikiRecommend");
        		$wikiRecommends = $wrRepo->getWikiByPageAndSize($page,8,''); 
                foreach($wikiRecommends as $recommend){
                    $wikis[]=$recommend->getWiki();
                }    
            }
            $programList=false;
        }else{
            $channels = Doctrine::getTable('Channel')->getChannels();
            $programs = $mongo->getRepository("program");
            $programList = $programs->getLiveProgramByTagPage($tag, $channels,$page,8);   
            if($programList){
                //$programTop = $programList[0];   
            }else{
                //$programTop = NULL;  
                //先从tcl接口获取

                $wiki_repository = $mongo->getRepository("Wiki");
                $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=8&uid=123&genre=".$tag;
                //$contents=file_get_contents($url);
                $contents=Common::get_url_content($url);
                if($contents){
                    $arr_contents=json_decode($contents);
                    foreach($arr_contents[3]->recommend as $value){
                        $wiki_id = $value->contid_id;  
                        $wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
                    }
                }       

                //获取不到从本地获取
                if(count($wikis)==0){       
                    $wrRepo = $mongo->getRepository("WikiRecommend");
        			$wikiRecommends = $wrRepo->getWikiByPageAndSize($page,8,$tag); 
                    foreach($wikiRecommends as $recommend){
                        $wikis[]=$recommend->getWiki();
                    }                
                } 
            }
        }
        return $this->renderPartial('showProgram', array('programList'=>$programList,'wikis'=>$wikis)); 
        /*
        //这样虽然能判断到最后一页,但会导致tag没有内容的不变
        if($programList||$wikiList){
            return $this->renderPartial('showProgram', array('programList'=>$programList,'wikiRecommends'=>$wikiRecommends)); 
        }else{
            return NULL;
        }
        */
    }    
}
