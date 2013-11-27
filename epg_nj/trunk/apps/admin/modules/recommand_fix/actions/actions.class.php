<?php
/**
 * recommand_fix actions.
 * @package    epg2.0
 * @subpackage recommand_fix
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class recommand_fixActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request)
    { 
        $this->arr_type=array('vod'=>'新上线','Series'=>'电视剧','Movie'=>'电影','Sports'=>'体育','Entertainment'=>'综艺','Cartoon'=>'动漫','Culture'=>'文化','News'=>'综合');
        $this->type = $request->getParameter('type','');
        $query=array();
        if($this->type!=''){
            $query['type']=$this->type;
        }
        $this->pageTitle = '固定推荐管理';
        $this->pager = new sfMondongoPager('RecommandFix', 20);
        $this->pager->setFindOptions(array('query'=>$query,'sort' => array('_id' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    public function executeAdd(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
            $this->form = new RecommandFixForm();
            $ok=$this->processForm($request, $this->form);
    		if($ok) {
    			$this->getUser()->setFlash("notice",'添加成功!');
    			$this->redirect('recommand_fix/index');
    		} else { 
    			$this->getUser()->setFlash("error",'添加失败，请重试!');
    			$this->redirect('recommand_fix/index');
    		} 
    	}else {
            $this->form = new RecommandFixForm();
    	}
    } 
    public function executeEdit(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $id = $request->getParameter('id');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('RecommandFix');
            $query = array('query' => array( "_id" => new MongoId($id) ));
            $rs = $repository->findOne($query);
            $this->form = new RecommandFixForm($rs);
            
            $ok=$this->processForm($request, $this->form);
    		if($ok) {
    			$this->getUser()->setFlash("notice",'操作完成!');
    			$this->redirect('recommand_fix/index');
    		} else { 
    			$this->getUser()->setFlash("error",'操作失败，请重试!');
    			$this->redirect('recommand_fix/index');
    		} 
    	}else {
            $this->id = $request->getParameter('id');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('RecommandFix');
            $query = array('query' => array( "_id" => new MongoId($this->id) ));
            $rs = $repository->findOne($query);
            $this->form = new RecommandFixForm($rs);
    	}
    }     
    public function executeDelete(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('RecommandFix');
        $query = array( "_id" => new MongoId($id) );
        $repository->remove($query);
        $this->getUser()->setFlash('notice', '已删除！');
        $this->redirect('recommand_fix/index');
    }    
    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
            $formOk = $form->save();
            return $formOk;
        }else{
            return false;
        }
    }  
}
