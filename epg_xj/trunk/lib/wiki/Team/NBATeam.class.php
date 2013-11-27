<?php

/**
 * NBA
 *
 * @author pjl
 */
class Wiki_Team_NBATeam extends Wiki_Team_Abstract {
    protected $model_name = "nba_team";
    protected $model_show_name = "NBA球队";
    protected $model_form = "WikiNBATeamForm";

    protected $wNBATeamFileds = array(
            'conference' => 'string', //部区
            'division' => 'string', //分区
        );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wNBATeamFileds);

        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wNBATeamFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wNBATeamFileds, $data);
        parent::setDocumentData($data);
    }
}