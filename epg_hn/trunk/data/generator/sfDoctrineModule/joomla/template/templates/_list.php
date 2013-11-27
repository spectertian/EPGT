<table class="adminlist" cellspacing="1" id="admin_list">
    <thead>
        <tr>
            <th width="5">#</th>
            <?php if ($this->configuration->getValue('list.batch_actions')): ?>
            <th width="5" id="sf_admin_list_batch_actions"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll();" /></th>
            <?php endif; ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_<?php echo $this->configuration->getValue('list.layout') ?>', array('sort' => $sort)) ?]
            <?php if ($this->configuration->getValue('list.object_actions')): ?>
                 <th id="sf_admin_list_th_actions">[?php echo __('Actions', array(), 'sf_admin') ?]</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="<?php echo count($this->configuration->getValue('list.display')) + ($this->configuration->getValue('list.object_actions') ? 1 : 0) + ($this->configuration->getValue('list.batch_actions') ? 1 : 0) + 1 ?>">
                <del class="container"><div class="pagination">
                [?php if ($pager->haveToPaginate()): ?]
                  [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager)) ?]
                [?php endif; ?]
                
                [?php if ($pager->haveToPaginate()): ?]
                <div class="limit">[?php echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'sf_admin') ?]</div>
                [?php endif; ?]
                </div></del>
            </td>
</tr>
</tfoot>
<tbody>
    [?php foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? '0' : '1' ?]
    <tr class="row[?php echo $odd; ?]">
        <td>[?php echo $i ?]</td>
        <?php if ($this->configuration->getValue('list.batch_actions')): ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_batch_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
        <?php endif; ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_<?php echo $this->configuration->getValue('list.layout') ?>', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
        <?php if ($this->configuration->getValue('list.object_actions')): ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
        <?php endif; ?>
    </tr>
    [?php endforeach; ?]
</tbody>
</table>
<table cellspacing="0" cellpadding="4" border="0" align="center">
    <tr align="center">
        <td>
            <img src="[?php echo image_path('icon/publish_g.png'); ?]" width="16" height="16" border="0" alt="Visible" />
        </td>
        <td>
            已发布 |
        </td>
        <td>
            <img src="[?php echo image_path('icon/publish_x.png'); ?]" width="16" height="16" border="0" alt="Finished" />
        </td>
        <td>
            未发布
        </td>
    </tr>
</table>
<script type="text/javascript">
/* <![CDATA[ */
function checkAll()
{
  var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
}
/* ]]> */
</script>