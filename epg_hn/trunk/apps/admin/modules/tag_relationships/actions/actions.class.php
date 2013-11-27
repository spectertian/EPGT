<?php

require_once dirname(__FILE__) . '/../lib/tag_relationshipsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/tag_relationshipsGeneratorHelper.class.php';

/**
 * tag_relationships actions.
 *
 * @package    epg
 * @subpackage tag_relationships
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tag_relationshipsActions extends autoTag_relationshipsActions {

    public function executeAjax_add(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $this->getResponse()->setContentType('application/x-json');
            $tag_ids = $request->getParameter('tag_ids');
            $program_id = $request->getParameter('program_id');
            $tag_ids = explode(',', $tag_ids);
            $tag_ids = array_unique($tag_ids);

            foreach ($tag_ids as $k => $tag_id) {
                $tag_relation = new TagRelationships();
                $tag_relation->setProgramId($program_id);
                $tag_relation->setTagId($tag_id);
                $tag_relation->save();
            }

            return $this->renderText(json_encode(array('status' => 'ok', 'message' => '添加成功')));
        }
    }

    public function executeAjax_delete(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $this->getResponse()->setContentType('application/x-json');
            $id = $request->getParameter('id');
            $tag_relation = Doctrine::getTable('TagRelationships')->findOneById($id);
            if ($tag_relation) {
                $tag_relation->delete();
                return $this->renderText(json_encode(array('status' => 'ok', 'message' => '添加成功')));
            }

            return $this->renderText(json_encode(array('status' => 'error', 'message' => '删除失败')));
        }
    }

}
