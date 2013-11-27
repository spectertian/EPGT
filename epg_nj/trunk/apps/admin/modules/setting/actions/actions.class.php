<?php

/**
 * setting actions.
 *
 * @package    epg2.0
 * @subpackage setting
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class settingActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request)
    {
    
    }
    public function executeEdit(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $key = $request->getParameter('key');
            $value = $request->getParameter('value');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => $key ));
            $setting = $repository->findOne($query);
            if(!$setting){
                $setting=new Setting();
                $setting->setKey($key);
            }
            $setting -> setValue($value);
            $setting -> save();
			$this->getUser()->setFlash("notice",'操作完成!');
			$this->redirect($request->getReferer());
    	}else {
            $this->key = $request->getParameter('key','hotsearchkey');
            switch($this->key){
                case 'hotsearchkey':
                    $this->PageTitle='热门搜索关键词';
                    break;
                case 'sensitiveWords':
                    $this->PageTitle='敏感词';
                    break;
            }
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => $this->key ));
            $rs = $repository->findOne($query);
            if($rs){
                $this->value=$rs->getValue();
            }else{
                $this->value='';
            }
    	}
    } 

    public function executeRecommend(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $vod = $request->getParameter('vod');
            $vodRelated = $request->getParameter('vodRelated');
            $live = $request->getParameter('live');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            //点播
            $query = array('query' => array( "key" => 'vodWho' ));
            $setting = $repository->findOne($query);
            if(!$setting){
                $setting=new Setting();
                $setting->setKey('vodWho');
            }
            $setting -> setValue($vod);
            $setting -> save();
            //相关推荐
            $query = array('query' => array( "key" => 'vodRelatedwho' ));
            $setting = $repository->findOne($query);
            if(!$setting){
                $setting=new Setting();
                $setting->setKey('vodRelatedwho');
            }
            $setting -> setValue($vodRelated);
            $setting -> save();
            //直播
            $querya = array('query' => array( "key" => 'liveWho' ));
            $settinga = $repository->findOne($querya);
            if(!$settinga){
                $settinga=new Setting();
                $settinga->setKey('liveWho');
            }
            $settinga -> setValue($live);
            $settinga -> save();
            
			$this->getUser()->setFlash("notice",'操作完成!');
			$this->redirect($request->getReferer());
    	}else {
            $this->PageTitle='推荐系统切换';
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            //获取点播
            $query = array('query' => array( "key" => 'vodWho' ));
            $rs = $repository->findOne($query);
            if($rs){
                $this->vodwho=$rs->getValue();
            }else{
                $this->vodwho='center';
            }
            //获取相关推荐
            $query = array('query' => array( "key" => 'vodRelatedwho' ));
            $rs = $repository->findOne($query);
            if($rs){
                $this->vodRelatedwho=$rs->getValue();
            }else{
                $this->vodRelatedwho='center';
            }
            //获取直播
            $querya = array('query' => array( "key" => 'liveWho' ));
            $rsa = $repository->findOne($querya);
            if($rsa){
                $this->livewho=$rsa->getValue();
            }else{
                $this->livewho='tcl';
            }
    	}
    }   
    
    public function executeWordsCheck(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $status = $request->getParameter('status');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => 'words' ));
            $setting = $repository->findOne($query);
            if(!$setting){
                $setting = new Setting();
                $setting->setKey('words');
            }
            $setting -> setValue($status);
            $setting -> save();

			$this->getUser()->setFlash("notice",'操作完成!');
			$this->redirect($request->getReferer());
    	}else {
            $this->PageTitle='敏感词审核状态管理';
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            //获取点播
            $query = array('query' => array( "key" => 'words' ));
            $rs = $repository->findOne($query);
            if($rs){
                $this->status=$rs->getValue();
            }else{
                $this->status='自动审核';
            }
    	}
    }  
    
    public function executeAutohidden(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $value = $request->getParameter('value');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => 'autohidden' ));
            $setting = $repository->findOne($query);
            if(!$setting){
                $setting = new Setting();
                $setting->setKey('autohidden');
            }
            $setting -> setValue($value);
            $setting -> save();

			$this->getUser()->setFlash("notice",'操作完成!');
			$this->redirect($request->getReferer());
    	}else {
            $this->PageTitle='自动隐藏时间设置';
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            //获取隐藏时间
            $query = array('query' => array( "key" => 'autohidden' ));
            $rs = $repository->findOne($query);
            if($rs){
                $this->value=$rs->getValue();
            }else{
                $this->value=15000;
            }
    	}
    } 
    
    public function executePage(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $page = intval($request->getParameter('page',-1));
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');

            $query = array('query' => array( "key" => 'page' ));
            $setting = $repository->findOne($query);
            if(!$setting){
                $setting=new Setting();
                $setting->setKey('page');
            }
            $setting -> setValue($page);
            $setting -> save();
            
			$this->getUser()->setFlash("notice",'操作完成!');
			$this->redirect($request->getReferer());
    	}else {
            $this->PageTitle='应急页面切换';
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            
            $query = array('query' => array( "key" => 'page' ));
            $rs = $repository->findOne($query);
            if($rs){
                $this->page=$rs->getValue();
            }else{
                $this->page=-1;
            }
    	}
    }    
}
