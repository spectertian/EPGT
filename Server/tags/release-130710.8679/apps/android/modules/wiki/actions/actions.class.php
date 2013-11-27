<?php

/**
 * wiki actions.
 *
 * @package    epg
 * @subpackage wiki
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wikiActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
//  public function executeIndex(sfWebRequest $request)
//  {
//
//  }
  /**
   * 维基显示页面
   * @param sfWebRequest $request
   * @author luren
   */
  public function executeShow(sfWebRequest $request) {
    if (false === strpos($request->getReferer(), 'wiki')) {
        $this->getUser()->setAttribute('wikiback',  $request->getReferer());
    }
    
    $wiki_id = $request->getParameter('id');
    $province = $this->getUser()->getUserProvince();
    $mongo = $this->getMondongo();
    $wiki_repository = $mongo->getRepository('Wiki');
    $program_repositroy = $mongo->getRepository('Program');
    $this->wiki = $wiki_repository->findOneById(new MongoId($wiki_id));
    $this->forward404Unless($this->wiki);

    switch($this->wiki->getModel()) {
        case "film":
        case "teleplay":
        case 'television':
            //获取一个星期的节目单
            $programs = $program_repositroy->getUserRelateProgramByDate($wiki_id, $province, date('Y-m-d', time()), date('Y-m-d', time() + (7*86400)));
            $this->channel_programs = array();
            $this->channels = array();
            if (!is_null($programs)) {
                foreach ($programs as $program) {
                    if (!key_exists($program->getChannelCode(), $this->channels)) {
                        $this->channels[$program->getChannelCode()] = $program->getChannel();
                    }
                    $this->channel_programs[$program->getChannelCode()][] = $program;
                }
            }
            break;
        case "actor":
            $this->setTemplate('actor');
            break;
        default :
            //..
    }

  }
}
