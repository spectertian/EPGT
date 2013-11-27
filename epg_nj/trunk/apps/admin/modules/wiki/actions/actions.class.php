<?php

/**
 * mwiki actions.
 *
 * @package    epg
 * @subpackage mwiki
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wikiActions extends sfActions {

    /**
     * 维基管理列表
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request) {
        $page    = $request->getParameter('page', 1);
        
        $this->q = trim($request->getGetParameter('q', ''));
        $this->c = trim($request->getGetParameter('c', ''));
        $this->m = $request->getGetParameter('m', 'all');
        $this->video = trim($request->getGetParameter('video', ''));
        $this->tag = $request->getGetParameter('tag', '');
        $query_arr=array();
        if($this->q!=''){
            $query_arr['$or']=array(array('title'=>new MongoRegex("/.*$this->q.*/i")),array('alias'=>new MongoRegex("/.*$this->q.*/i")));
        }
        if($this->c!='')
            $query_arr['admin_id']=intval($this->c);
        if($this->m!='all')
            $query_arr['model']=$this->m;
        if($this->tag!='')
            $query_arr['tags']=$this->tag;
        if($this->video==1){
            $query_arr['has_video']=array('$gt'=>0);
        }elseif($this->video==-1){
            $query_arr['has_video']=array('$lte'=>0);
        }
               
        $query = array('query'=>$query_arr,'sort' => array('_id' => -1));

        $this->query=$query;
        $this->pageTitle    = '维基列表' ;
        $this->wiki = new sfMondongoPager('Wiki', 20);
        $this->wiki->setFindOptions($query);

        $this->wiki->setPage($page);
        $this->wiki->init();
        $this->users = Doctrine::getTable('admin')->findAll();
    }

    /**
     * 未审核wiki
     * @param sfWebRequest $request
     */
    public function executeUnaudited(sfWebRequest $request) {
        $page    = $request->getParameter('page', 1);  
        $query = array('query'=>array('verify'=>0),'sort' => array('_id' => -1));

        $this->query=$query;
        $this->wiki = new sfMondongoPager('Wiki', 20);
        $this->wiki->setFindOptions($query);
        $this->wiki->setPage($page);
        $this->wiki->init();
    }
    /**
     * 创建新内容，显示表单页面
     * @param sfWebRequest $request
     */
    public function executeNew(sfWebRequest $request) {
        $model_name = $request->getParameter("model");
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Wiki');
        $wiki_model = $repository->factory($model_name);
        $this->form = $wiki_model->getForm();
    }

    /**
     * 创建 Wiki, 保存至数据库
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeCreate(sfWebRequest $request) {
        $model_name = $request->getParameter("model");
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Wiki');
        $wiki_model = $repository->factory($model_name);
        $this->form = $wiki_model->getForm();
        if ($this->processForm($request, $this->form)) {
             $this->getUser()->setFlash('notice', '维基添加成功');
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
    public function executeUpdate(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('wiki');
        $id = $request->getParameter('id', null);
        $wiki = $repository->findOneById(new MongoId($id));
        $this->redirectUnless($wiki, 'wiki/index');
        $this->form = $wiki->getForm();

        if ($this->processForm($request, $this->form)) {
            $this->getUser()->setFlash('notice', '维基保存成功');
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
    public function executeEdit(sfWebRequest $request) {
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
    }

    /**
     * 表单处理保存
     * @param array $wiki
     * @param sfForm $form
     * @return sfForm
     */
    protected function processForm(sfWebRequest $request, sfForm $form) {
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
    public function executeBatch(sfWebRequest $request) {
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
                case 'verify':
                        $this->batchVerify($ids);
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
    private function batchRecommend($ids) {  
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
    public function  executeRecommend(sfWebRequest $request) {
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
    private function batchDelete($ids) {
        $mongo = $this->getMondongo();
        $wikiRepos = $mongo->getRepository('Wiki');
        
        foreach ($ids as $id) {
            $wiki = $wikiRepos->findOneById(new MongoId($id));       
            if (!is_null($wiki)) $wiki->delete();
        }
        
        $this->getUser()->setFlash('notice', '已删除所选择的维基!');
    }

    /**
     * 批量审核 
     * @param <array> $ids
     */
    private function batchVerify($ids) {
        $mongo = $this->getMondongo();
        $wikiRepos = $mongo->getRepository('Wiki');
        
        foreach ($ids as $id) {
           $wiki = $wikiRepos->findOneById(new MongoId($id));       
           if($wiki){
                $wiki->setVerify(1);
                $wiki->save();
           }
        }
        
        $this->getUser()->setFlash('notice', '已审核完毕!');
    } 
    /**
     * 单个审核 
     * @param <array> $id
     */
    public function executeVerify(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wikiRepos = $mongo->getRepository('Wiki');
        $id = $request->getParameter('id');
        $wiki = $wikiRepos->findOneById(new MongoId($id));       
        if($wiki){
            $wiki->setVerify(1);
            $wiki->save();
        }
        $this->getUser()->setFlash('notice', '审核完毕!');
        $this->redirect($request->getReferer());
    }     
    /**
     * 批量删除维基
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeDelete(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wikiRepos = $mongo->getRepository('Wiki');
        $wiki = $wikiRepos->findOneById(new MongoId($request->getParameter('id')));
        
        if (!is_null($wiki)) $wiki->delete();
        $this->getUser()->setFlash('notice', '维基已删除!');
        $this->redirect($request->getReferer());
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
        preg_match('|<h1>(.*)</h1>|', $doubanHtml, $matches);
        if(isset($matches[1])) {
            $result['wiki_title'] = strip_tags ($matches[1]);
        } else {
            preg_match('|<title>(.*) \(豆瓣\)</title>|', $doubanHtml, $matches);
            if(isset($matches[1]))  $result['wiki_title'] =  $matches[1];
        }

        // 截取剧情简介
        $summaryHtml = explode('<div class="related_info"><h2  >',$doubanHtml);
        if (isset($summaryHtml[1])) {
            $summaryHtml = explode('</div>', $summaryHtml[1]);
            if(isset ($summaryHtml[0])) {
                if (strpos($summaryHtml[0], '<span class="all hidden">')){
                    $content = explode('<span class="all hidden">', $summaryHtml[0]);
                    if (isset($content[1])) $result['wiki_content'] = strip_tags (str_replace ('<br/>', "\n\n", $content[1]));
                } else {
                    preg_match('#<span property="v:summary">(.*?)<span class="pl">#', $summaryHtml[0], $matches);
                    if (isset($matches[1])) {
                        $result['wiki_content'] = strip_tags(str_replace ('<br/>', "\n\n", $matches[1]));
                    } else {
                        preg_match('#<span property="v:summary">(.*?)</span>#', $summaryHtml[0], $matches);
                        if (isset($matches[1]))  $result['wiki_content'] = strip_tags(str_replace ('<br/>', "\n\n", $matches[1]));
                    }
                }  
            }
        }
        
        // 截取人物简介
        $summaryHtml = explode('<div id="intro"',$doubanHtml);
        if (isset($summaryHtml[1])) {
            $summaryHtml = explode('</div>', $summaryHtml[1]);
            if(isset ($summaryHtml[1])) {
                if (strpos($summaryHtml[1], '<span class="all hidden">')){
                    preg_match('|<span class="all hidden">(.*?)</span>|', $summaryHtml[1], $matches);
                    if (isset($matches[1])) $result['wiki_content'] = str_replace ('<br/>', "\n\n", $matches[1]);
                } else {
                    $result['wiki_content'] = str_replace ('<br/>', "\n\n", $summaryHtml[0]);
                }
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

                            preg_match('|>编剧:</span>(.*)|', $item, $matches);
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
        $baikeInnerHtml = explode('<div class="card-info-inner">', $baikeHtml);
        //基本资料匹配
        if (isset ($baikeInnerHtml[1])) {
            $baikeInnerHtml = explode('<dl class="holder1" id="catalog-holder-0">', $baikeInnerHtml[1]);
            if (isset($baikeInnerHtml[0])) {
                $html = preg_replace('/\s+/s', '', $baikeInnerHtml[0]);
                $html = iconv('gb2312', 'UTF-8//IGNORE', $html);

                preg_match('|<td.*?>中文名：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_title'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>中文队名：</td>.*?>(.*?)</td>|', $html, $matches);
                if (isset($matches[1]) ) {
                    if (! empty($result['wiki_title'])) {
                        $result['wiki_title'] .= ',' .isset($matches[1]) ? strip_tags($matches[1]) : '' ;
                    } else {
                        $result['wiki_title'] = isset($matches[1]) ? strip_tags($matches[1]) : '' ;;
                    }
                }

                preg_match('|<td.*?>其它译名：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_alias'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>外文名：</td>.*?>(.*?)</td>|', $html, $matches);
                if (isset($matches[1]) ) {
                    if (! empty($result['wiki_alias'])) {
                        $result['wiki_alias'] .= ',' .str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    } else {
                        $result['wiki_alias'] = str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    }
                }

                preg_match('|<td.*?>类型：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_tags'] =  isset($matches[1]) ? rtrim(str_replace(array('剧，','/','、','，'), ',', strip_tags($matches[1])),'剧') : '' ;

                preg_match('|<td.*?>片长：</td>.*?>(\d+).*?</td>|', $html, $matches);
                $result['wiki_runtime'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>导演：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_director'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>编剧：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_writer'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>主演：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_starring'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>出品时间：</td>.*?>(\d+).*?</td>|', $html, $matches);
                $result['wiki_produced'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>上映时间：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_released'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>语言：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_language'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>对白语言：</td>.*?>(.*?)</td>|', $html, $matches);
                if (isset($matches[1]) ) {
                    if (! empty($result['wiki_language'])) {
                        $result['wiki_language'] .= ',' .str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    } else {
                        $result['wiki_language'] = str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    }
                }

                preg_match('|<td.*?>制片地区：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_country'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>出品公司：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_distributor'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>集数：</td>.*?>(\d+).*?</td>|', $html, $matches);
                $result['wiki_episodes'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>主持人：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_host'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>嘉宾：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_guest'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>英文名：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_english_name'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>英文全名：</td>.*?>(.*?)</td>|', $html, $matches);
                if (isset($matches[1]) ) {
                    if (! empty($result['wiki_english_name'])) {
                        $result['wiki_english_name'] .= ',' .str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    } else {
                        $result['wiki_english_name'] = str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    }
                }

                preg_match('|<td.*?>外文名：</td>.*?>(.*?)</td>|', $html, $matches);
                if (isset($matches[1]) ) {
                    if (! empty($result['wiki_english_name'])) {
                        $result['wiki_english_name'] .= ',' .str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    } else {
                        $result['wiki_english_name'] = str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    }
                }

                preg_match('|<td.*?>外文队名：</td>.*?>(.*?)</td>|', $html, $matches);
                if (isset($matches[1]) ) {
                    if (! empty($result['wiki_english_name'])) {
                        $result['wiki_english_name'] .= ',' .str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    } else {
                        $result['wiki_english_name'] = str_replace(array('/','、','，'), ',', strip_tags($matches[1]));
                    }
                }

                preg_match('|<td.*?>别名：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_nickname'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>出生日期：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_birthday'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>出生地：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_birthplace'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>国籍：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_nationality'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>出生地：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_region'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>职业：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_occupation'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>身高：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_height'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>体重：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_weight'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>宗教信仰:：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_faith'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>出道日期：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_debut'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>球队:：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_team'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>位置：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_position'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>场上位置：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_position'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>球衣号码：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_number'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>现任主教练：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_coach'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>拥有者：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_owner'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>总经理：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_manager'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>所属地区：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_city'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>主场馆：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_arena'] =  isset($matches[1]) ? strip_tags($matches[1]) : '' ;

                preg_match('|<td.*?>球衣颜色：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_color'] =  isset($matches[1]) ? str_replace(array('/','、','，','；'), ',', strip_tags($matches[1])) : '' ;

                preg_match('|<td.*?>成立时间：</td>.*?>(.*?)</td>|', $html, $matches);
                $result['wiki_founded'] =  isset($matches[1]) ? str_replace(array('/','、','，'), ',', strip_tags($matches[1])) : '' ;
            }
        }

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


}