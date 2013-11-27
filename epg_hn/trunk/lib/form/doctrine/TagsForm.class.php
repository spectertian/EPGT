<?php

/**
 * Tags form.
 *
 * @package    epg
 * @subpackage form
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TagsForm extends BaseTagsForm
{
  public function configure()
  {
        $this->disableCSRFProtection(); //关闭_csrf_token
        unset(
                $this['id'],$this['created_at'], $this['updated_at']
        );
        $this->widgetSchema->setLabel('name', '名称：');
        $this->validatorSchema['name'] = new sfValidatorString(array('required' => true));
  }
}
