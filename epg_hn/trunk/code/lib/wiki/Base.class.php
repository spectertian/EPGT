<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Wiki基类
 *
 * @author ward
 */
abstract class WikiAbstract extends Wiki{
    protected $model_name;
    protected $model_show_name;
    protected $model_form;

    public function  __construct() {
        //parent::__construct();
    }

    /**
     * 返回模型中文名，如：“电影”“电视剧”“栏目”等
     * @return string
     * @author zhigang
     */
    public function getDisplayName() {
        return $this->model_show_name;
    }

    /**
     * 获取当前对象短名称，如：film、teleplay
     * @return string
     * @author zhigang
     */
    public function getModelName() {
        return $this->model_name;
    }

    /**
     * 获取当前对象 Form 实例
     * @return WikiForm
     * @author zhigang
     */
    public function getForm() {
        $form_class = $this->model_form;
        $form = new $form_class($this);

        return $form;
    }

    /**
     * php类型转换mongo类型
     * @param <Array> $attr
     * @param <Array> $fields
     * @return Array
     * @author ward
     */
    public function arrayConversionPHPtoMongo($attr, $fields) {

        foreach ($attr as $key => $value) {
            if (isset ($fields[$key])) {
                $fields[$key]   = $this->parseTypePHPToMongo($value, $fields[$key]);
            }
        }

        return $fields;
    }

    /**
     * mongo类型转换php类型
     * @param <Array> $attr
     * @param <Array> $fields
     * @return Array
     * @author ward
     */
    public function arrayConversionMongoToPHP($attr, $data) {
        foreach ($attr as $key => $value) {
            if (isset($data[$key])) {
                $this->data['fields'][$key] = $this->parseTypeMongoToPHP($value, $data[$key]);
            }
        }
    }

    /**
     * 数据库到php类型转换
     * @param <String> $type
     * @param <Object> $value
     * @return <Objects>
     * @author ward
     */
    public function parseTypeMongoToPHP($type, $value) {

        switch ($type) {

            case 'string':
                $value  = (string) $value;
                break;

            case 'int':
                $value  = (int) $value;
                break;

            case 'array':
                $value  = unserialize($value);
                break;

            case 'date':
                $date = new \DateTime();
                $date->setTimestamp($value->sec);
                $value  = $date;
                break;

            case 'raw':
                $value  = $value;
                break;

            default:
                $value  = $value;
                break;
        }
        return $value;
    }
    /**
     * 数据库到php类型转换
     * @param <String> $type
     * @param <Object> $value
     * @return <Objects>
     * @author ward
     */
    public function parseTypePHPToMongo($type, $value) {

        switch ($type) {

            case 'string':
                $value  = (string) $value;
                break;

            case 'int':
                $value  = (int) $value;
                break;

            case 'array':
                $value  = serialize($value);
                break;

            case 'date':
                if ($value instanceof \DateTime) {
                    $value = $value->getTimestamp();
                } elseif (is_string($value)) {
                    $value = strtotime($value);
                }
                $value = new \MongoDate($value);
                break;

            case 'raw':
                $value  = $value;
                break;

            default:
                $value  = $value;
                break;
        }
        return $value;
    }

    /**
     * 移除数组键值
     * @param Array $array
     * @return Array
     * @author ward
     */
    public function removeArrayValue($array) {
        foreach ($array as $key => $value) {
            $array[$key]    = null;
        }
        return $array;
    }

    /**
     * 初始化动态字段
     * @param <Array> $data
     * @author ward
     */
    public function fieldsMerge($data) {
        $data   = $this->removeArrayValue($data);
        $this->data['fields']   = array_merge($this->data['fields'], $data);
    }

}
