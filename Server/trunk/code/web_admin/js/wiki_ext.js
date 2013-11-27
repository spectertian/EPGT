function action()
{
    $(function(){
        var td_name    = $(".sf_admin_list_td_name");
        var td_wiki       = $(".sf_admin_list_td_wiki_value");
        var td_key       = $(".sf_admin_list_td_wiki_key");
        //修改标题
        td_name.click(function(evt){
                var value   = $(this).text();
                if($(this).find('input').html() == null)
                {
                   var id      = $(this).parent().find('.sf_admin_list_td_id a').text();
                   var input   = "<input id=\"postName\" value=\""+ $.trim(value)+"\" onblur=\"doAction('wiki_nem',"+id+");\"/>";
                   $(this).html(input);
                }
        });
        //修改wiki_value
        td_wiki.click(function(evt){
                var value   = $(this).text();
                if($(this).find('input').html() == null)
                {
                   var id      = $(this).parent().find('.sf_admin_list_td_id a').text();
                   var input   = "<input id=\"postName\" value=\""+ $.trim(value)+"\" onblur=\"doAction('wiki_value',"+id+");\"/>";
                   $(this).html(input);
                }
        });
        //修改wiki_value
        td_key.click(function(evt){
                var value   = $(this).text();
                if($(this).find('input').html() == null)
                {
                   var id      = $(this).parent().find('.sf_admin_list_td_id a').text();
                   var input   = "<input id=\"postName\" value=\""+ $.trim(value)+"\" onblur=\"doAction('wiki_key',"+id+");\"/>";
                   $(this).html(input);
                }
        });
    });

}
function insertHtml()
{
    var name    = $("#name").val();
    var time    = $("#time").val();
    var wiki_key= $("#wiki_key").val();
    $.ajax({
        url:        "wiki_ext/insert_value?name="+name+"&wiki_value="+time+"&wiki_key="+wiki_key,
        dataType:   "json",
        cache:      "false",
        success:function(data){
            if(data.code==1)
            {
                insertHtmlAfter(data);
                action();
            }
            else
            {
                alert(data.msg);
            }

        },
        error:function(){

        }
    });
}

function insertHtmlAfter(data)
{
    var base       = $("#postAction").parent().parent();
    var title      = $("#title");
    var id         = $("#id");
    var id_value   = $("#id_value");
    var name       = $("#name");
    var time       = $("#time");
    var postAction = $("#postAction");
    var updated_at = $("#updated_at")
    var wiki_key   = $("#wiki_key");

    title.attr('href', 'wiki_ext/'+data.id+'/edit');
    id.attr('href', 'wiki_ext/'+data.id+'/edit');
    id.html(data.id);
    id_value.val(data.id);
    name.parent().html(name.val());
    time.parent().html(time.val());
    wiki_key.parent().html(wiki_key.val());
    postAction.parent().html(data.create);
    updated_at.html(data.create);

    //删除id属性
    title.html(data.title);
    title.attr('id','');
    wiki_key.attr('id','');
    id.attr('id','');
    id_value.attr('id','');
    name.attr('id','');
    time.attr('id','');

    postAction.attr('id','');
    updated_at.attr('id','');
    $(".rows").attr('class', 'row'+data.id);

}


function init()
{
    var  html = $('.row0').html();
    if(html == null)
    {
        $('#admin_list').find('tbody').html(innerHtml());
        //$("#list").html(innerHtml());
    }
    else
    {
        var str = innerHtml();
            str = str.replace('row0','rows');
       var add  = $('.row0').parent().html();
       $('.row0').parent().html(str + add);
    }
}

function innerHtml()
{
   var str  = '';
       str +='<tr class="row0">';
       str +='     <td>1</td>';
       str +='                 <td>';
       str +='<input type="checkbox" class="sf_admin_batch_checkbox" value="1" name="ids[]" id="id_value">';
       str +='</td>';
       str +='<td class="sf_admin_text sf_admin_list_td_id"><a  id="id" href="/epg/web_admin/admin_dev.php/program_template/8/edit">1</a></td>';
       str +='<td class="sf_admin_text sf_admin_text sf_admin_list_td_wiki"id="title">模板名称</td>';
       str +='<td class="sf_admin_text sf_admin_text sf_admin_list_td_title"><input id="name"/></td>';
       str +='<td class="sf_admin_text sf_admin_text sf_admin_list_td_title"><input id="wiki_key"/></td>';
       str +='<td class="sf_admin_text sf_admin_text sf_admin_list_td_wiki_value"><input id="time"/></td>';
       str +='<td class="sf_admin_date sf_admin_list_td_created_at" id="created_at"><input id="postAction" type="button" value="保存" onclick="insertHtml();"></td>';
       str +='<td class="sf_admin_date sf_admin_list_td_updated_at" id="updated_at"><input type="button" value="删除" onclick="$(this).parent().parent().remove();">';
       str +='</td>';
       str +='</tr>';
       return str;
}
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
        url:        "wiki_ext/ajax_update?name="+name+"&value="+$.trim(value)+"&id="+id,
        dataType:   "json",
        cache:      "false",
        scuccess:function(data){
            alert(data);
        },error:function(){

        }
    });
}

action();