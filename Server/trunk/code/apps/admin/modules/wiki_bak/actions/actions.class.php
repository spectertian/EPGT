<?php

require_once dirname(__FILE__).'/../lib/wikiGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/wikiGeneratorHelper.class.php';

/**
 * wiki actions.
 *
 * @package    epg
 * @subpackage wiki
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wikiActions extends autoWikiActions
{
    public function executeIndex(sfWebRequest $request) {

       $id  = $request->getParameter('id');
       //过滤查询
       if(is_numeric($id)) {
             $this->setFilters(array('id'=>$id));
       }
        //清空条件查询
       if($request->getParameter('do') == 'reset') {
           $this->setFilters(array());
       }
        parent::executeIndex($request);
    }
    
    public function executeEdit(sfWebRequest $request) {
        $style  = $request->getParameter('style');
        $id     = $request->getParameter('id');
        $url    = $request->getUri();
        if(empty($style)) {
            if($style == '') {
                $wiki   = Doctrine::getTable('Wiki')->findOneById($id);
                $style  = $wiki->getStyle();
                if ($style == '') {
                    $this->forward404('没有设置维基');
                }

            }

            $this->redirect($url.'?style='.$style);
        }
        parent::executeEdit($request);
    }


  protected function processForm(sfWebRequest $request, sfForm $form) {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

      try {
        $wiki = $form->save();
      } catch (Doctrine_Validator_Exception $e) {

        $errorStack = $form->getObject()->getErrorStack();

        $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
        foreach ($errorStack as $field => $errors) {
            $message .= "$field (" . implode(", ", $errors) . "), ";
        }
        $message = trim($message, ', ');

        $this->getUser()->setFlash('error', $message);
        return sfView::SUCCESS;
      }

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $wiki)));

      if ($request->hasParameter('_save_and_add')) {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

        $this->redirect('@wiki_new');
      }else{
        $this->getUser()->setFlash('notice', $notice);
        /***
         * add your code here
         */
        //修改开始
        $ext                = $request->getParameter('ext');
        $wiki_tags          = $request->getParameter('wiki_tags');
        $wiki_alias         = $request->getParameter('wiki_alias');
        $drama              = $request->getParameter('drama');
        $screenshots        = $request->getParameter('screenshots');
        $nba_team           = $request->getParameter('nba_team');
        $nba_player         = $request->getParameter('nba_player');
        $fb_international   = $request->getParameter('fb_international');
        $fb_resume          = $request->getParameter('fb_resume');
        $statistics         = $request->getParameter('statistics');
        $statistics_man     = $request->getParameter('statistics_man');

        //全角替换成半角
        //$wiki_tags      = str_replace('，', ',', $wiki_tags);
        //$wiki_alias      = str_replace('，', ',', $wiki_alias);

        //分割成数组
        $wiki_tags      = explode(',', $wiki_tags);

        //电视剧分集
        if($request->hasParameter('drama')) {
            $wiki->setDramaAll($drama);
        }
        
        //电影电视剧照
        if($request->hasParameter('screenshots')) {
            $wiki->setScreenshotAll($screenshots);
        }

        //nba球队
        if($request->hasParameter('nba_team')) {
            $wiki->setNbaTeamPost($nba_team);
        }

        //nba球员
        if($request->hasParameter('nba_player')) {
            $wiki->setNbaPlayerPost($nba_player);
        }

        //国际足球,球队
        if($request->hasParameter('fb_international')){
            $wiki->setFbInternational($fb_international);
        }
        //国际足球 球员个人履历
        if($request->hasParameter('fb_resume')){
            $wiki->jsonSaveAttribute('fb_resume', $fb_resume);
        }
        
        //国际足球 球员赛季资料统计
        if($request->hasParameter('statistics')){
            $wiki->jsonSaveAttribute('statistics', $statistics);
        }
        //国际足球 球员个人技术统计
        if($request->hasParameter('statistics_man')){
            $wiki->jsonSaveAttribute('statistics_man', $statistics_man);
        }

        if ($request->hasParameter('ext')) {
            //设置wiki_ext表
            $wiki->setAttributes($ext);
        }
        
        //设置维基标签
        $save   = $wiki->setTags($wiki_tags);
        $save   = $wiki->setAlias($wiki_alias);
        if ($save['code'] == 0) {
            $this->getUser()->setFlash('error', $save['msg']);
        }
        /**
         * code end
         */
        $this->redirect('@wiki');
      }
    }else{
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }
    /**
     * 删除管理的wiki_ext表记录
     * @param sfWebRequest $request
     */
    public function executeBatchDelete(sfWebRequest $request) {
        $id = $request->getParameter('ids');
        Doctrine::getTable('WikiExt')->createQuery()->delete()->whereIn('wiki_id', $id)->execute();
        parent::executeBatchDelete($request);
    }

    public function executeUpdates(sfWebRequest $request) {
        $this->wiki = $this->getRoute()->getObject();
        $this->form = $this->configuration->getForm($this->wiki);

        $wiki_id     = $request->getParameter('wiki_id');
        $wiki_key    = $request->getParameter('wiki_key');
        $wiki_value  = $request->getParameter('wiki_value');

        //WikiExtTable::wiki_ext_update($this->wiki->getId(), $wiki_id, $title, $wiki_key, $wiki_value);

        $this->form->save   = 1;

        $this->processForm($request, $this->form);
        
        $this->setTemplate('edit');
     }

    public function executeSelect(sfWebRequest $request) {
        
    }

    //自动完成
    public function executeAjax(sfWebRequest $request) {
        $query   = $request->getParameter('query');
        $where   = $request->getParameter('identifier');
        $result  = '';
        $arr     = '';
        $html    = strstr($query, '/');
        
        if (!empty($html)) {
                $arr    = explode('/', $query);
                $len    = count($arr)-1;
                $query  = $arr[$len];
                unset($arr[$len]);
                $arr    = implode('/', $arr);
        }else{
            $arr    = $query;
        }

        $wiki    = Doctrine::getTable('Wiki')->auto_complete($query);
        if($wiki)
        {
            
        }
        $str     = '<ul>' . $result . '</ul>';
        return $this->renderText($str);
    }

    /**
     * 删除维基扩展属性
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeExt_del(sfWebRequest $request) {
        $id     = $request->getParameter('id');
        $ext    = Doctrine::getTable('WikiExt')->findOneById($id);
        $msg    = array('code' => 1, 'msg'=> '删除成功');
        if(!$ext)
        {
            $msg    = array('code' => 0, 'msg'=> '记录不存在');
        }
        return $this->renderText(json_encode($msg));
    }

    /**
     * 维基name自动完成
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeAuto_complete(sfWebRequest $request){
        $list = '';
        $this->id       = $request->getParameter('identifier');
        $this->query    = $request->getParameter('query');
        //自动完成
        $wiki           = Doctrine::getTable('Wiki')->auto_complete($this->query);
        return $this->renderText($wiki);
    }

    /**
     * 自动完成wiki title
     * @param sfWebRequest $request
     */
    public function executeAuto_complete_wiki_title(sfWebRequest $request) {
        $this->query   = $request->getParameter('query');
        $where         = $request->getParameter('identifier');
        $wiki          = Doctrine::getTable('wiki')->auto_complete_title($this->query);
        return $this->renderText($wiki);
    }
    
    /**
     * 自动完成WikiExt wiki_value字段
     * @param sfWebRequest $request
     */
    public function executeAuto_complete_wiki_ext_wiki_value(sfWebRequest $request) {
        $this->query   = $request->getParameter('query');
        $where         = $request->getParameter('identifier');
        $wiki          = Doctrine::getTable('wiki')->auto_complete_wiki_ext_wiki_value($this->query);
        return $this->renderText($wiki);
    }
    
    public function executeAjax_sort_delete(sfWebRequest $request) {
        $id   = $request->getParameter('id',0);
        $sort = $request->getParameter('sort',0);
        Doctrine::getTable("Wiki")->removeAttribute($id, 'screenshots', $sort);
        return sfView::NONE;
    }
    
     /**
     * 自动完成wiki 球队
     * @param sfWebRequest $request
     */
    public function executeAuto_complete_nba_team(sfWebRequest $request) {
        $this->query   = $request->getParameter('query');
        $where         = $request->getParameter('identifier');
        $wiki          = Doctrine::getTable('wiki')->auto_complete_by_style($this->query, $where);
        return $this->renderText($wiki);
    }
     /**
     * 根据ID获取标题
     * @param sfWebRequest $request
     */
    public function executeAjax_get_title_by_id(sfWebRequest $request) {
        $id      = $request->getParameter('id',0);
        $rs = Doctrine::getTable('Wiki')->findOneById($id);
        if(!$rs){
            $rs = new Wiki();
        }
        return $this->renderText($rs->getTitle());
    }
     /**
     * 根据标题获取ID
     * @param sfWebRequest $request
     */
    public function executeAjax_get_id_by_title(sfWebRequest $request) {
        $title = $request->getParameter('title','index');
        $rs = Doctrine::getTable('Wiki')->findOneByTitle($title);
        if(!$rs){
            $rs = new Wiki();
        }
        return $this->renderText($rs->getId());
    }
}
