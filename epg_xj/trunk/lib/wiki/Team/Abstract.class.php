<?php
/**
 * 球队抽象类
 *
 * @author pjl
 */
abstract class Wiki_Team_Abstract extends WikiAbstract {

    /**字段=>类型*/
    protected $wTeamFileds = array(
            'english_name' => 'string', //英文名
            'nickname' => 'string', //昵称
            'founded' => 'string', //建队时间
            'arena' => 'string', //球馆
            'city' => 'string', //所在城市
            'coach' => 'string', //主教练
            'owner' => 'string', //拥有者
            'manager' => 'string', //经理
            'color' => 'string', //颜色
        );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wTeamFileds);
        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wTeamFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wTeamFileds, $data);
        parent::setDocumentData($data);
    }

}

