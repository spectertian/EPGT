<?php

/**
 * search actions.
 *
 * @package    epg
 * @subpackage search
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
      $this->m = $request->getParameter('m', 'channel');
      $this->q = trim($request->getParameter('q'));
      $this->results = null;
      
      if (! empty($this->q)) {
          switch ($this->m) {
            case 'channel' :
                $this->results = Doctrine::getTable('Channel')->search($this->q, 0, 60);
              break;
            case 'program' :
                $this->results = new XapianPager("Wiki", 60);
                $this->results->setSearchText($this->q);
                $this->results->setPage(1);
                $this->results->init();
              break;
            default :
          //...
        }
      }

  }
}
