<?php

/**
 * WikiExt filter form.
 *
 * @package    epg
 * @subpackage filter
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class WikiExtFormFilter extends BaseWikiExtFormFilter
{
  public function configure()
  {
      $this->widgetSchema['wiki_key'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
      $this->widgetSchema['wiki_value'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
      $this->widgetSchema['title'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
  }
}
