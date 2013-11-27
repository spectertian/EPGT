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
      $this->id     = $request->getParameter('id',0);
      $this->wiki   = Doctrine::getTable('Wiki')->findOneById($this->id);
      
      if($this->wiki){
          if($this->wiki->getStyle() =='movie'){
              $this->setTemplate('movie');
          }elseif($this->wiki->getStyle() =='tv') {
              $this->setTemplate('tv');
          }else{
              return $this->renderText('0');
          }
          
      }else{
        return $this->renderText('0');
      }
  }
}
