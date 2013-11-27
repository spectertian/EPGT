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
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  /**
   * 
   * @param sfRequest $request
   */
  public function executeShow(sfRequest $request)
  {
      $id = $request->getParameter('id');
      $program_id = $request->getParameter('program_id');
      
      $mondongo = $this->getMondongo();
      $wiki_respository = $mondongo->getRepository('Wiki');

      if(is_numeric($id)){
   
          $this->wiki = $wiki_respository->findOne(
                                             array(
                                                'query' => array(
                                                    'wiki_id' => (int)$id,
                                                ),
                                             )
                                          );
      }else{
         $this->wiki = $wiki_respository->findOneById(new MongoId($id));
      }
//      $mondongo = $this->getMondongo();
//      $program_respository = $mondongo->getRepository('program');
//      $this->program = $program_respository->findOneById(new MongoId($program_id));
      $template = $this->wiki->getModel();

      $this->setTemplate($template);
  }
}