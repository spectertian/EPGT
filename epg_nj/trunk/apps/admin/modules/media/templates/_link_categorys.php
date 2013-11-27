<script type="text/javascript">
$(".jstree-clicked").live('click',function(){
    var id = $(this).parent('LI').attr("id").replace("node_","");
    $("#category_id").find("OPTION").each(function(x){
                if(id == $(this).val())
                {
                    $(this).attr('selected',true);
                }
    });
    
    $.ajax({
        url: '<?php echo url_for('media/category_files') ?>',
        dataType: 'json',
        type: 'GET',
        data: 'category_id='+id,
        success: function(files){
                if(id != 0)
                {
                   $('.icon-48-addedit').html( '文件管理 ---' + files[0].category_name );
                }else{
                   $('.icon-48-addedit').html( '文件管理');
                }
                html = '';
                if(!files[0].files)
                {
                    html='分类暂无文件';
                }else{
                    for( i = 0 ; i <= (files.length - 1) ; i++ )
                    {
                        html +=       '<div class="imgOutline" style="margin-left:10px;">';
                        html +=       '     <div class="imgTotal"> ' ;
                        html +=       '        <div align="center" class="imgBorder"> ' ;
                        html +=       ' <a style="display: block; width: 100%; height: 100%;" title="'+ files[i].source_name +'" href="#" class="img-preview"> ';
                        html +=       '     <div class="image"> ';
                        html +=       '             <img border="0" alt="cancel.png - 564 bytes" src="'+ files[i].file_thumbNail +'" /> ';
                        html +=       '     </div>';
                        html +=       ' </a> ' ;
                        html +=       '<div id="show_file_info" style="display:none;" rel="0">';
                        html +=       '<span>'+ files[i].file_url +'</span>';
                        html +=       '<span>'+ files[i].file_name +'</span>';
                        html +=       '</div>';
                        html +=       ' </div> ';
                        html +=       ' </div> ' ;
                        html +=       ' <div class="controls"> ';
                        html +=       '</div>';
                        html +=       ' <div class="imginfoBorder"> ';
                        html +=       ' <a href="#" onclick="return false;" title="'+ files[i].source_name +'" > ';
                        html +=       files[i].short_name ;
                        html +=       ' </a> ' ;
                        html +=       ' </div> ';
                        html +=       ' </div>';
                    }
                }
                $('.manager').html(html);
        }
    });
});
$(document).ready(function(){
    
    $('#file_tree').jstree({
        //JSON
        "json_data" : {
			"ajax" : {
                            url : "<?php echo url_for('attachment_categorys/Index') ?>",
                            data: function(n){
                                return { 'category_id' : n.attr ? n.attr("id").replace("node_","") : 0 };
                            }
                        }
		},
        "core" : { "initially_open" : [ "node_0" ] },
        //右键菜单配置
       'contextmenu' : {
           'items' : {
                "rename" : { 
                                "label" : "修改分类" ,
                                'action' : function(obj){
                                    
                                            if(obj.attr('id').replace("node_","") == 0 )
                                                {
                                                    alert('不能修改顶级！');
                                                }else{
                                                    this.rename(obj);
                                                }
                                                
                                                obj.keypress(function(event){
                                                   switch(event.which)
                                                   {
                                                        case 13 :
                                                            $('#file_tree').bind('rename.jstree',function(e,data){
                                                                jstreeRename(e, data);
                                                            });
                                                            return false;
                                                            break;
                                                   }
                                                })
                                            }
                           },
                'create' : {
                                'label': '增加分类',
                                'action': function(obj){
                                                 this.create(obj);

                                                 obj.keypress(function(event){
                                                   switch(event.which)
                                                   {
                                                        case 13 :
                                                            return false;
                                                            break;
                                                   }
                                                });
                                          }
                            },
//************** START Modify by tianzhongsheng-ex@huan.tv 关闭删除分类功能能 Time 2013-04-27 11:32:00 ***********************/

//                'remove' : { 
//                                'label'    : '删除分类',
//                                'action'   : function(obj){
//                                                    var current = this;
//                                                    var current_obj = obj;
//
//                                                    if(obj.attr('id').replace("node_","") == 0 )
//                                                    {
//                                                        alert('不能删除顶级分类！');
//                                                        return false;
//                                                    }
//
//                                                    $.ajax({
//                                                        url: '<?php echo url_for('attachment_categorys/pre_remove') ?>',
//                                                        dataType: 'json',
//                                                        data:  'id=' + obj.attr('id').replace("node_","") ,
//                                                        type: 'POST',
//                                                        success: function(msg){
//                                                            if(msg.status == 0)
//                                                            {
//                                                                current.remove(current_obj);
//                                                            }
//
//                                                            if(msg.status == 1)
//                                                            {
//                                                                alert('分类删除失败！请先删除子分类！');
//                                                                return false;
//                                                            }
//
//                                                            if(msg.status == 2)
//                                                            {
//                                                                alert('分类删除失败！请先删除或移动此分类下的文件！');
//                                                                return false;
//                                                            }
//                                                        },
//                                                        error: function()
//                                                        {
//                                                            alert('服务器出错！请联系管理员！');
//                                                        }
//                                                    });
//                                              }
//                           }
//************** END Modify by tianzhongsheng-ex@huan.tv 关闭删除分类功能能 Time 2013-04-27 11:32:00 *******************/
            }
       },

       "plugins" : [ "themes", "json_data", "ui", "crrm", "contextmenu" ]
         
    })
    .bind('rename.jstree',function(e,data){
        jstreeRename(e,data);
    })
    .bind("create.jstree", function (e, data) {
       jstreeCreate(e,data);
    })
    .bind('remove.jstree',function(e,data){
        $.ajax({
            url: '<?php echo url_for('attachment_categorys/remove_category') ?>',
            dataType: 'json',
            data:  'id=' +  data.rslt.obj.attr('id').replace("node_","") ,
            type: 'POST',
            success:function(result){
                if(result.status == 0)
                {
                    alert('分类删除失败！请先删除子分类！');
                    window.location.reload(true);
                }else{
                    var id = result.status;
                    $('#category_id').find('OPTION').each(function(x){
                        if(id == $(this).val())
                        {
                            $(this).remove();
                        }
                    });
                }
            }
        });
    })

    function jstreeRename(e,data)
    {
        $.post(
            '<?php echo url_for('attachment_categorys/change') ?>',
            {
                'id' : data.rslt.obj.attr('id').replace("node_",""),
                'new_name' : data.rslt.new_name
            },
            function(r)
            {
                if(r.status == 0)
                {
                    alert('分类修改失败！');
                    return false;
                }else{
                    var id = r.status;
                    $('#category_id').find('OPTION').each(function(x){
                        if(id == $(this).val())
                        {
                            $(this).text(data.rslt.new_name);
                        }
                    });
                }
            }
        );
    }

    function jstreeCreate(e,data)
    {
         $.post(
            '<?php echo url_for("attachment_categorys/add_category"); ?>',
            {
                'parent_id' : data.rslt.parent.attr ? data.rslt.parent.attr('id').replace("node_","") : 0 ,
                'name' : data.rslt.name
            },
            function(r){
                //No Result
                if(r.id > 0)
                {
                    data.rslt.obj.attr('id','node_'+r.id);
                    html = '<option value="'+ r.id +'">'+ r.name +'</option>';
                    $('#category_id').append(html);
                }
            }
        );
    }


});

</script>
<fieldset id="treeview">
    <legend>文件分类</legend>
    <div id="file_tree" class="jstree jstree-0 jstree-default jstree-focused">
        <ul>
            <li class="jstree-closed">
                 <ins class="jstree-icon">&nbsp;</ins>
                 <a href="#"></a>
            </li>
        </ul>
    </div>
</fieldset>

