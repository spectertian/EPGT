<?php

/**
 * ContentInject document.
 */
class ContentInject extends \BaseContentInject
{
     /**
     * 手动set调用
     * @param <type> $field
     * @param <type> $value
     */     
    public function setPropety($field, $value) {
        if (null == $value && null == $this->data["fields"][$field]) {
            return ;
        }
        if (!array_key_exists($field, $this->fieldsModified)) {
            $this->fieldsModified[$field] = $this->data['fields'][$field];
        } elseif ($value === $this->fieldsModified[$field]) {
            unset($this->fieldsModified[$field]);
        }
        $this->data["fields"][$field]   = $value;
    }
}