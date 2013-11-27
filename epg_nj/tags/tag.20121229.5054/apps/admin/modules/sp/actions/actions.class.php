<?php

/**
 * sp actions.
 *
 * @package    epg2.0
 * @subpackage sp
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class spActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    private $channel_code;  
    
    public function executeIndex(sfWebRequest $request)
    { 
        $this->pageTitle = '运营商管理';
        $this->pager = new sfMondongoPager('Sp', 20);
        $this->pager->setFindOptions(array('sort' => array('created_at' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    public function executeListwikis(sfWebRequest $request)
    {
        $this->id = $request->getParameter('id');
        $this->pageTitle = $this->id.'关联wiki';
        $page    = $request->getParameter('page', 1);
        $this->wiki = new XapianPager('Wiki', 20);
        $this->wiki->setSearchText('source:'.$this->id);
        $this->wiki->setPage($page);
        $this->wiki->init();
    }
    public function executeListchannel(sfWebRequest $request)
    {
        $this->id = $request->getParameter('id');
        $this->pageTitle = $this->id.'关联频道';
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Sp');
        $query = array('query' => array( "signal" => $this->id ));
        $rs = $repository->findOne($query);
        if($rs){
            if($rs->getChannels()){
                $channels=$rs->getChannels();
            }else{
                $channels=array('无数据');
            }
        }else{
            $channels=array('无数据');  //如果是array(),查询的是所有的，所以加了一个“无数据”
        }
        //查询相关频道
        $this->pager = new sfDoctrinePager('Channel', 10);
        $q=Doctrine::getTable('channel')->createQuery('c')->whereIn('c.code', $channels);
		$this->pager->setQuery($q);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->page=$request->getParameter('page',1);
    }  
    public function executeAddChannel(sfWebRequest $request)
    {
        $signal = $request->getParameter('signal');
        $channel_code = $request->getParameter('channel_code');
        if($channel_code!=''){
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Sp');
            //$query = array(array( "signal" => $signal ),array('$push'=>array('channels'=>$channel_code)));
            //$repository->update($query);
            $query = array('query' => array( "signal" => $signal ));
            $rs = $repository->findone($query);
            $channels=array();
            if($rs['channels'])
                $channels=$rs['channels'];
            array_push($channels,$channel_code);
            $rs->setChannels($channels);
            $rs->save();
        }else{
            $this->getUser()->setFlash("notice",'请输入频道名称!');
        }
        $this->redirect("sp/listchannel?id=$signal");
    }
    public function isHave($var) 
    {
        if($var!=$this->channel_code) return true;  
    }      
    public function executeDelChannel(sfWebRequest $request)
    {
        
        $signal = $request->getParameter('id');
        $page = $request->getParameter('page',1);
        $this->channel_code = $request->getParameter('code');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Sp');
        $query = array('query' => array( "signal" => $signal ));
        $rs = $repository->findone($query);
        $channels=$rs['channels'];
        foreach($channels as $key => $value){
            if($value==$this->channel_code) 
                array_splice($channels,$key,1);  //这个删除后key值是连续的
                //unset($channels[$key]);        //这个删除后key值是不连续的
        }
        //array_filter($channels,"isHave");
        $rs->setChannels($channels);
        $rs->save();
        $this->redirect("sp/listchannel?id=$signal&page=$page");
    }                         
    public function executeAdd(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
            $this->form = new SpForm();
            $ok=$this->processForm($request, $this->form);
    		if($ok) {
    			$this->getUser()->setFlash("notice",'操作完成!');
    			$this->redirect('sp/index');
    		} else { 
    			$this->getUser()->setFlash("error",'操作失败，请重试!');
    			$this->redirect('sp/index');
    		} 
    	}else {
            $this->form = new SpForm();
    	}
    } 
    public function executeEdit(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $id = $request->getParameter('id');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Sp');
            $query = array('query' => array( "signal" => $id ));
            $rs = $repository->findOne($query);
            //print_r($rs);
            //return sfView::NONE;
            $this->form = new SpForm($rs);
            $ok=$this->processForm($request, $this->form);
    		if($ok) {
    			$this->getUser()->setFlash("notice",'操作完成!');
    			$this->redirect('sp/index');
    		} else { 
    			$this->getUser()->setFlash("error",'操作失败，请重试!');
    			$this->redirect('sp/index');
    		} 
    	}else {
            $id = $request->getParameter('id');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Sp');
            $query = array('query' => array( "signal" => $id ));
            $rs = $repository->findOne($query);
            $this->form = new SpForm($rs);
    	}
    }     
    public function executeDelete(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Sp');
        $query = array( "signal" => $id );
        $repository->remove($query);
        $this->getUser()->setFlash('notice', '已删除！');
        $this->redirect('sp/index');
    }    
    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
            $theme = $form->save();
            return $theme;
        }else{
            return false;
        }
    }  
    public function executeLoadChannel(sfWebRequest $request)
    {
        $query = $request->getParameter('query');
        $this->channels = Doctrine::getTable('Channel')->createQuery()->where('name like ?', "%$query%")->limit(15)->execute();
    }        
}
