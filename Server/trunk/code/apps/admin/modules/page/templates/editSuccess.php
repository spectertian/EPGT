<div id="content-box">
    <div class="border">
        <div class="padding">
            <div id="toolbar-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                <?php include_partial('toolbar',array('id' => $form->getDocument()->getId()));?>
                <div class="header icon-48-addedit">编辑: <small><small>页面模板 - <?php echo $page->getPagename()?></small></small></div>
                <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
            <?php include_partial('global/flashes') ?>
            <div id="element-box">
        <div class="t"><div class="t"><div class="t"></div></div></div>
        <div class="m">
        <form action="<?php echo url_for('page/update?id='.$form->getDocument()->getId()) ?>" method="post" name="adminForm">
            <?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <input type="hidden" id="wiki_model" name="model" value="<?php echo $form->getModelName(); ?>" />
            <div class="col width-100">
                <fieldset class="adminform" id="page-form">
                    <legend></legend>
                    <table class="admintable" style="width:100%">
                    <tbody>
                        <tr>
                            <td class="key"><?php echo $form["pagename"]->render(array('readonly' => true)); ?></td>
                            <td><a href="<?php echo url_for('page/history?pagename='.$page->getPagename());?>">查看历史更新记录</a></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo $form["content"]->render(array('cols' => '100%', 'rows' => 50)); ?><?php echo $form['content']->getError() ?></td>
                        </tr>
                    </tbody>
                    </table>
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