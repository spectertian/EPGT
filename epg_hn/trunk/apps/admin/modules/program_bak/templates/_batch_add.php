<?php
    $datas = $sf_user->getAttribute('datas');
?>
<td class="button">
    <a class="toolbar thickbox" href="<?php echo url_for('@program_index').'/show_index?id='.$datas['channel_id'].'&date='.$datas['date'] ?>&height=600&width=800" title="请选择节目单模板">
        <span title="使用模板" class="icon-32-publish"></span>
        使用模板</a>
</td>
<td class="button">
    <a class="toolbar" href="javascript:insert_html();" title="添加节目">
        <span title="添加新节目" class="icon-32-publish"></span>
        添加节目</a>
</td>