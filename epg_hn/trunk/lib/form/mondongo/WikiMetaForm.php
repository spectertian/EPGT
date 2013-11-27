<?php

/**
 * WikiMeta Form.
 */
class WikiMetaForm extends BaseWikiMetaForm
{
        public function configure() {
            $this->validatorSchema['mark'] = new sfValidatorString(array('required' => true),array("required" => "必填项"));
            $this->validatorSchema['content'] = new sfValidatorString(array('required' => true), array("required" => "必填项"));
            $this->validatorSchema['guest'] = new sfValidatorString(array('required' => false));
    }
}