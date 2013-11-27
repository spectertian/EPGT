<?php

/**
 * NBA
 *
 * @author pjl
 */
class Wiki_People_FooterballPlayer extends Wiki_People_Abstract {
    protected $model_name = "footerball_player";
    protected $model_show_name = "足球球员";
    protected $model_form = "WikiFooterballPlayerForm";

    protected $wBasketballPlayerFileds = array(
            'team' => 'string', //球队
            'position' => 'string', //在球队中的位置
            'number' => 'string', //号码
            'colors' => 'string', //球队颜色
            'manager' => 'string', //经理
        );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wBasketballPlayerFileds);

        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wBasketballPlayerFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wBasketballPlayerFileds, $data);
        parent::setDocumentData($data);
    }
}