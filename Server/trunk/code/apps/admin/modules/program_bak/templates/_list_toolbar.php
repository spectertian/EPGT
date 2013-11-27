<div class="toolbar" id="toolbar">
    <table class="toolbar">
        <tr>
            <td class="button" id="toolbar-publish">
                <a href="<?php echo url_for('@default?module=program_index&action=show_index&id=1&TB_iframe=true') ?>" class="toolbar thickbox">
                    <span class="icon-32-new" title="添加"></span>
                    <?php echo __('添加', array(), 'sf_admin') ?>
                </a>
            </td>
            <td class="button" id="toolbar-publish">
                <a href="#" onclick="javascript:submitform('batchPublish')" class="toolbar">
                    <span class="icon-32-publish" title="发布"></span>
                    <?php echo __('发布', array(), 'sf_admin') ?>
                </a>
            </td>
            <td class="button" id="toolbar-publish">
                <a href="#" onclick="javascript:submitform('batchUnPublish')" class="toolbar">
                    <span class="icon-32-unpublish" title="取消发布"></span>
                    <?php echo __('取消发布', array(), 'sf_admin') ?>
                </a>
            </td>
            <td class="button" id="toolbar-publish">
                <a href="#" onclick="javascript:submitform('batchDelete')" class="toolbar">
                    <span class="icon-32-delete" title="Delete"></span>
                    <?php echo __('Delete', array(), 'sf_admin') ?>
                </a>
            </td>

            <td class="button" id="toolbar-new">
                <?php echo link_to("<span class=\"icon-32-new\" title=\"New\"></span>
" . __("New", array(), "sf_admin"), $helper->getUrlForAction("new")) ?>
            </td>
        </tr>
    </table>
</div>
