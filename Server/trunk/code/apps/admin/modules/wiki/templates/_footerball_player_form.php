<?php include_partial("wiki_editor"); ?>
<?php include_partial("screenshots"); ?>
<?php 
    $sf_user->setAttribute('formcode',mt_rand(1,1000));
    $formcode = $sf_user->getAttribute('formcode');
?>
<div class="m">
    <form action="<?php echo url_for('wiki/'.($form->isNew() ? 'create' : 'update').(!$form->isNew() ? '?id='.$form->getDocument()->getId() : '')) ?>" method="post" name="adminForm">
        <input type='hidden' name='htmlformcode' value='<?php echo $formcode; ?>'>
        <?php echo $form->renderHiddenFields(); ?>
        <?php if ($form->hasGlobalErrors()): ?>
          <?php echo $form->renderGlobalErrors() ?>
        <?php endif; ?>
        <input type="hidden" id="wiki_model" name="model" value="<?php echo $form->getDocument()->getModelName(); ?>" />
        <div class="col width-60">
            <fieldset class="adminform">
                <legend>基本资料</legend>
                <table class="admintable" style="width:100%">
                    <tbody>
                        <tr>
                            <td class="key"><label for="wiki-url">采集维基地址：</label></td>
                            <td>
                                <input type="text" size="60" value="" id="wiki-url">
                                <input type="button" value="采集维基" id="get-wiki-btn" onclick="javascript:getSiteWikiData()">
                            </td>
                        </tr>
                        <tr>
                            <td class="key"><label for="wiki_title">姓名</label></td>
                            <td><?php echo $form["title"]->render(array("size" => "50")); ?><?php echo $form['title']->getError() ?></td>
                        </tr>
                        <tr>
                            <td class="key"><label for="wiki_title">球队</label></td>
                            <td><?php echo $form["team"]->render(array("size" => "50")); ?><?php echo $form['team']->getError() ?></td>
                        </tr>
                        <tr>
                            <td class="key"><label for="wiki_title">位置</label></td>
                            <td><?php echo $form["position"]->render(array("size" => "50")); ?><?php echo $form['position']->getError() ?></td>
                        </tr>
                        <tr>
                            <td class="key"><label for="wiki_title">号码</label></td>
                            <td><?php echo $form["number"]->render(array("size" => "50")); ?><?php echo $form['number']->getError() ?></td>
                        </tr>
                        <tr>
                            <td class="key"><label for="wiki_content">生平介绍</label></td>
                            <td>
                                <?php echo $form["content"]->render(array("class" => "wikiEdit", "cols" => "90", "rows" => "30", "style" => "width:100%")); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </div>

        <div class="col width-40">
            <div class="pane-sliders" id="menu-pane">
                <div class="panel">
                    <h3 id="param-page" class="title jpane-toggler-down"><span>辅助参数</span></h3>
                    <div class="jpane-slider content">
                        <table cellspacing="1" width="100%" class="paramlist admintable">
                            <tbody id="right">
                                <?php include_partial("cover", array("form" => $form)); ?>
                                <tr>
                                    <td width="40%" class="paramlist_key">英文名:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["english_name"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">昵称:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["nickname"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">性别:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["sex"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">生日:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["birthday"]->render(array("size" => "40")); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">籍贯:</td>
                                    <td class="paramlist_value"><?php echo $form["birthplace"]->render(array("size" => "40")); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">国籍:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["nationality"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">星座:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["zodiac"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">血型:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["blood_type"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">身高:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["height"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">体重:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["weight"]->render(); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <div class="clr"></div>
</div>