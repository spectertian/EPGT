<?php

/**
 * WidgetFormInputTextArray represents an HTML input tag.
 * 继承自 sfWidgetFormInputText，将数组类型整合为用逗号分隔的字符串。
 * 主要用于 tag 等 mongodb 存储的类型
 *
 * @package    5i.tv
 * @author     zhigang
 */
class WidgetFormInputTextArray extends sfWidgetFormInputText {

    /**
     * Renders the widget.
     *
     * @param  string $name        The element name
     * @param  string $value       The value displayed in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        if (is_array($value)) {
            $value = implode(",", $value);
        }
        return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
    }

}
