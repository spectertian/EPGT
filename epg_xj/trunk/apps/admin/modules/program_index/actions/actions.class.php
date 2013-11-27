<?php

require_once dirname(__FILE__).'/../lib/program_indexGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/program_indexGeneratorHelper.class.php';

/**
 * program_index actions.
 *
 * @package    epg
 * @subpackage program_index
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class program_indexActions extends autoProgram_indexActions
{
   public function executeIndex(sfWebRequest $request) {

       $id  = $request->getParameter('id');
       //过滤查询
       if(is_numeric($id)) {
             $this->setFilters(array('channel_id'=>$id));
            
       }

        //清空条件查询
       if($request->getParameter('do') == 'reset') {
           $this->setFilters(array());
       }

       parent::executeIndex($request);
       
    }

    public function executeShow_index(sfWebRequest $req) {
        $this->id   = $req-> getParameter('id', 1);
        $this->date = $req-> getParameter('date', 0);
        //$this-> setLayout('null');
        $this->return = ProgramIndexTable::getTemplateList($this->id);
    }

    public function executeDo_edit(sfWebRequest $req) {
        $id = $req->getParameter('id');
        $this->tem  = Doctrine::getTable('ProgramIndex')->findOneById($id);
    }

    //更新操作
    public function executeAjax_update(sfWebRequest $req) {
        $return = array('code'=>0, '非法请求');

        $name   = $req->getParameter('name');
        $value  = $req->getParameter('value');
        $id     = $req->getParameter('id');
        $return = ProgramIndexTable::ajaxUpdate($id, $name, $value);
        return $this->renderText(json_encode($return));
    }

    /**
     * 删除操作
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_del(sfWebRequest $req) {
        $return = array('code'=>0, '非法请求');
        
        $id     = $req->getParameter('id');
        //删除模板
        ProgramIndexTable::ajaxDel($id);
        return $this->renderText('操作成功');
    }


    public function executeNew(sfWebRequest $request){
        $this->form = $this->configuration->getForm();
        $this->program_index = $this->form->getObject();
  }

  public function executeCreate(sfWebRequest $request){
        $this->form = $this->configuration->getForm();
        $this->program_index = $this->form->getObject();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request){
        $this->program_index = $this->getRoute()->getObject();
        $this->form = $this->configuration->getForm($this->program_index);
        $this->setTemplate('new');
  }

   public function executeDelete(sfWebRequest $request){
        $request->checkCSRFProtection();

        $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

        if ($this->getRoute()->getObject()->delete()){
            $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
        }

        $this->redirect('@program_index');
  }

}
