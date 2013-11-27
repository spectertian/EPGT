<?php

/**
 * ProgramIndex filter form.
 *
 * @package    epg
 * @subpackage filter
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProgramIndexFormFilter extends BaseProgramIndexFormFilter
{
  public function configure()
  {
      $this->widgetSchema['title'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
  }
}
