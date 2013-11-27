<?php

/**
 * Admin filter form.
 *
 * @package    epg
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AdminFormFilter extends BaseAdminFormFilter
{
  public function configure()
  {
      $this->widgetSchema['username'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
      $this->widgetSchema['status'] = new sfWidgetFormChoice(array('choices' => array('' => '', '0' => '锁定', '1' => '正常')));
      
  }
}
