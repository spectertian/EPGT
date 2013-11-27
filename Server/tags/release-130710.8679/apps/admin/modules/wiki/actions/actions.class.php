<?php

/**
 * mwiki actions.
 *
 * @package    epg
 * @subpackage mwiki
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wikiActions extends sfActions 
{
    var $putQueues = array("epg_queue","epg_queue_irs","epg_queue_nj");
    
    /**
     * 维基管理列表
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request) 
    {
        $page    = $request->getParameter('page', 1);        
        $this->q = trim($request->getGetParameter('q', ''));
        $this->c = trim($request->getGetParameter('c', ''));
        $this->m = $request->getGetParameter('m', 'all');
        $this->tag = $request->getGetParameter('tag', '');
        $query_arr = array();
        
        if($this->q!=''){
            $query_arr['$or']=array(array('title'=>new MongoRegex("/.*$this->q.*/i")),array('alias'=>new MongoRegex("/.*$this->q.*/i")));
        }
        if($this->c!='') {
            if($this->c == "null")
            	$query_arr['admin_id']=array('$exists'=>false);
            else
            	$query_arr['admin_id']=intval($this->c);
        }
        if($this->m!='all')
            $query_arr['model']=$this->m;
        if($this->m == 'television' && $this->tag!='')
			$query_arr['tags']=$this->tag;     
        $query = array('query'=>$query_arr,'sort' => array('updated_at' => -1));

        $this->query=$query;
        $this->pageTitle    = '维基列表' ;
        $this->wiki = new sfMondongoPager('Wiki', 20);
        $this->wiki->setFindOptions($query);

        $this->wiki->setPage($page);
        $this->wiki->init();
        $this->users = Doctrine::getTable('admin')->findAll();
    }

    /**
     * 创建新内容，显示表单页面
     * @param sfWebRequest $request
     */
    public function executeNew(sfWebRequest $request) 
    {
        $model_name = $request->getParameter("model");
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Wiki');
        $wiki_model = $repository->factory($model_name);
        if($wiki_model){
         	$this->form = $wiki_model->getForm();
        }
    }

    /**
     * 创建 Wiki, 保存至数据库
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeCreate(sfWebRequest $request) 
    {
        $model_name = $request->getParameter("model");
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Wiki');
        $wiki_model = $repository->factory($model_name);
        $this->form = $wiki_model->getForm();
        if ($this->processForm($request, $this->form)) {
            $this->getUser()->setFlash('notice', '维基添加成功');
            $httpsqs = HttpsqsService::get();
            $queueData = array(
                "action" => "wiki_insert",
                "created_at" => time(),
                "parms" => array("wiki_id" => strval($this->form->getDocument()->getId()))
            );
            foreach($this -> putQueues as $putQueue) {
                $httpsqs->put($putQueue,json_encode($queueData)); 
            }
            $this->redirect($this->generateUrl('wiki_edit', array('id' => (string) $this->form->getDocument()->getId())));
        } else {
            $this->getUser()->setFlash('error', '保存失败，请检查', false);     
        }
        $this->setTemplate("new");
    }

    /**
     * 修改、更新 Wiki
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeUpdate(sfWebRequest $request) 
    {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('wiki');
        $id = $request->getParameter('id', null);
        $wiki = $repository->findOneById(new MongoId($id));
        $this->redirectUnless($wiki, 'wiki/index');
        $this->form = $wiki->getForm();

        if ($this->processForm($request, $this->form)) {
            $this->getUser()->setFlash('notice', '维基保存成功');
            $httpsqs = HttpsqsService::get();
            $queueData = array(
                "action" => "wiki_update",
                "created_at" => time(),
                "parms" => array("wiki_id" => strval($this->form->getDocument()->getId()))
            );
            foreach($this -> putQueues as $putQueue) {
                $httpsqs->put($putQueue,json_encode($queueData)); 
            }
            $memcache = tvCache::getInstance();
            $memcache->clear();
            $this->redirect($request->getReferer());
        } else {
            $this->getUser()->setFlash('error', '保存失败，请检查');
            $this->setTemplate("edit");
        }
    }

    /**
     * 编辑或新增
     * @param sfWebRequest $request
     * @date 2010-11-30
     */
    public function executeEdit(sfWebRequest $request) 
    {
        $id = $request->getParameter('id', null);
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('wiki');
        $this->wiki = $repository->findOneById(new MongoId($id));
        $this->forwardUnless($this->wiki, 'wiki', 'index');
        //获取电视分集
        if( $this->wiki->getModel() == "teleplay" ){
            $wikiMetaRepos = $mongo->getRepository('wikiMeta');
            $this->metas = $wikiMetaRepos->getMetasByWikiId($id);
        }
        
        $this->form = $this->wiki->getForm();
        $memcache = tvCache::getInstance();
        $memcache->clear();
    }

    /**
     * 表单处理保存
     * @param array $wiki
     * @param sfForm $form
     * @return sfForm
     */
    protected function processForm(sfWebRequest $request, sfForm $form) 
    {
        $form->bind($request->getParameter('wiki'));
        if ($form->isValid()) {
            $form->save();
            $wiki_id = $form->getDocument()->getId();
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Wiki');
            $wiki = $repository->findOneById(new MongoId($wiki_id));
            
            if(!empty($wiki) && $wiki->getModel() == "teleplay" ){
                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
                $metas = $wikiMetaRepos->getMetasByWikiId((string)$wiki_id);
            
                //删除旧的wikiMeta
                if(!empty ($metas)){
                    foreach ($metas as $meta){
                        $meta->delete();
                    }
                }
            }
            
            //插入新的wikiMeta
            $meta = $request->getParameter('meta');
            if (!empty($meta)) {
                foreach ($meta['mark'] as $key=>$mark) {
                    $wikiMeta = new WikiMeta();
                    $wikiMeta->setWikiId($wiki_id);
                    $wikiMeta->setTitle($meta['title'][$key]);
                    $wikiMeta->setContent($meta['content'][$key]);
                    $wikiMeta->setMark($mark);
                    if ($form->getDocument()->getModel() == 'television') {
                        $timestamp = strtotime($mark);
                        $wikiMeta->setYear(date('Y', $timestamp));
                        $wikiMeta->setMonth(date('m', $timestamp));
                    }

                    if(isset ($meta["screenshots"][$mark])){
                        $wikiMeta->setScreenshots($meta["screenshots"][$mark]);
                    }
                    $wikiMeta->save();
                }
             }

            return $form;
        } else {          
            return false;
        }
    }
    
    /**
     * 维基批量推荐 / 删除 操作
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeBatch(sfWebRequest $request) 
    {
        $action = $request->getParameter('batch_action');
        $ids = $request->getParameter('id');    
        if (count($ids) > 0) {
            switch ($action) {
                case 'recommend':    
                    $this->batchRecommend($ids);
                    break;
                case 'delete':
                        $this->batchDelete($ids);
                    break;
            }
            
        } else {
            $this->getUser()->setFlash('error', '请选择操作项目!');
        }

        $this->redirect($request->getReferer());
    }

    /**
     * 批量推荐
     * @param <array> $ids
     */
    private function batchRecommend($ids) 
    {  
        $mongo = $this->getMondongo();
        $wikiRep = $mongo->getRepository('Wiki');
        $recommendRep = $mongo->getRepository('WikiRecommend');
       
        foreach ($ids as $id) {   
            if (! $recommendRep->findOne(array('query' => array('wiki_id'=> $id)))) {
                $wiki = $wikiRep->findOnebyId(new MongoId($id));
                $wikiRecommend = new WikiRecommend();
                $wikiRecommend->setWikiId($id);
                if ($wiki->getTags()) $wikiRecommend->setTags($wiki->getTags());
                $wikiRecommend->setModel($wiki->getModel());
                $wikiRecommend->save();
            }
        }
        
        $this->getUser()->setFlash('notice', '维基设置推荐成功!');
    }

    /**
     * data 2011-06-21
     * @param sfWebRequest $request
     */
    public function  executeRecommend(sfWebRequest $request) 
    {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $wikiRep = $mongo->getRepository('Wiki');
        $recommendRep = $mongo->getRepository('WikiRecommend');
         if (! $recommendRep->findOne(array('query' => array('wiki_id'=> $id)))) {
                $wiki = $wikiRep->findOnebyId(new MongoId($id));
                $wikiRecommend = new WikiRecommend();
                $wikiRecommend->setWikiId($id);
                if ($wiki->getTags()) $wikiRecommend->setTags($wiki->getTags());
                $wikiRecommend->setModel($wiki->getModel());
                $wikiRecommend->save();
                $this->getUser()->setFlash('notice', '维基设置推荐成功!');
         }else{
             $this->getUser()->setFlash('error', '该维基已经被推荐！');
         }
        $this->redirect($request->getReferer());
    }


    /**
     * 批量删除维基 
     * @param <array> $ids
     */
    private function batchDelete($ids) 
    {
        foreach ($ids as $id) {
            $this->deleteWiki($id); 
        }        
        $this->getUser()->setFlash('notice', '已删除所选择的维基!');
    }
    
    /**
     * 批量删除维基
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeDelete(sfWebRequest $request) 
    {
        $this->deleteWiki($request->getParameter('id'));
        $this->getUser()->setFlash('notice', '维基已删除!');
        $this->redirect($request->getReferer());
    }
    
    /**
     * 删除wiki操作，如果有推荐推荐内容也删除
     * @param unknown $wikiId
     */
    public function deleteWiki($wikiId)
    {
    	$mongo = $this->getMondongo();
    	$wikiRepos = $mongo->getRepository('Wiki');

    	$wiki = $wikiRepos->findOneById(new MongoId($wikiId));
        if (!is_null($wiki)) $wiki->delete();
        
        $httpsqs = HttpsqsService::get();
        $queueData = array(
                "action" => "wiki_delete",
                "created_at" => time(),
                "parms" => array("wiki_id" => $wikiId)
        );
        foreach($this -> putQueues as $putQueue) {
            $httpsqs->put($putQueue,json_encode($queueData)); 
        }
    }
    

    /**
     * 表单处理保存
     * @param array $wiki
     * @param sfForm $form
     * @return sfForm
     */
    /*
    protected function processForm($wiki, sfForm $form) {
        $form->bind($wiki);

        if ($form->isValid()) {
            $form->save();
            return $form;
        } else {
            $this->getUser()->setFlash('notice', '该条维基删除不存在!');
        }
        
        $this->redirect('wiki/index');
    }
     *
    */
    /**
     *  通过ajax 采集站点维基数据
     * @author luren
     */
    public function executeGetWikiSiteData(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            sfContext::getInstance()->getConfiguration()->loadHelpers(array('Curl'));
            $url = $request->getPostParameter('url');
            $model = $request->getPostParameter('model');
            $result = array();
            
            if ( false !== strpos($url, 'baidu') ) {
                $result = $this->baiduWiki($url);
            } elseif (false !== strpos($url, 'douban')) {
                $result = $this->danbanWiki($url, $model);
            }

            return $this->renderText(json_encode($result));
        } else {
            $this->forward404();
        }
    }

    /**
     *显示分期
     * @param sfWebRequest $request
     */
    public function executeGetStaging(sfWebRequest $request){
        $result['meta_screenshots'] = array();
        $result['meta_screenshots_url'] = array();
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('GetFileUrl'));
        if ($request->isXmlHttpRequest()){
            $result = array();
            $meta_id = $request->getPostParameter('meta_id');
            $mongo = $this->getMondongo();
            $wikiMetaRepos = $mongo->getRepository('wikiMeta');
            $wikiMeta = $wikiMetaRepos->findOneById(new MongoId($meta_id));

            $result['meta_id'] =  (string)$wikiMeta->getId();
            $result['meta_mark'] =  $wikiMeta->getMark();
            $result['meta_content'] =  $wikiMeta->getContent();
            $result['meta_guests'] =  $wikiMeta->getGuests();
            $result['meta_title'] =  $wikiMeta->getTitle();
            $result['meta_wiki_id'] =  $wikiMeta->getWikiId();
            if($wikiMeta->getScreenshots()){
                 $result['meta_screenshots'] = $wikiMeta->getScreenshots();
                 foreach( $wikiMeta->getScreenshots() as $val){
                    $array[] = file_url($val);
                }
                $result['meta_screenshots_url'] = $array;
            }
            return $this->renderText(json_encode($result));
        }else{
            $this->forward404();
        }
    }

    /**
     * 豆瓣维基采集
     * @author luren
     */
    private function danbanWiki($url, $model) {
        $result = array();
        $doubanHtml = file_get_contents($url, false, Common::createStreamContext());
        //匹配标题
        preg_match('|property="v:itemreviewed">([\w\W]*?)</span>|', $doubanHtml, $matches);
        
        if(isset($matches[1])) {
            $result['wiki_title'] = strip_tags ($matches[1]);
        }

        if (isset($doubanHtml)) {
        	if(preg_match('/span.*?all hidden.+?>([\w\W]*?)<\/span/',$doubanHtml,$matche)){
        		if (isset($matche[1])) $result['wiki_content'] = trim(strip_tags($matche[1]));
        	}else if(preg_match('/span.*?v:summary.+?>([\w\W]*?)<\/span/',$doubanHtml,$matche)){
        		if (isset($matche[1])) $result['wiki_content'] = trim(strip_tags($matche[1]));
        	}else if(preg_match_all('/<div class="bd">([\s\S]*?)<\/div>/',$doubanHtml,$matche)){
        		if (isset($matche[1][1])) $result['wiki_content'] = trim(strip_tags($matche[1][1]));
        	}
        }
        
        // 匹配基本资料
        switch ($model) {
            case 'actor' :
                $doubanInfoHtml = explode('<div id="content">', $doubanHtml);
                 if (isset($doubanInfoHtml[1])) {
                     $doubanInfoHtml = explode('<div id="opt-bar"', $doubanInfoHtml[1]);
                     $html = preg_replace('/\s+/s', '', $doubanInfoHtml[0]);

                     preg_match('|<span>出生地</span>:([^<]+)<\s*/li>|', $html, $matches);
                     if(isset($matches[1]))  $result['wiki_birthplace'] = $matches[1];

                     preg_match('|<span>职业</span>:([^<]+)<\s*/li>|', $html, $matches);
                     if(isset($matches[1]))  $result['wiki_occupation'] = $matches[1];

                     preg_match('|<span>更多外文名</span>:([^<]+)<\s*/li>|', $html, $matches);
                     if(isset($matches[1]))  $result['wiki_english_name'] = $matches[1];

                     preg_match('#<span>出生日期</span>:([\d|-]+)<\s*/li>#', $html, $matches);
                     if(isset($matches[1]))  $result['wiki_birthday'] = $matches[1];
                 }
                 break;
           case 'teleplay':
           case 'film'    :
                $doubanInfoHtml = explode('<div id="info">', $doubanHtml);
                if (isset($doubanInfoHtml[1])) {
                    $doubanInfoHtml = explode('<div id="interest_sectl">', $doubanInfoHtml[1]);
                    if (isset($doubanInfoHtml[0])) {
                        $html = preg_replace('/\s+/s', '', $doubanInfoHtml[0]);
                        $htmlArr = explode('<br/>', $html);
                        foreach ($htmlArr as $item) {
                            preg_match('|>导演</span>:(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_director'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>主演</span>:(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_starring'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>片长:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_runtime'] = strip_tags($matches[1]);

                            preg_match('|>语言:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_language'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>集数:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_episodes'] = strip_tags($matches[1]);

                            preg_match('|>上映日期:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_released'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>首播日期:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_released'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>又名:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_alias'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>编剧</span>:(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_writer'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>制片国家/地区:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_country'] = str_replace('/', ',', strip_tags($matches[1]));

                            preg_match('|>类型:</span>(.*)|', $item, $matches);
                            if(isset($matches[1]))  $result['wiki_tags'] = str_replace('/', ',', strip_tags($matches[1]));
                        }
                    }
                }
                break;
            default:
                //...
        }
        
        return $result;
    }

    public function executeBaidu($request) {
        $this->baiduWiki('http://baike.baidu.com/view/417109.htm#view_shipin417109_link');
        exit;
    }
    /**
     * 百度百科采集
     * @author luren
     */
    private function baiduWiki($url) {
        $result = array();
        $baikeHtml = file_get_contents($url, false, Common::createStreamContext());
        $baikeInner = explode('<div class="card-info-inner">', $baikeHtml);
        $juqingjieshao  = $baikeInner[0];
        
        preg_match_all('|<table.*?>(.*?)</table></div>|', $baikeInner[1],$baikeInnerHtml);
        if (isset($baikeInnerHtml[0][0])) {
	        //基本资料匹配
            preg_match_all('|<td.*?>(.*?)</td><td.*?>(.*?)</td></tr>|', $baikeInnerHtml[0][0], $matches);
                
            foreach ($matches[1] as $key => $title) {
            	$title = strip_tags($title);
            	$title = str_replace("：", "", $title);
            	if($title == '中文名' || $title == '中文队名' || $title == '影片名称' ){
            		if (! empty($result['wiki_title'])) {
            			$result['wiki_title'] .= ',' .isset($matches[2][$key]) ? str_replace(array('《','》'), '', strip_tags($matches[2][$key])) : '' ;
            		}else{
	            		$result['wiki_title'] =  isset($matches[2][$key]) ? str_replace(array('《','》'), '', strip_tags($matches[2][$key])) : '' ;
            		}
            	}
            	
            	if($title == '其它译名' || $title == '外文名'){
            		 if (! empty($result['wiki_alias'])) {
            		 	 $result['wiki_alias'] .= ',' .isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])):"";
            		 }else{
            		 	 $result['wiki_alias'] = isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;;
            		 }
            	}
            	
            	if ($title == '类型'){
            		$result['wiki_tags'] =  isset($matches[2][$key]) ? rtrim(str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key]))) : '' ;
            	}
            	
            	if($title == '片长'){
            		$result['wiki_runtime'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '导演'){
            		$result['wiki_director'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '编剧'){
            		$result['wiki_writer'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '主演'){
            		$result['wiki_starring'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '出品时间'){
            		$result['wiki_produced'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '上映时间'){
            		$result['wiki_released'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '语言' || $title == '对白语言'){
            		if (! empty($result['wiki_language'])) {
            			$result['wiki_language'] .= ',' .isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])):"";
            		}else{
            			$result['wiki_language'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])):"";
            		}
            	}
            	
            	if($title == '制片地区'){
            		$result['wiki_country'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '出品公司'){
            		$result['wiki_distributor'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '集数'){
            		$result['wiki_episodes'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '主持人'){
            		$result['wiki_host'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '嘉宾'){
            		$result['wiki_guest'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '英文名' || $title == '英文全名' || $title == '外文名' || $title == '外文队名'){
            		if (! empty($result['wiki_english_name'])) {
            			$result['wiki_english_name'] .= ',' .isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])):"";
            		}else{
            			$result['wiki_english_name'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])):"";
            		}
            	}

            	if($title == '别名'){
            		$result['wiki_nickname'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '出生日期'){
            		$result['wiki_birthday'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '出生地'){
            		$result['wiki_birthplace'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '国籍'){
            		$result['wiki_nationality'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '职业'){
            		$result['wiki_occupation'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '身高'){
            		$result['wiki_height'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '体重'){
            		$result['wiki_weight'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '宗教信仰'){
            		$result['wiki_faith'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '出道日期'){
            		$result['wiki_debut'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '球队'){
            		$result['wiki_team'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '位置'){
            		$result['wiki_position'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '场上位置'){
            		$result['wiki_position'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '球衣号码'){
            		$result['wiki_number'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '现任主教练'){
            		$result['wiki_coach'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '拥有者'){
            		$result['wiki_owner'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '总经理'){
            		$result['wiki_manager'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '所属地区'){
            		$result['wiki_city'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '主场馆'){
            		$result['wiki_arena'] =  isset($matches[2][$key]) ? strip_tags($matches[2][$key]) : '' ;
            	}
            	
            	if($title == '球衣颜色'){
            		$result['wiki_color'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            	
            	if($title == '成立时间'){
            		$result['wiki_founded'] =  isset($matches[2][$key]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[2][$key])) : '' ;
            	}
            }
        }
        preg_match('|<div class="card-summary-content"><div class="para">(.*?)</div>|', $juqingjieshao, $matches);
        $result['wiki_content'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

        //分集剧情采集
        $baikePlotContent = explode('class="plot-content-ul"', $baikeHtml, 2);
        if (isset($baikePlotContent[1])) {
            $baikePlotContent = iconv('gb2312', 'UTF-8//IGNORE', $baikePlotContent[1]);
            $baikePlotContent = explode('<div class="plot-pagebar "', $baikePlotContent, 2);
            if (isset($baikePlotContent[0])) {
                $baikePlotItems = explode('</li>', $baikePlotContent[0]);
                $metas = array();
                foreach($baikePlotItems as $item) {
                    preg_match('|<div class="plot_title">(.*)</div>.*<div class="text_pic".*</div>(.*)<div.*></div>|s', $item, $matches);
                    if (empty($matches)) preg_match('|<div.*?>(.*)</div>\s(.*)<|s', $item, $matches);
                    if (!empty($matches)) {
                        $title = $result['wiki_title'].$matches[1];
                        $content = str_replace('宇王', '在', strip_tags($matches[2]));
                        $content = str_replace('夹也', '她', $content);
                        $content = str_replace('卖也', '她', $content);
                        preg_match('|(\d+)|', $matches[1], $mark);
                        $meta = array(
                                'title' =>  $title,
                                'content' => $content,
                                'mark'  => $mark[1]
                            );
                        
                        $metas[] = $meta;
                    }
                }
            }

            $result['wiki_metas'] = $metas;
        }

        return $result;
    }

    /**
     *
     */
    public function executeDeleteStaging(sfWebRequest $request){
        if ($request->isXmlHttpRequest()){
            $meta_id = $request->getParameter('meta_id');
            $mongo = $this->getMondongo();
            $wikiMetaRepos = $mongo->getRepository('wikiMeta');
            $wikiMeta = $wikiMetaRepos->findOneById(new MongoId($meta_id));
            $wikiMeta->delete();
            return $this->renderText(json_encode($wikiMeta->getMark()));
        }
    }

    /**
     *
     * @param sfWebRequest $request 保存栏目分期
     * ly
     */
    public  function executeSaveStaging(sfWebRequest $request){
        if ($request->isXmlHttpRequest()){
                $meta_wiki_id = $request->getParameter('meta_wiki_id');
                $meta_id = $request->getParameter('meta_id');
                $meta_title = $request->getParameter('meta_title');
                $meta_content = $request->getParameter('meta_content');
                $meta_guests = $request->getParameter('meta_guests');
                $meta_mark = $request->getParameter('meta_mark');

                $meta_screenshots = $request->getParameter('meta_screenshots',"");
                if(!empty ($meta_screenshots)){
                    $meta_screenshots = rtrim($meta_screenshots,",");
                    $meta_screenshots = trim($meta_screenshots," ");
                    $meta_screenshots = explode(",",$meta_screenshots);
                }
                $mongo = $this->getMondongo();
                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
                if(!empty ($meta_id) && $meta_id != ""){
                    $wikiMeta = $wikiMetaRepos->findOneById(new MongoId($meta_id));
                }else{
                     $wikiMeta = new WikiMeta();
                }
                
//                if(empty ($wikiMeta)){
//                   $wikiMeta = new WikiMeta();
//                }
                $wikiMeta->setMark($meta_mark);
                $timestamp = strtotime($meta_mark);
                $wikiMeta->setYear(date('Y', $timestamp));
                $wikiMeta->setMonth(date('m', $timestamp));
                $wikiMeta->setWikiId($meta_wiki_id);
                $wikiMeta->setTitle($meta_title);
                $wikiMeta->setContent($meta_content);
                $meta_guests = explode(",", $meta_guests);
                $wikiMeta->setGuests($meta_guests);
                $wikiMeta->setScreenshots($meta_screenshots);
                $wikiMeta->save();
                return $this->renderText(json_encode($wikiMeta->getId()));      
        }
    }

    /**
     * xunsearch索引重建
     * 
     * Enter description here ...
     * @param sfWebRequest $request
     */
    public function executeResetXunSearch(sfWebRequest $request)
    {
    	$wikiId = $request->getParameter('id');
    	$mongo = $this->getMondongo();
    	$wikiRep = $mongo->getRepository('wiki');
    	$wiki = $wikiRep->findOnebyId(new MongoId($wikiId));
    	if ($wiki) {
    		$wiki->rebuildXunSearchDocument();
    		$this->getUser()->setFlash('notice', '维基索引重建成功!');
    	}else {
    		$this->getUser()->setFlash('error', '维基索引重建失败!');
    	}
    	$this->redirect($request->getReferer());
    }
}