<div class="toolbar" id="toolbar">
    <table class="toolbar">
        <tr>
            <td class="button">
                <a href="#" onclick="javascript:submitform('batchDelete')" class="toolbar">
                    <span class="icon-32-delete" title="Delete"></span>
                    <?php echo __('Delete', array(), 'sf_admin') ?>
                </a>
            </td>
            <td class="button">
                <a href="<?php echo url_for('attachment_categorys/select_categorys') ?>?height=100&width=350&TB_iframe=true" class="toolbar thickbox">
                    <span class="icon-32-trash" title="Change"></span>
                    <?php echo __('修改分类', array(), 'sf_admin') ?>
                </a>
            </td>
        </tr>
    </table>
</div>