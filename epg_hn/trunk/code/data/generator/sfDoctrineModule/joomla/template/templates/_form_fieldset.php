<fieldset class="adminform">
    [?php if ('NONE' != $fieldset): ?]
        <legend>[?php echo __($fieldset, array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</legend>
    [?php endif; ?]
    <table class="admintable">
        [?php foreach ($fields as $name => $field): ?]
            [?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?]
            [?php include_partial('<?php echo $this->getModuleName() ?>/form_field', array(
              'name'       => $name,
              'attributes' => $field->getConfig('attributes', array()),
              'label'      => $field->getConfig('label'),
              'help'       => $field->getConfig('help'),
              'form'       => $form,
              'field'      => $field,
              'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
            )) ?]
        [?php endforeach; ?]
    </table>
</fieldset>

<?php
$close = <<<EOF
<table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td valign="top">
                    <table  class="adminform">
                        <tr>
                            <td>
                                <label for="title">标题</label>
                            </td>
                            <td>
                                <input class="inputbox" type="text" name="title" id="title" size="40" maxlength="255" value="" />
                            </td>
                            <td>
                                <label>发布</label>
                            </td>
                            <td>
                                <input type="radio" name="state" id="state0" value="0" />
                                <label for="state0">否</label>
                                <input type="radio" name="state" id="state1" value="1" />
                                <label for="state1">是</label>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="alias">别名</label>
                            </td>
                            <td>
                                <input class="inputbox" type="text" name="alias" id="alias" size="40" maxlength="255" value="joomla-overview" />
                            </td>

                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <table class="adminform">
                        <tr>
                            <td>内容：
                                <textarea id="content" name="content" cols="75" rows="15" style="width:100%; height:550px;"></textarea>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top" width="320" style="padding: 7px 0 0 5px">
                    <table width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
                        <tr>
                            <td>
                                <strong>State</strong>
                            </td>

                            <td>
                                Published            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Hits</strong>
                            </td>
                            <td>

                                146                <span >
                                    <input name="reset_hits" type="button" class="button" value="Reset" onclick="submitbutton('resethits');" />
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Revised</strong>

                            </td>
                            <td>
                                13 Times            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Created</strong>
                            </td>

                            <td>
                                Monday, 09 October 2006 07:49            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Modified</strong>
                            </td>
                            <td>

                                Sunday, 04 November 2007 15:50            </td>
                        </tr>
                    </table>
                    <div id="content-pane" class="pane-sliders">
                        <div class="panel">
                            <h3 class="jpane-toggler title" id="detail-page"><span>参数 - 内容</span></h3>
                            <div class="jpane-slider content">
                                <table width="100%" class="paramlist admintable" cellspacing="1">
                                    <tr>
                                        <td width="40%" class="paramlist_key"><span class="editlinktip"><label id="detailscreated_by-lbl" for="detailscreated_by" class="hasTip" title="Author::Author Name">作者</label></span></td>
                                        <td class="paramlist_value"><input type="text" name="author" value="" id="author" /></td>
                                    </tr>
                                    <tr>
                                        <td width="40%" class="paramlist_key"><span class="editlinktip"><label id="detailscreated-lbl" for="detailscreated" class="hasTip" title="Created Date::Creation Date of the Article">时间</label></span></td>
                                        <td class="paramlist_value"><input type="text" name="created_at" id="detailscreated" value="" class="inputbox" /><img class="calendar" src="/joomla/templates/system/images/calendar.png" alt="calendar" id="detailscreated_img" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
EOF;
?>