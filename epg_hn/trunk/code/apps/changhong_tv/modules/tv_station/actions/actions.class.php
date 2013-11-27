<?php

/**
 * tv_station actions.
 *
 * @package    epg
 * @subpackage tv_station
 * @author     Mozi Tekz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tv_stationActions extends sfActions
{
 /**
  * 显示本地频道
  *
  * @param sfRequest $request A request object
  * @author ward
  * @final 2010-08-31 15:27
  */
  public function executeShow_local_channel(sfWebRequest $request) {
      $city         = $this->getUser()->getAttribute('user_city');
      $this->tv     = '';
      $provice      = $this->getUser()->getAttribute('province');
      $md5          = array(md5($provice), md5($city));
      if(!empty($city)) {
          $channel_ids  = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
          if(count($channel_ids) == 0) {
              $this->tv = '';
          }else{
              $this->tv   = Doctrine::getTable('Channel')->findInTvStaionId($channel_ids);
          }
      }
  }

/**
 * 显示卫视
 * @param sfWebRequest $request
 * @author ward
 * @final 2010-08-31 15:27
 */
  public function executeShow_tv(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type', 'tv');
    $this->tv   = Doctrine::getTable('Channel')->findListByType($this->type);
  }
}
