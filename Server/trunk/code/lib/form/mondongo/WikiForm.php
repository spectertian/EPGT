<?php

/**
 * Wiki Form.
 */
class WikiForm extends BaseWikiForm {

    public function configure() {
        unset($this['slug'],$this['source'], $this['rev'], $this['wiki_id'], $this['html_cache'], $this['model'], $this['wiki_id']);
        $this->setWidget("tags", new WidgetFormInputTextArray());
        $this->setWidget("cover", new sfWidgetFormInputHidden());
        $this->setWidget('content', new sfWidgetFormTextarea());
        $this->validatorSchema['title'] = new sfValidatorString(array('required' => true), array('required' => '<br /><span style="color:red">* 请输入标题<span>'));
        $this->validatorSchema['content'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['cover'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['tags'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['like_num'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['comment_tags'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['dislike_num'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['watched_num'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['aspect'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['tvsou_id'] = new sfValidatorString(array('required' => false));
    }

    protected function unset_fildes() {
        unset(
            $this['created_at'], $this['updated_at'], $this['token'],$this['has_video'], $this['do_date']
        );
    }

    public function save() {
        if (!$this->isValid()) {
            throw new LogicException('Cannot save the sfMondongoForm if it is not valid.');
        }

        $this->document->fromArray($this->getValues());
        $this->document->save();

        return $this->getDocument();
    }

}