<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if($return['code'] == 1){?>
<table id="template_list">
    <tr>
        <td>频道名称</td>
        <td>模板名称</td>
    </tr>
<?php  foreach ($return['msg'] as $value){?>
    <tr id="tr_id_<?php echo $value['id'];?>">
        <td id="channel_<?php echo $value['id'];?>"><?php echo $value['channel_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td id="title_"><a title="<?php echo $value['title'];?> 节目单模板" class="thickbox" href="<?php echo url_for('@program_template').'/show_template?id='.$value['id'].'&date='.$date.'&channel_id='.$value['channel_id'];?>&height=600&width=800"><?php echo $value['title'];?></a></td>
    </tr>
<?php }?>
</table>
<?php }
 else
{
    echo $return['msg'];
}
?>
