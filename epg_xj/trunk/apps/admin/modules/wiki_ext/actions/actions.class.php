<?php

require_once dirname(__FILE__).'/../lib/wiki_extGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/wiki_extGeneratorHelper.class.php';

/**
 * wiki_ext actions.
 *
 * @package    epg
 * @subpackage wiki_ext
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wiki_extActions extends autoWiki_extActions
{
    public function executeIndex(sfWebRequest $req) {
        $id = $req->getParameter('id');
        if ($id > 0)
        {
            $this->setFilters(array('wiki_id' => $id));
        }

       //清空条件查询
       if($req->getParameter('do') == 'reset') {
           $this->setFilters(array());
       }
        parent::executeIndex($req);
    }

    /**
     * 批量添加
     */
    public function executeInsert_value(sfWebRequest $req) {
        $user    =  $this->getFilters('wiki_id');
        if(array_key_exists('wiki_id', $user) && !empty($user['wiki_id']))
        {
            $title      = $req->getParameter('name');
            $wiki_key   = $req->getParameter('wiki_key');
            $wiki_value = $req->getParameter('wiki_value');
            $return     = WikiExtTable::saveData($title,$user['wiki_id'],$wiki_key, $wiki_value);
            $return['create']   = date('Y-m-d H:i:s');
            if($return['id'] == 0 ) {
                $return = array('code'=>0 ,'msg'=>'未设置【分类ID】');
            }
            return $this->renderText(json_encode($return));
        }else{
            return $this->renderText(json_encode(array('code'=>0,'msg'=>'请设置【维基ID】')));
        }
    }

    public function executeAjax_update(sfWebRequest $req) {
        $name   = $req->getParameter('name');
        $value  = $req->getParameter('value');
        $id  = $req->getParameter('id');
        $return = WikiExtTable::updateDataByKey($id, $name, $value);
        return $this->renderText(json_encode($return));
    }

    /**
     * 删除电视剧剧情
     * @param sfWebRequest $req
     * @author ward
     * @final 2010-09-02 14:30
     */
    public function executeAjax_drama_delete(sfWebRequest $req) {
        $id   = $req->getParameter('id');
        try {
            $wiki_ext   = Doctrine::getTable('WikiExt')->findOneById($id);
        } catch (Doctrine_Connection_Mysql_Exception $ex) {
            return array('code' => 0, 'msg' => '数据库连接异常,请稍后再试');
        }

        if ($wiki_ext) {
            $wiki_ext->delete();
            $return = array('code' => 1, 'msg' => '删除成功');
        }else{
            $return = array('code' => 0, 'msg' => '剧情不存在');
        }
        return $this->renderText(json_encode($return));
    }
}
