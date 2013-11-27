<?php

/**
 * Channel form.
 *
 * @package    epg
 * @subpackage form
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ChannelForm extends BaseChannelForm
{
    
    public function configure()
    {
        unset(
       		$this['id'],$this['hot'],$this['tvsou_get'],$this['tvsou_update'],$this['editor_update'],$this['created_at'], $this['updated_at'], $this['live'],$this["live_config"]
        );
        $sex_selects = array( '1' => '发布', '0' => '隐藏');

        $this -> widgetSchema['publish'] = new sfWidgetFormSelect(
                array(
                    'choices' => $sex_selects
                )
        );
        $this -> validatorSchema['publish'] = new sfValidatorChoice(
                array(
                    'choices' => array_keys($sex_selects)
                ),
                array(
                    'invalid' => '请选择分类'
                )
        );
        
        /*
        $this->validatorSchema->setPostValidator(
            new sfValidatorDoctrineUnique(array('model' => 'channel', 'column' => array('code')),array('invalid'=>'该频道code已存在'))
        );
        */
           
      
      	 $this->validatorSchema['code'] = new sfValidatorString(array('max_length' => 50,'required' => true),array('required'=>'<font color="red">频道code不为空</font>','max_length' => '最长50个字符',));  
                
         $this->validatorSchema['memo'] = new sfValidatorString(array('max_length' => 255,'required' => false),array('max_length' => '最长255个字符',));
         $this->validatorSchema['name'] = new sfValidatorString(array('max_length' => 20,'required' => true),array('required'=>'<font color="red">频道名称不为空</font>','max_length' => '最长20个字符',));
         $this->validatorSchema['province'] = new sfValidatorString(array('max_length' => 40,'required' => false),array('required'=>'频道所属省不为空','max_length' => '最长40个字符',));
         $this->validatorSchema['city'] = new sfValidatorString(array('max_length' => 40,'required' => false),array('max_length' => '最长20个字符',));
    }
}
