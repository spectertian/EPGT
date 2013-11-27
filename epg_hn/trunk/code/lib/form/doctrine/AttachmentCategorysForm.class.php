<?php

/**
 * AttachmentCategorys form.
 *
 * @package    epg
 * @subpackage form
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AttachmentCategorysForm extends BaseAttachmentCategorysForm
{
  public function configure()
  {
      $this->removeFields();

      $categorys = Doctrine::getTable('AttachmentCategorys')->getSelectCategorys();
      $this->widgetSchema['parent_id'] = new sfWidgetFormChoice(
                                            array(
                                                'choices'=> $categorys,
                                                'multiple' => false,
                                                'expanded' => false,
                                            )
                                        );
     $this->validatorSchema['parent_id'] = new sfValidatorChoice(
                                            array(
                                                'choices'=>  array_keys($categorys),
                                            )
                                        );
     $this->widgetSchema['name'] = new sfWidgetFormInputText();

     $this->validatorSchema['name'] = new sfValidatorRegex(
                                            array(
                                                'max_length' => 30,
                                                'min_length' => 2,
                                                'trim' => true,
                                                'required' => true,
                                                'pattern' => '/^[0-9a-zA-Z\x{4E00}-\x{9FA5}]{2,30}$/u'
                                            ),
                                            array(
                                                'max_length' => '合法长度为2～20个字符',
                                                'min_length' => '合法长度为2～20个字符',
                                                'required' => '分类名必须填写！',
                                                'invalid' => '分类名格式错误！'
                                            )
                                        );
     $this->widgetSchema->setLabels(
                                            array(
                                                'parent_id' => '&nbsp;&nbsp;',
                                                'name' => '分类',
                                            )
                                    );

  }

  private function  removeFields()
  {
    unset($this['created_at'],$this['updated_at']);
  }

}
