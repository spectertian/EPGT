<?php

/**
 * program_template actions.
 *
 * @package    epg
 * @subpackage program_template
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class program_templateActions extends autoprogram_templateActions
{

    /**
     * 列出所有模板
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_template(sfWebRequest $req) {
        $return = array('code' => '0', 'msg' => '没有模板');
        $index_id = $req->getParameter('id', 0);
        $return = ProgramTemplateTable::getTemplateList($index_id);
        return $this->renderText(json_encode($return->toArray()));
    }

    /**
     * 根据字段名称设置字段值
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_update(sfWebRequest $req) {
        $return = array('code' => 0, '非法请求');
        
        $name = $req->getParameter('name');
        $value = $req->getParameter('value');
        $id = $req->getParameter('id');

        $return = ProgramTemplateTable::ajaxUpdate($id, $name, $value);        
        return $this->renderText(json_encode($return));
    }

    /**
     * 根据指定ID显示模板
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeShow_template(sfWebRequest $req) {
        $this->index_id = $req->getParameter('id', 0);
        $this->channel_id = $req->getParameter('channel_id', 0);
        $this->date = $req->getParameter('date', 0);
        if(!preg_match('/\d{4}-\d{2}-\d{2}/', $this->date))
        {
            return $this->renderText('您未在【节目单】选择节目单日期');
        }
        $this->return = ProgramTemplateTable::getTemplateList($this->index_id);
        //return $this->renderText(json_encode($return));
    }

    public function executeIndex(sfWebRequest $request) {

        $this->id = $request->getParameter('id');
        $this->name = $request->getParameter('name');
        $this->time = $request->getParameter('time');

        $channel_id  = $this->getFilters('channel_id');
        $p_id  = $this->getFilters('p_id');
        //过滤查询
        if (is_numeric($this->id)) {
            $this->setFilters(array('p_id' => $this->id));
        }

       //清空条件查询
       if($request->getParameter('do') == 'reset') {
           $this->setFilters(array());
       }
       
        //批量添加
        elseif (!empty($this->name)){
            $filters = $this->getFilters('p_id');
            if(array_key_exists('p_id', $filters) && !empty($filters['p_id'])){
                $id = ProgramTemplateTable::save_data($filters['p_id'], $this->name, $this->time);
                $return = array('code' => 0, 'id' => 0, 'create' => date('Y-m-d H:i:s'), 'msg'=>'对不起,【节目模板】不存在~_~');
                if($id['id'] != 0){
                    $return['id']    = $id['id'];
                    $return['code']  = 1;
                    $return['title'] = $id['title'];
                    $return['msg']   ='添加成功';
                }
            }else{
                $return = array('code'=>0, 'msg'=>'【节目模板】未选择');
            }
            return $this->renderText(json_encode($return));
        }
        parent::executeIndex($request);
    }

    /**
     * 批量添加模板
     * @param sfWebRequest $req
     */
    public function executeTem_add(sfWebRequest $req){
        $this->id = $req->getParameter('id', 0);
        $this->class = 'none';

        if ($this->getRequest()->isMethod('post')) {
            $name = $req->getParameter('name');
            $time = $req->getParameter('time');
            ProgramTemplateTable::Tem_add($name, $time, $this->id);
            $this->getUser()->setFlash('notice', '操作已执行.');
            $this->redirect('@program_index');
        }
    }

    /**
     * 节目单模板导入到节目单
     * @param sfWebRequest $req
     */
    public function executeProgram_to_template(sfWebRequest $req) {
        $this->channel_id   = $req->getParameter('channel_id');
        $this->name         = $req->getParameter('name');
        $this->date         = $req->getParameter('date');
        $this->time         = $req->getParameter('time');
        $this->wiki_id      = $req->getParameter('wiki_id');
        $save   = ProgramTemplateTable::template_to_program($this->channel_id, $this->wiki_id, $this->time, $this->name, $this->date);
        $this->getUser()->setFlash('notice', $save['msg']);
        $this->redirect('@program');
    }

    /**
     * 自动完成节目名称
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAuto_complete_name(sfWebRequest $req) {
        
        $this->query = $req->getParameter('query', 'index');
        $program    = Doctrine::getTable('Program')->auto_complete($this->query);
        return $this->renderText($program);
    }

}
