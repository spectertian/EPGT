$(document).ready(function(){
    auto_complete_program_filters_name();
});
var str_save    = '';

function init()
{
    $(document).ready(function(){
        var name    = $(".sf_admin_list_td_name");
        var time    = $(".sf_admin_list_td_time");
        var date    = $(".sf_admin_list_td_date");
        var publish = $(".sf_admin_list_td_publish");
        var wiki    = $(".sf_admin_list_td_wiki");
        var tags    = $(".sf_admin_list_td_tags");
        var is_new  = $(".sf_admin_list_td_is_new");
        var is_top  = $(".sf_admin_list_td_is_top");
        var is_hot  = $(".sf_admin_list_td_is_hot");

        publish.click(function(){
            if($("#publish").html() == null ) {
                var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
                $(this).attr('id','publish');
                ajax_publish(id);
            }
        });
        
        wiki.click(function(event){
            if(event.target.nodeName.toUpperCase() != 'TD') return;
            if ($("#wiki").html() == null) {
                var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
                $(this).attr('id', 'wiki');
                str_save = $.trim($(this).html());
                $(this).parent().find('.sf_admin_list_td_tags').attr('id', 'tagHtml');
                ajax_wiki(id);
            }
        });
        is_new.click(function(){
            if ($("#ext").length == 0) {
                var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
                $(this).attr('id', 'ext');
                ajax_ext(id,'new');
            }
        });
        is_top.click(function(){
            if ($("#ext").length == 0) {
                var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
                $(this).attr('id', 'ext');
                ajax_ext(id,'top');
            }
        });
        is_hot.click(function(){
            if ($("#ext").length == 0) {
                var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
                $(this).attr('id', 'ext');
                ajax_ext(id,'hot');
            }
        });

        //名称点击
        name.click(function(event)
        {
            
            if(event.target.nodeName.toUpperCase() == 'INPUT') return;
            if($("#name").val() != null )
            {
                return ;
            }
            var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
            if($(this).find('input').html() == null )
            {
                str_save    = $(this).html();
                $(this).attr('id', 'test_200');
                $(this).html('<input id="name"  value="'+$.trim($(this).html())+'" size="40">' + '<input onclick="ajax_name('+id+',\'name\');" type="button" value="修改" id="edit"><input type="button" value="取消" onclick="program_cance()">');
                $("#name").focus();
                auto_complete_name();
            }

            

        });
        
        tags.click(function(event){
            var target  = event.target.nodeName.toUpperCase();
            if(target == 'A' || target == "LI" || target=="INPUT") return;
            if($("#tags").val() != null ) {
                return ;
            }
            var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
            if($(this).find('input').html() == null ) {
                str_save    = $(this).html();
                $(this).html('<input id="tags"><input onclick="tags_add('+ id +');" value="确认" type="button"> <input value="取消" type="button" onclick="tags_cance();">');
                $("#tags").focus();
                auto_complete_tags();
            }
        });
        
        //时间点击
        time.click(function(event){
            if(event.target.nodeName.toUpperCase() == 'INPUT') return;
            if($("#time").val() != null ){
                return ;
            }
            var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
            if($(this).find('input').html() == null ){
                str_save    = $.trim($(this).html());
                $(this).html('<input id="time" value="'+$.trim($(this).html())+'"><input type="button" value="确认" onclick="ajax_time('+id+',\'time\');"><input type="button" value="取消" onclick="write_cance('+id+',\'time\'); ">');
                $.DateTimeMask.Selection($("#time").DateTimeMask({masktype:"4",isnow:false})[0], 0, 1);
            }
        });
        
        //日期点击
        date.click(function(event){
            if(event.target.nodeName.toUpperCase() == 'INPUT') return;
            if($("#time").val() != null )
            {
                return ;
            }
            var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
            if($(this).find('input').html() == null )
            {
                str_save    = $.trim($(this).html());
                $(this).html('<input id="date" value="'+$.trim($(this).html())+'" ><input value="确认" type="button" onclick="ajax_date('+id+',\'date\');"><input type="button" value="取消" onclick="write_cance('+id+',\'date\');">');
                $.DateTimeMask.Selection($("#date").DateTimeMask({masktype:"3",isnow:false})[0], 0, 1);
            }
        });
    });
}

function insert_html()
{
    var str    = $('#admin_list').find('tbody');
    str.html(str.html()+html_str());
    $("#time").DateTimeMask({masktype:"4",isnow:true});
    $("#name").focus();
}


function html_str()
{
    var str = '';
        str += '<tr class="rows">';
        str += '<td id ="id">1</td>';
        str += '<td>';
        str += '<input type="checkbox" class="sf_admin_batch_checkbox" value="11" name="ids[]">';
        str += '</td>';
        str += '<td class="sf_admin_text sf_admin_list_td_id"><a href="">11</a></td>';
        str += '<td class="sf_admin_text sf_admin_list_td_name"><input id="name" value=""/></td>';
        str += '<td class="sf_admin_text sf_admin_list_td_channel"> -----</td>';
        str += '<td class="sf_admin_boolean sf_admin_list_td_publish"><img src="'+un_publish+'" title="UnChecked" alt="Unhecked"></td>';
        str += '<td class="sf_admin_date sf_admin_list_td_time"><input id="time" value="" onblur="ajax_insert_html();"/></td>';
        str += '<td class="sf_admin_date sf_admin_list_td_date">0000-0000-00</td>';
        str += '<td class="sf_admin_text sf_admin_list_td_is_new"><img alt="新节目" src="'+un_publish+'"></td>';
        str += '<td class="sf_admin_text sf_admin_list_td_is_top"><img alt="推荐" src="'+un_publish+'"></td>';
        str += '<td class="sf_admin_text sf_admin_list_td_is_hot"><img alt="热播" src="'+un_publish+'"></td>';
        str += '<td class="sf_admin_text sf_admin_list_td_wiki"></td>';
        str += '<td class="sf_admin_text sf_admin_list_td_tags"><a href="#" onclick="$(this).parent().parent().remove();">删除</a></td>';
        str += '</tr>';
        return str;
}

function ajax_insert_html()
{
    var name    = $("#name").val();
    var time    = $("#time").val();
    $.ajax({
        url:            "program/ajax_program_insert?name=" + name + "&time=" + time,
        dataType:       "json",
        success:function(data)
        {
            if(data.code == 1)
            {
                ajax_insert_html_after(data);
            }
            else
            {
                alert(data.msg);
            }
            
        },error:function()
        {
            
        }
    });
}

function ajax_insert_html_after(data)
{
    var rows    = $(".rows");
    var name    = $("#name");
    var time    = $("#time");
    var id    = $("#id");
    //id
    id.html(data.id);
    //多选框
    rows.find('.sf_admin_batch_checkbox').val(data.id);
    rows.find('.sf_admin_list_td_id').html('<a href="program/' + data.id + '/edit">'+data.id+'</a>');
    rows.find('.sf_admin_list_td_channel').html(data.channel);
    rows.find('.sf_admin_list_td_date').html(data.date);
    rows.find('.sf_admin_list_td_tags').html('');
    name.parent().html(name.val());
    time.parent().html(time.val());
    init();
}
function ajax_name(id,name)
{
    var key = $("#"+name);
    ajax_update(id,name);
    var value   = key.val();
    $("#test_200").html(value);
    $("#test_200").attr('id', '');
}

function ajax_time(id,name)
{
    var key = $("#"+name);
    ajax_value(id,name, key.val());
    key.parent().html(key.val());
}

function ajax_date(id,name)
{
    var key = $("#"+name);
    ajax_value(id,name,key.val());
    key.parent().html(key.val());
}

function write_cance(id,name) {
    var key = $("#"+name);
    key.parent().html(str_save);
    return false;
}
function wiki_cance(id,name) {
    var key = $("#"+name);
    key.parent().html(str_save);
    $("#wiki").attr('id','');
    init();
    return false;
}

//更新字段
function ajax_update(id,name)
{
    var flag    = false;
    var key     = $("#"+name);
    $.ajax({
        url:            "program/ajax_program_update?name=" + name + "&id=" + id + "&value=" + key.val(),
        dataType:       "json",
        success:function(data)
        {
            if(data.code == 1)
            {
                noticeShow(data.msg);
            }
            else
            {
                noticeShow(data.msg);
            }

        },error:function()
        {
            noticeShow('操作失败');
        }
    });
}

function ajax_value(id,name,value)
{
    var flag    = false;
    var key     = $("#"+name);
    $.ajax({
        url:            "program/ajax_program_update?name=" + name + "&id=" + id + "&value=" + value,
        dataType:       "json",
        success:function(data)
        {
            if(data.code == 1)
            {
                noticeShow(data.msg);
            }
            else
            {
                noticeShow(data.msg);
            }

        },error:function()
        {
            noticeShow('操作失败');
        }
    });
}

function ajax_publish(id)
{
    $.ajax({
        url:            "program/ajax_program_publish?id=" + id,
        dataType:       "json",
        success:function(data)
        {
            if(data.code == 1)
            {
                if(data.msg == 0)
                {
                    $("#publish").html('<img src="'+un_publish+'" title="UnChecked" alt="Unhecked">');
                }
                else
                {
                    $("#publish").html('<img src="'+publish+'" title="Checked" alt="Checked">');
                }
                $("#publish").attr('id', '');
                noticeShow(data.content);
            }
            else
            {
                alert(data.msg);
            }

        },error:function()
        {
        }
    });
}


function ajax_wiki(id)
{
    var wiki = $("#wiki");
    wiki.html('<input id="wiki_complete" value="'+str_save+'"/><input type="button" value="确认" onclick="ajax_wiki_auto('+id+');"><input type="button" value="删除" onclick="ajax_wiki_cance('+id+');"><input type="button" value="取消" onclick="wiki_cance(1,\'wiki_complete\'); ">');

    //自动完成
    $('#wiki_complete').simpleAutoComplete('wiki/auto_complete',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: id,
        max       : 20
    });
}

function ajax_wiki_cance(id)
{
    var wiki   = $("#wiki_complete");
        $.ajax({
            url :  "program/wiki_del?id="+id,
            cache: "false",
            dataType: "text",
            success:function(data)
            {
                noticeShow(data);
                init();
                $("#wiki").html('');
                $("#wiki").attr('id','');
            }
        });

}
function ajax_wiki_auto(id)
{
    var wiki   = $("#wiki_complete");
    var value  = wiki.val().split('|');
    if (!isNaN(value[0])) {
        $.ajax({
            url :  "program/auto_complete_set_tag?wiki_id="+ value[0] + "&id="+id,
            cache: "false",
            dataType: "json",
            success:function(data)
            {
                if (data.code == 1) {
                    wiki.parent().html(value[1]);
                }else{
                    noticeShow(data.msg);
                }
                $('#wiki').attr('id', '');
                if (data.tags.length > 0) {
                    tags_array(data.tags);
                }
                init();
            }
        });
    }
    $("#wiki").attr('id','');
}

function tags_array(data) {
    var str = '';
    $.each(data, function(key,value){
        str +=  get_tag_html(value);
    });
    var tagHtml = $("#tagHtml");
    tagHtml.html(str);
    tagHtml.attr('id', '');
}

function get_tag_html(json) {
    return '<span rel="'+json.id+'" id="tags'+json.id+'">'+json.tagName+'<a href="javascript:tag_del('+json.id+');" title="删除标签--'+json.tagName+'" class="removeTags">x</a>,</span>';
}

function ajax_ext(id, style)
{
    var publish_ext = '<img src="'+publish +'"/>';
    var un_publish_ext = '<img src="'+un_publish +'"/>';
    var ext = $("#ext");
   $.ajax({
        url: "program/ajax_ext?id=" + id + "&style="+style,
        dataType: "json",
        success:function(data)
        {
            if (data.code ==1 ) {
                ext.html(publish_ext);
            }else if(data.code ==0) {
                ext.html(un_publish_ext);
            }
            noticeShow(data.msg);
        }
    });
    ext.attr('id','');
}

function tag_del(id)
{
    $("#tags").remove()
    $.ajax({
        url: "program/tag_del?id=" + id,
        dataType: "text",
        success:function(data)
        {
            noticeShow(data);
            $("#tags" + id).remove();
        }
    });
}
function tags_add(id)
{
    var value   = $("#tags").val();
    $.ajax({
        url: "program/tag_add",
        type: "post",
        data: {"id": id, "value" : value},
        dataType: "json",
        success:function(data)
        {
            noticeShow(data.msg);
            if(data.code == 1) {
                var str    = tags_html(data.id, value);
                var len    = data.id.msg.length;
                var i = 0;
                var html = '';
                for (i=0;i<len;i++){
                    html += tags_html(data.id.msg[i],data.post[i]);
                }
                $("#tags").parent().html(html);
                $("#tags").remove();
            }else{
                $("#tags").remove();
            }
        }
    });
}

function tags_html(id,name)
{
    var html    = '<span rel="'+id+'" id="tags'+id+'">'+ name +'<a href="javascript:tag_del(' + id + ');" title="删除标签--'+ name + '" class="removeTags">x</a>,</span>';
    return html;
}

function auto_complete_name()
{
    $('#name').simpleAutoComplete('program/auto_complete_name',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 8
    });
}
function auto_complete_tags()
{
    $('#tags').simpleAutoComplete('tags/auto_complete_tags',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 8
    });
}
//自动完成过滤器频道名称
function auto_complete_program_filters_name()
{
    $('#program_filters_name').simpleAutoComplete('program/auto_complete_name',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 8
    });
}

function program_cance(){
    var value   = $("#name");
    $("#test_200").html(str_save);
    $("#test_200").removeAttr('id');
    init();
}

function tags_cance(){
    $("#tags").parent().html(str_save);
}

init();