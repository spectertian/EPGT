<div id="content-box">
    <div class="border">
        <div class="padding">
            <div id="toolbar-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                <div id="toolbar" class="toolbar">
                    <table class="toolbar">
                        <tbody>
                            <tr>
                                <td id="toolbar-publish" class="button">
                                    <a class="toolbar" onclick="javascript:submitform()" href="#"><span title="设置为最新版本" class="icon-32-save"></span>设置为最新版本</a>
                                </td>
                                <td class="button"><a href="javascript:history.go(-1)"><span class="icon-32-cancel"></span>返回</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="header icon-48-addedit">
                    查看: <small><small><?php echo $page->getPagename()?> | 版本 - <?php echo $page->getVersion()?> | 作者 - <?php echo $page->getAuthor()?></small></small>
                </div>
                <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
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
                            <td colspan="2"><?php echo $form["pagename"]->render(array('readonly' => true)); ?></td>
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