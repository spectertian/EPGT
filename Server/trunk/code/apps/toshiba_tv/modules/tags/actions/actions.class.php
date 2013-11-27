<?php

/**
 * tags actions.
 *
 * @package    epg
 * @subpackage tags
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tagsActions extends sfActions {

    public function executeAjax_get_all_tags(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
//            $this->tags = Doctrine::getTable('Tags')->findAll();
        }
    }

    public function executeAjax_get_tag_programs(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $tag = $request->getPostParameter('tag', '');
            $date = $request->getPostParameter('date', date('Y-m-d'));
            $city = $this->getUser()->getAttribute('user_city');

            $channels = Doctrine::getTable('Channel')->getReceiveChannels($city);

            $this->programs = Doctrine::getTable('Program')->getDayProgramsByTag($tag, $date, $channels);

            if(!$this->programs || !$this->programs->count()){
                return $this->renderText(0);
            }
        }
    }

}
