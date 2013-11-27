<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_javascripts();
if($return['code'] == 1){?>
<form action="<?php echo url_for('@program_template').'/program_to_template'?>" method="post" id="content">
    <input type="hidden" name="channel_id" value="<?php echo $channel_id;?>">
    <input type="hidden" name="date" value="<?php echo $date;?>">
<?php
$k = 0;
foreach ($return['msg'] as $value){?>
    <span id="list<?php echo $k;?>">节目:&nbsp;&nbsp;&nbsp;&nbsp;<input class="name" name="name[]" value="<?php echo $value->getName();?>" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;时间:&nbsp;&nbsp;&nbsp;&nbsp;<input name="time[]" class="time" value="<?php echo $value->getTime();?>" size="10"/><input id="here" type="button" value="删除" onclick="$(this).parent().remove();"><input type="hidden" name="wiki_id[]" value="<?php echo $value->getWikiId();?>"><br/></span>
<?php 
$k++;
}?>
    <span id="add"><input type="button" value="添加一列" onclick="addHtml();"><input type="submit" value="提交"></span>
</form>
<?php }
 else
{
    echo $return['msg'];
}
?>
<script type="text/javascript">
    <!--
    var span_id = <?php echo $k;?>;
    function addHtml()
    {
        var str = '<span id="list'+span_id+'">节目:<input size="10" value="新节目"class="name" name="name[]">时间:<input size="10" value="00:00" name="time[]" class="time"><input type="button" onclick="$(this).parent().remove();" value="删除"><input type="hidden" value="1" name="wiki_id[]"><br></span>';
        var add_html    = $("#add").clone();
        $("#add").remove();
        $("#content").append(str);
        $("#content").append(add_html);
        span_id++;
    }

    function test()
    {
        alert($(""));
    }
    //-->
</script>
