<?php

require_once dirname(__FILE__).'/../lib/program_extGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/program_extGeneratorHelper.class.php';

/**
 * program_ext actions.
 *
 * @package    epg
 * @subpackage program_ext
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class program_extActions extends autoProgram_extActions
{
    /**
     * 主方法
     * @param sfWebRequest $request
     * @author ward
     * @final 2010-08-28 11:53
     */
    public function executeIndex(sfWebRequest $request){        
        $style         = $request->getParameter('style');
        $date_from     = $request->getParameter('date_from');
        $date_to       = $request->getParameter('date_to');
        $date          = array('from' => $date_from, 'to' => $date_to);
        $filters_style = array();
        $filters_date  = array();

//        if(empty($style)) {
//            $style = $this->getUser()->getAttribute('style');
//        }
//        if(empty($date_from)) {
//
//            $date_from = $this->getUser()->getAttribute('date_from');
//        }
//        if(empty($date_to)) {
//            $date_to = $this->getUser()->getAttribute('date_to');
//        }
//        if(empty($style)) {
//            $style = $this->getUser()->getAttribute('style');
//        }
        
        if(!empty($style)) {
            $filters_style    = array('style' => $style);
            $this->getUser()->setAttribute('admin_style', $style);
        }

        if(!empty($date)) {
            $filters_date    = array('date' =>$date);
            $this->getUser()->setAttribute('admin_date_from',$date_from);
            $this->getUser()->setAttribute('admin_date_to',$date_to);
        }
        $filters    = array_merge($filters_date, $filters_style);
        $this->setFilters($filters);
        parent::executeIndex($request);
    }
}
