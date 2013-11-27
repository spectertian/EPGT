<?php

require_once dirname(__FILE__) . '/../lib/tagsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/tagsGeneratorHelper.class.php';

/**
 * tags actions.
 *
 * @package    epg
 * @subpackage tags
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tagsActions extends autoTagsActions {

    public function executeAjax_show(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $this->getResponse()->setContentType('application/x-json');
            $tags = Doctrine::getTable('Tags')->findAll(Doctrine_Core::HYDRATE_ARRAY);

            foreach($tags as $k => $tag) {
                unset($tag['created_at']);
                unset($tag['updated_at']);
            }

            return $this->renderText(json_encode(array('status' => 'ok', 'tags' => $tags)));
        }
    }

    /**
     * 自动完成节目名称
     * @param sfWebRequest $req
     * @return <type>
     * @author ward
     */
    public function executeAuto_complete_tags(sfWebRequest $req) {

        $query = $req->getParameter('query', '');
        $return = '';
        if(!empty($query)) {
            $query    = explode(',', $query);
            $this->len      = count($query) - 1;
            $program    = Doctrine::getTable('Tags')->auto_complete($query[$this->len]);
            if(!empty($program)) {
                unset ($query[$this->len]);
                $this->str  = implode(',', $query);
                foreach ($program as $rs){
                    if (empty($this->str)) {
                        $return .= '<li>' . $rs->getName().'</li>';
                    }else{
                        $return .= '<li>' . $this->str . ',' . $rs->getName().'</li>';
                    }
                }
            }
        }
        return $this->renderText('<ul>'.$return.'</ul>');
    }

}
