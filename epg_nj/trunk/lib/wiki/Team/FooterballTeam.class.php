<?php

/**
 * NBA
 *
 * @author pjl
 */
class Wiki_Team_FooterballTeam extends Wiki_Team_Abstract {
    protected $model_name = "footerball_team";
    protected $model_show_name = "足球球队";
    protected $model_form = "WikiFooterballTeamForm";

    protected $wFooterballTeamFileds = array(
            
        );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wFooterballTeamFileds);

        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wFooterballTeamFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wFooterballTeamFileds, $data);
        parent::setDocumentData($data);
    }
}