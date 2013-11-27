/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    var td_title    = $(".sf_admin_list_td_title");
    var td_id       = $(".sf_admin_list_td_id");
    var del         = $(".del_template");
    //修改标题
    td_title.click(function(evt){
            var value   = $(this).text();
            if($(this).find('input').html() == null)
            {
               var id      = $(this).parent().find('.sf_admin_list_td_id a').text();
               var input   = "<input id=\"postName\" value=\""+ $.trim(value)+"\" onblur=\"doAction('title',"+id+");\"/>";
               $(this).html(input);
            }
    });
    //删除模板
    del.click(function(evt){
        var  parent = $(this).parent().parent();
        var id      = parent.find('.sf_admin_list_td_id a').text();
        parent.remove();
        ajax_del(id);
    });

});

//更新标题
function doAction(name, id)
{
    var value   = $("#postName").val();
    $("#postName").parent().html(value);
    ajax_update(name,value,id);
}

//异步请求
function ajax_update(name, value, id)
{

    $.ajax({
        url:        "program_index/ajax_update?name="+name+"&value="+$.trim(value)+"&id="+id,
        dataType:   "json",
        cache:      "false",
        success:function(data){
            noticeShow(data.msg);
        },error:function(){
            
        }
    });
}

//删除模板
function ajax_del(id)
{
   $.ajax({
        url:        "program_index/ajax_del?id="+id,
        dataType:   "json",
        cache:      "false",
        success:function(data){
        },error:function(){

        }
    });
}

