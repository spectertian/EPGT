<?php

/**
 * Tags filter form.
 *
 * @package    epg
 * @subpackage filter
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TagsFormFilter extends BaseTagsFormFilter
{
  public function configure()
  {
      $this->widgetSchema['name'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
  }
}
