<?php
/**
 * 人物抽象类
 *
 * @author pjl
 */
abstract class Wiki_People_Abstract extends WikiAbstract {

    /**字段=>类型*/
    protected $wPeopleFileds = array(
            'english_name' => 'string', //英文名
            'nickname' => 'raw', //昵称
            'sex' => 'string', //性别
            'birthday' => 'string', //生日
            'birthplace' => 'string', //出生地,籍贯
            'occupation' => 'string', //职业
            'nationality' => 'string', //国籍
            'zodiac' => 'string', //星座
            'blood_type' => 'string', //血型
            'debut' => 'string', //出道日期
            'height' => 'string', //身高
            'weight' => 'string', //体重
            'screenshots'=>'raw',
        );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wPeopleFileds);
        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wPeopleFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wPeopleFileds, $data);
        parent::setDocumentData($data);
    }

}
