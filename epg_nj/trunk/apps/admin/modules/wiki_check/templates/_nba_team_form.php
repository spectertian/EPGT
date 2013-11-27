<?php include_partial("wiki_editor"); ?>
<?php include_partial("screenshots"); ?>
<div class="m">
    <form action="<?php echo url_for('wiki/'.($form->isNew() ? 'create' : 'update').(!$form->isNew() ? '?id='.$form->getDocument()->getId() : '')) ?>" method="post" name="adminForm">
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
                            <td class="key"><label for="wiki_title">球队名称</label></td>
                            <td><?php echo $form["title"]->render(array("size" => "50")); ?><?php echo $form['title']->getError() ?></td>
                        </tr>
                        <tr>
                            <td class="key"><label for="wiki_content">球队介绍</label></td>
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
                                    <td width="40%" class="paramlist_key">球队英文名:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["english_name"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">建队时间:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["founded"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">球馆:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["arena"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">所在城市:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["city"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">主教练:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["coach"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">部区:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["conference"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">分区:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["division"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">拥有者:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["owner"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">经理:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["manager"]->render(); ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="paramlist_key">队服颜色:</td>
                                    <td class="paramlist_value" width="70%"><?php echo $form["color"]->render(); ?></td>
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