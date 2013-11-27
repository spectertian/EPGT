<?php

/**
 * attention actions.
 *
 * @package    epg
 * @subpackage attention
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class attentionActions extends sfActions
{
    public function executeAjax_create(sfWebRequest $request) {
        if (!$request->isXmlHttpRequest()) return $this->renderText (json_encode(array('result' => false)));

        $this->getResponse()->setContentType('application/x-json');
        $user_id = $this->getUser()->getAttribute('user_id');
        $pid = $request->getPostParameter('pid');

        if(!$user_id || !$pid ) return $this->renderText (json_encode(array('result' => false)));

        $attention = Doctrine::getTable('Attention')->createQuery()
                    ->where('user_id = ?', $user_id)
                    ->andWhere('pid =?', $pid)
                    ->fetchOne();

        if(!$attention) {
            $attention = new Attention();
            $attention->setUserId($user_id);
            $attention->setPid($pid);
            $attention->save();
        }

        return $this->renderText (json_encode(array('result' => true)));
    }
}
