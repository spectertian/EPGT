function action()
{
    $(function(){
        var td_name    = $(".sf_admin_list_td_name");
        var td_time    = $(".sf_admin_list_td_time");
        var td_wiki    = $(".sf_admin_list_td_wiki");
        //修改标题
        td_name.click(function(evt){
                var value   = $(this).text();
                if($(this).find('input').html() == null)
                {
                   var id      = $(this).parent().find('.sf_admin_list_td_id a').text();
                   var input   = "<input id=\"postName\" value=\""+ $.trim(value)+"\" onblur=\"doAction('name',"+id+");\"/>";
                   $(this).html(input);
                   $("#postName").focus();
                }
        });
        td_wiki.click(function(){
            alert('here');
        });
        
        //时间点击
        td_time.click(function()
        {
            if($("#time").val() != null )
            {
                return ;
            }
            var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());

            if($(this).find('input').html() == null )
            {
                $(this).html('<input id="postName" value="'+$.trim($(this).html())+'" onblur="doAction(\'time\',' + id + ');">');
                $.DateTimeMask.Selection($("#postName").DateTimeMask({masktype:"4",isnow:true})[0], 0, 1);
                
            }
        });
    });
}


function insertHtml()
{
    var name    = $("#name").val();
    var time    = $("#time").val();
    $.ajax({
        url:        "?name="+name+"&time="+time,
        dataType:   "json",
        cache:      "false",
        success:function(data){
            if(data.code==1)
            {
                noticeShow(data.msg);
                insertHtmlAfter(data);
                init();
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
    
    title.attr('href', 'program_template/'+data.id+'/edit');
    id.attr('href', 'program_template/'+data.id+'/edit');
    id.html(data.id);
    id_value.val(data.id);
    name.parent().html(name.val());
    time.parent().html(time.val());
    postAction.parent().html(data.create);
    updated_at.html(data.create);

    //删除id属性
    title.html(data.title);
    title.attr('id','');
    id.attr('id','');
    id_value.attr('id','');
    name.attr('id','');
    time.attr('id','');
    postAction.attr('id','');
    updated_at.attr('id','');
    $(".rows").attr('class', 'row'+data.id);
    action();

}


function init()
{
    var  html = $('.row0').html();
    if(html == null)
    {
        $('#admin_list').find('tbody').html(innerHtml());
        $("#time").DateTimeMask({masktype:"4",isnow:true});
        //$("#list").html(innerHtml());
        //alert('here');
    }
    else
    {
        var str = innerHtml();
            str = str.replace('row0','rows');
       var add  = $('.row0').parent().html();
       $('.row0').parent().append(str);
       $("#time").DateTimeMask({masktype:"4",isnow:true});
    }
    $("#name").focus();
    //自动完成
    aotu_complete_name();
}

function innerHtml()
{
   var str  = '';
       str +='<tr class="row0">';
       str +='     <td>1</td>';
       str +='                 <td>';
       str +='<input type="checkbox" class="sf_admin_batch_checkbox" value="1" name="ids[]" id="id_value">';
       str +='</td>';
       str +='<td class="sf_admin_text sf_admin_list_td_id">';
       str +='<a  id="id" href="/epg/web_admin/admin_dev.php/program_template/8/edit">1</a></td>';
       str +='<td class="sf_admin_text sf_admin_list_td_wiki"></td>';
       str +='<td class="sf_admin_text sf_admin_list_td_program_index" id="title">';
       str +='模板名称</td>';
       str +='<td class="sf_admin_text sf_admin_list_td_name"><input id="name"/></td>';
       str +='<td class="sf_admin_text sf_admin_list_td_time"><input id="time" onblur="insertHtml();"/></td>';
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
        url:        "program_template/ajax_update?name="+name+"&value="+$.trim(value)+"&id="+id,
        dataType:   "json",
        cache:      "false",
        success:function(data){
            //alert(data);
            noticeShow(data.msg);
        },error:function(){
            alert('error');
        }
    });
}

function doNew()
{
    insertHtml();
}
action();


//自动完成部分

function aotu_complete_name()
{
    $(document).ready(function(){
        $('#name').simpleAutoComplete('program_template/auto_complete_name',{
            autoCompleteClassName: 'autocomplete',
            autoFill: false,
            selectedClassName: 'sel',
            attrCallBack: 'rel',
            identifier: 'wiki_key',
            max       : 20
        });
    });
}