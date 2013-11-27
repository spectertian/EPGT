<?php

use Mondongo\Type\DateType;

/**
 * stat actions.
 *
 * @package    epg2.0
 * @subpackage stat
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statActions extends sfActions
{
  /**
   * get mongodb handler
   * gao
   */
  private static $mdb = null;
  private function getMdb(){
    if(null == self::$mdb){
      $mongo = $this->getMondongo();
      return self::$mdb = $mongo->getRepository("TransferStatistics");
    }else{
      return self::$mdb;
    }
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $mongo    = self::getMdb();
    $statArr  = array();
    $startstr = ' 00:00:00';
    $endstr   = ' 23:59:59';
    
    if($request->isMethod("POST")){
      $this->startdate = $request->getPostParameter('startdate');
      $this->enddate   = $request->getPostParameter('enddate');
      //echo $startdate;exit;
      $dateArr = self::dateFormat($this->startdate, $this->enddate);
      foreach($dateArr as $date){
        $startdate = new MongoDate(strtotime($date.$startstr));
        $enddate   = new MongoDate(strtotime($date.$endstr));
        $statArr[$date]  = $mongo->count(array('created_at'=>array('$gte' => $startdate ,'$lte' => $enddate)));
        $this->statcount = $statArr;
        $this->stattotal = $this->stattotal+$statArr[$date];
      }
    }else{
      $today = date('Y-m-d',time());
      $startdate = new MongoDate(strtotime($today.$startstr));
      $enddate   = new MongoDate(strtotime($today.$endstr));
      $statArr[$today] = $mongo->count(array('created_at' => array('$gte' => $startdate ,'$lte' => $enddate)));
      $this->statcount = $statArr;
      $this->stattotal = $this->stattotal+$statArr[$date];
    }
    return sfView::SUCCESS;
  }
  
  private function dateFormat($startdate , $enddate)
  {
    $countday = (strtotime($enddate)-strtotime($startdate))/86400;
    $dateArr  = array();
    if($countday){
      $dateArr[] = $startdate;
      $tmptime   = strtotime($startdate);
      for($i=1;$i<$countday;$i++){
        $tmptime   = $tmptime+86400;
        $dateArr[] = date('Y-m-d',$tmptime);
      }
      $dateArr[] = $enddate;
    }else{
      $dateArr[] = $enddate;
    }
    return $dateArr;
  }
}
