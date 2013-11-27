<?php

/**
 * NBA
 *
 * @author pjl
 */
class Wiki_Team_BasketballTeam extends Wiki_Team_Abstract {
    protected $model_name = "basketball_team";
    protected $model_show_name = "篮球球队";
    protected $model_form = "WikiBasketballTeamForm";

    protected $wBasketballTeamFileds = array(
            
        );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wBasketballTeamFileds);

        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wBasketballTeamFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wBasketballTeamFileds, $data);
        parent::setDocumentData($data);
    }
}