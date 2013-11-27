<?php use_stylesheet('auto_complete.css')?>
<div id="content-box">
    <div class="border">
        <div class="padding">
            <div id="toolbar-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                <?php include_partial('toolbar',array('id' => $form->getDocument()->getId()));?>
                    <div class="header icon-48-addedit">
                        <?php if($form->isNew()): ?>
                        新建: <small><small>视频</small></small>
                        <?php else: ?>
                        编辑: <small><small>视频</small></small>
                        <?php endif; ?>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
            <?php include_partial('global/flashes') ?>
            <div id="element-box">
        <div class="t"><div class="t"><div class="t"></div></div></div>
    <div class="m">
    <form action="<?php echo url_for('video/'.($form->isNew() ? 'create' : 'update').(!$form->isNew() ? '?id='.$form->getDocument()->getId() : '')) ?>" method="post" name="adminForm">
        <?php echo $form->renderHiddenFields(); ?>
        <?php if ($form->hasGlobalErrors()): ?>
          <?php echo $form->renderGlobalErrors() ?>
        <?php endif; ?>
        <input type="hidden" id="wiki_model" name="model" value="<?php echo $form->getModelName(); ?>" />
        <div class="col width-60">
            <fieldset class="adminform" id="video-form">
                <legend>基本资料</legend>
                <table class="admintable" style="width:100%">
                <tbody>
                    <tr>
                        <td class="key"><label for="wiki_title">标题</label></td>
                        <td><?php echo $form["title"]->render(array("size" => "50")); ?><?php echo $form['title']->getError() ?></td>
                    </tr>
                    <tr>
                        <td class="key"><label for="wiki_title">维基标题</label></td>
                        <td>
                            <?php echo $form["wiki_title"]->render(array("size" => "50")); ?><?php echo $form['wiki_title']->getError() ?>
                            <div id="load-wiki-list" class="autocomplete" style="display:none"></div>
                        </td>
                    </tr>
                </tbody>
                </table>   
                <?php $config = $form->getDocument()->getConfig();?>
                <?php include_partial($form->getDocument()->getReferer().'Form', array('data' => $config))?>
            </fieldset>
        </div>
    </form>
    <div class="clr"></div>
</div>
<script type="text/javascript">
    function submitform(action){
        if (action) {
            document.adminForm.batch_action.value=action;
        }
        if (typeof document.adminForm.onsubmit == "function") {
            document.adminForm.onsubmit();
        }
        document.adminForm.submit();
    }
</script>

            <div class="b"><div class="b"><div class="b"></div></div></div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    </div>
</div>