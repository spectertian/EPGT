<script type="text/javascript">
$(".jstree-clicked").live('click',function(){
    var id = $(this).parent('li').attr("id").replace("node_","");
    $("#category_id").val(id);
    /*$("#category_id").find("OPTION").each(function(x){
        if(id == $(this).val())
        {
            $(this).attr('selected',true);
        }
    });*/
    $("#media_list").load("<?php echo url_for('media/category_files') ?>", {"category_id": id, "popup": "<?php echo $popup;?>"}, function() {
        //tb_init('a.thickbox, area.thickbox, input.thickbox');
    });
});

$(document).ready(function(){
    
    $('#file_tree').jstree({
        //JSON
        "json_data" : {
			"ajax" : {
                url : "<?php echo url_for('attachment_categorys/index') ?>",
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
                'remove' : { 
                    'label'    : '删除分类',
                    'action'   : function(obj){
                                        var current = this;
                                        var current_obj = obj;

                                        if(obj.attr('id').replace("node_","") == 0 )
                                        {
                                            alert('不能删除顶级分类！');
                                            return false;
                                        }

                                        $.ajax({
                                            url: '<?php echo url_for('attachment_categorys/pre_remove') ?>',
                                            dataType: 'json',
                                            data:  'id=' + obj.attr('id').replace("node_","") ,
                                            type: 'POST',
                                            success: function(msg){
                                                if(msg.status == 0)
                                                {
                                                    current.remove(current_obj);
                                                }

                                                if(msg.status == 1)
                                                {
                                                    alert('分类删除失败！请先删除子分类！');
                                                    return false;
                                                }

                                                if(msg.status == 2)
                                                {
                                                    alert('分类删除失败！请先删除或移动此分类下的文件！');
                                                    return false;
                                                }
                                            },
                                            error: function()
                                            {
                                                alert('服务器出错！请联系管理员！');
                                            }
                                        });
                                  }
                           }
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

    <div class="menu menu_category"><strong>文件分类</strong></div>
    <div id="file_tree" class="jstree jstree-0 jstree-default jstree-focused">
        <ul id="browser" class="filetree">
            <li class="jstree-closed">
                 <ins class="jstree-icon">&nbsp;</ins>
                 <a href="#"></a>
            </li>
        </ul>
    </div>
