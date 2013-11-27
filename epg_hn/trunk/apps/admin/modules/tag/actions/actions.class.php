<?php

/**
 * tag actions.
 *
 * @package    epg2.0
 * @subpackage tag
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tagActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request)
    {
        $this->mc = trim($request->getParameter('mc', ''));
        $this->pager = new sfDoctrinePager('tags', 20);
        if($this->mc!=''){
            $q=Doctrine::getTable('tags')->createQuery()->where("name like ?","%$this->mc%");
        }else{
            $q=Doctrine::getTable('tags')->createQuery();
        }
		$this->pager->setQuery($q);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->page=$request->getParameter('page',1);        
    }
	public function executeDel(sfWebRequest $request)
	{
		$id = intval($request->getParameter('id'));
        $page=$request->getParameter('page');
		$tags = Doctrine::getTable('tags')->findOneByID($id);	
		if($tags) {
			if($tags->delete())
				$this->getUser()->setFlash("notice",'删除成功!');
			else				
				$this->getUser()->setFlash("error",'删除失败!');
		}else{
			$this->getUser()->setFlash("error",'该记录不存在!');
		}
		$this->redirect("tag/index?page=$page");
	}
	public function executeAdd(sfWebRequest $request)
	{
		if($request->isMethod("POST")) {
            $this->form = new TagsForm();
            $ok=$this->processForm($request, $this->form);
			if($ok) {
				$this->getUser()->setFlash("notice",'操作成功!');
				$this->redirect('tag/index');
			} else { 
				$this->getUser()->setFlash("error",'操作失败，请重试!');
				$this->redirect('tag/index');
			} 
		}else {
            $this->form = new TagsForm();
		}
	} 
	public function executeEdit(sfWebRequest $request)
	{
		if($request->isMethod("POST")){
		    $id = $request->getParameter('id');
            $page = $request->getParameter('page');
			$rs = Doctrine::getTable('tags')->findOneByID($id);
			if(!$rs) {		 
				$this->getUser()->setFlash("error",'该记录不存在!');
			} else {  
                $this->form = new TagsForm($rs);
                $ok=$this->processForm($request, $this->form);
    			if($ok) {
    				$this->getUser()->setFlash("notice",'操作成功!');
    			} else { 
    				$this->getUser()->setFlash("error",'操作失败，请重试!');
    			} 
			}
            $this->redirect('tag/index?page='.$page);
		}else{			
			$this->id = $request->getParameter('id');
            $this->page = $request->getParameter('page');
			$rs = Doctrine::getTable('tags')->findOneByID($this->id);				
			$this->forwardUnless($rs, 'tag', 'index');
            $this->form = new TagsForm($rs);
		}
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
       
}
