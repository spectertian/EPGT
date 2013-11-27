<dl id="system-message" style="display: <?php echo $class;?>">
    <dt class="notice">Message</dt>
    <dd class="notice fade">
        <ul>
            <li><?php echo $sf_user->getFlash('notice');?></li>
        </ul>
    </dd>
</dl>
<form action="<?php echo url_for('@program_template').'/tem_add/id/'.$id;?>" method="post" id="content">
    <input type="hidden" value="<?php echo $id; ?>">
    <div class="m">
        <table cellspacing="1" class="adminlist">
            <thead>
                <tr>
                    <th class="title sf_admin_text ">节目名称</th>
                    <th class="title sf_admin_text ">播放时间</th>
                    <th class="title sf_admin_text ">其他操作</th>
                </tr>
            </thead>
            <tbody id="add">
                <tr>
                    <td class="sf_admin_text">
                        <input value="" name="name[]" /></td>
                    <td class="sf_admin_text">
                        <input value="" name="time[]" /></td>
                    <td class="sf_admin_text">
                        <a onclick="javascript:$(this).parent().parent().remove();" ><img src="/epg/web_admin/images/icon/publish_x.png"> </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: center">
            <input name="" type="button" value="添加一列" onclick="addHtml()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="postAction" type="submit" value="确认提交"/>
        </div>
    </div>
</form>
<script type="text/javascript">
    <!--
    function addHtml()
    {
        var str = '<tr>';
        str+= '<td class="sf_admin_text">';
        str+= '        <input value="" name="name[]"></td>';
        str+= '<td class="sf_admin_text">';
        str+= '        <input value="" name="time[]"</td>';
        str+= '    <td class="sf_admin_text">';
        str+= '        <a onclick="javascript:$(this).parent().parent().remove();" ><img src="/epg/web_admin/images/icon/publish_x.png" /> </a>';
        str+= '    </td>';
        str+= '</tr>'
        $("#add").append(str);
    }
    $(document).ready(function() {
       // bind 'myForm' and provide a simple callback function
       $('#postAction').ajaxForm(function() {
           alert("Thank you for your comment!");
        });
    });
    //-->
</script>
