<?php

/**
 * wiki people form
 */
class WikiPeopleForm extends WikiForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();

        $this->setWidget("sex", new sfWidgetFormSelect(array('choices' => array('男'=>'男', '女'=>'女', '变性'=>'变性'))));
        $this->setWidget("birthday", new sfWidgetFormInputText());
        $this->setWidget("birthplace", new sfWidgetFormInputText());
        $this->setWidget("occupation", new sfWidgetFormInputText());
        $this->setWidget("english_name", new sfWidgetFormInputText());
        $this->setWidget("nickname", new sfWidgetFormInputText());
        $this->setWidget("nationality", new sfWidgetFormInputText());
        $this->setWidget("height", new sfWidgetFormInputText());
        $this->setWidget("weight", new sfWidgetFormInputText());
        
        $this->setWidget("zodiac", new sfWidgetFormSelect(array(
                    'choices' => array(
                        '' => '',
                        '白羊座'=>'白羊座', '金牛座'=>'金牛座', '双子座'=>'双子座', '巨蟹座'=>'巨蟹座',
                        '狮子座'=>'狮子座', '处女座'=>'处女座', '天秤座'=>'天秤座', '天蝎座'=>'天蝎座',
                        '射手座'=>'射手座', '摩羯座'=>'摩羯座', '水瓶座'=>'水瓶座', '双鱼座'=>'双鱼座',
                    )
                )
            ));
        $this->setWidget("blood_type", new sfWidgetFormSelect(
                    array(
                        'choices' => array(
                            '' => '', 'A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O')
                        )
                    )
                );

        
        $this->setValidator("sex", new sfValidatorString(array("required" => false)));
        $this->setValidator("birthday", new sfValidatorString(array("required" => false)));
        $this->setValidator("birthplace", new sfValidatorString(array("required" => false)));
        $this->setValidator("occupation", new sfValidatorString(array("required" => false)));
        $this->setValidator("zodiac", new sfValidatorString(array("required" => false)));
        $this->setValidator("blood_type", new sfValidatorString(array("required" => false)));
        $this->setValidator("english_name", new sfValidatorString(array("required" => false)));
        $this->setValidator("nickname", new sfValidatorString(array("required" => false)));
        $this->setValidator("nationality", new sfValidatorString(array("required" => false)));
        $this->setValidator("height", new sfValidatorString(array("required" => false)));
        $this->setValidator("weight", new sfValidatorString(array("required" => false)));
    }
}

