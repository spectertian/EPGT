/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function tinymc_init()
{
    tinyMCE.init({
    theme : "advanced", //总共两种模板：advanced和simple
    //mode : "exact",
    mode : "textareas",
    editor_deselector : "mceNoEditor",
    plugins : "inlinepopups,searchreplace,paste,fullscreen",
    theme_advanced_toolbar_location : "top", //工具栏默认是在底部，调到顶部的话要加入这个句配置。
    theme_advanced_toolbar_align : "left", //工具栏按钮默认是全局居中，要让按钮左对齐要加入这句配置。
    theme_advanced_statusbar_location : "bottom", //默认是不显示状态栏的（功能和DW的状态栏一样），加入此句子可以调出状态栏并且显示在编辑器下方。
    theme_advanced_resizing : true, //可以动态调整编辑器大小（按钮在编辑器右下方）
    theme_advanced_buttons1 : 'replace,|,forecolor,backcolor,bold,italic,underline ,strikethrough,|,justifyleft,justifycenter,justifyright,|,bullist,numlist,copy,cut,paste,pastetext,removeformat,undo,redo,code,fullscreen',
    theme_advanced_buttons2 : '',
    theme_advanced_buttons3 : '',

    skin: "default" //这是office风格，挺清爽的。

    });
}

function global_complete(id, data, url){
   $('#'+id).simpleAutoComplete(wikiUrl  + '/'+url,{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: data,
        max       : 20
    });
}

function get_team_name_by_id()
{
    var id   = $("#value_nba_team").val();
    if (id !='') {
        $.ajax({
            url :  wikiUrl + "/ajax_get_title_by_id?id="+id,
            cache: "false",
            dataType: "text",
            success:function(data){
                $("#value_nba_team_show").val(data);
            }
        });
    }
}

$(document).ready(function() {
    $('#wiki_tags').simpleAutoComplete(wikiUrl + '/../tags/auto_complete_tags',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 10
    });

    $('#wiki_key_starring').simpleAutoComplete(wikiUrl + '/ajax',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 10
    });
    
    $('#wiki_key_release').simpleAutoComplete(wikiUrl + '/auto_complete_wiki_ext_wiki_value',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 10
    });
    
    $('#wiki_title').simpleAutoComplete(wikiUrl + '/auto_complete_wiki_title',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 10
    });
    
    $('#wiki_key_director').simpleAutoComplete(wikiUrl + '/auto_complete_wiki_ext_wiki_value',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_key',
        max       : 10
    });
});

function init()
{
    tinyMCE.init({
    theme : "advanced", //总共两种模板：advanced和simple
    mode : "exact",
    plugins : "inlinepopups,searchreplace,paste,fullscreen",
    theme_advanced_toolbar_location : "top", //工具栏默认是在底部，调到顶部的话要加入这个句配置。
    theme_advanced_toolbar_align : "left", //工具栏按钮默认是全局居中，要让按钮左对齐要加入这句配置。
    theme_advanced_statusbar_location : "bottom", //默认是不显示状态栏的（功能和DW的状态栏一样），加入此句子可以调出状态栏并且显示在编辑器下方。
    theme_advanced_resizing : true, //可以动态调整编辑器大小（按钮在编辑器右下方）
    theme_advanced_buttons1 : 'pastetext,|,search,replace,|,forecolor,backcolor,bold,italic,underline ,strikethrough,|,justifyleft,justifycenter,justifyright,|,bullist,numlist,copy,cut,paste,removeformat,undo,redo,code',
    theme_advanced_buttons2 : '',
    theme_advanced_buttons3 : '',

    //mode : "textareas",
    skin: "default" //这是office风格，挺清爽的。
    });
}

function button_clear(id)
{
    $("#" + id).val('');
}

function language(keys,html,id)
{
    show_text(keys,id);
}

function show_text(keys,id)
{
    var value   = $("#" + keys);
    var str    = $("#"+id).val();
    var checked = $("#" + id).attr('checked');
    //已经选中
    if (checked) {
        if(value.val() == '')
        {
            value.val(str);
        }
        else
        {
            var value_before    = value.val();
            value.val(value_before + ',' + str);
        }
    }else{
        var data    = value.val();
            data    = data.replace(',' + str, '');
            data    = data.replace(str, '');
            value.val(data);
    }
}

function screenshots_html()
{
    var $ips = $('#index input[type=text]');
    var $e = null;
    var b = 0;
    $ips.each(function(i, e){
        var a = $(e).val();
        if(/^\d+$/g.test(a)){
            if(Number(a) > b) {
                b = parseInt(a);
                $e = $(e);
            }
        }
    });
    var action    = parseInt(b) +1 ;
    if ($("#screenshots_index" + action).length > 0) {
        action  = action + 1;
    }
    var html    = '<tr id="screenshots_index'+action+'">';
        html   +='                 <td width="40%" class="paramlist_key">剧照</td>';
        html   +='                    <td class="paramlist_value" width="70%">';
        html   +='                            排序：<input type="text" format="*N" value="'+action+'" name="screenshots[Sort][]" id="key_sort'+action+'">';
        html   +='                             <a href="'+wikiUrl +'/../media/link?function_name=screenshots&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save('+action+');">上传文件</a><input type="button" value="删除" onclick="screenshots_del('+action+');">';
        html   +='                             <input type="hidden" id="key_screenshots'+action+'" name="screenshots[WikiKey][]" value="screenshots">';
        html   +='                             <input type="hidden" id="old_screenshots_hide'+action+'" name="screenshots[Old][]" value=""><br/>';
        html   +='                             <input name="screenshots[WikiValue][]" id="screenshots'+action+'" value="654" type="hidden"/>';
        html   +='                            <img alt="加载中" id="screenshots_pic'+action+'" src="321" >';
        html   +='                     </td>';
        html   +='                 </tr>';
        return html;
}

function screenshots_add(){
    var html    = screenshots_html();
    var content = $("#index").html();
    $("#index").html(html+content);
    $(document).ready(function(){
        tb_init('a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
        imgLoader = new Image();// preload image
        imgLoader.src = tb_pathToImage;
    });

}

function screenshots_adds(key)
{
    $.each(key, function(k,v){
        var html    = screenshots_html();
        html        = html.replace(/321/g,v.link);
        html        = html.replace(/654/g,v.key);
        var content = $("#index").html();
        $("#index").html(html+content);
    });
    tb_remove();
}

function screenshots_del(id) {
    $("#screenshots_index"+id).remove();
}

function ajax_screenshots_del(id,sort) {
    $.ajax({
        url:   wikiUrl + "/../wiki/ajax_sort_delete?id=" +id +"&sort=" +sort,
        dataType:   "json",
        success:function(data){
            screenshots_del(sort);
        },error:function(){
            alert('删除失败');
        }
    });
}

function drama_add()
{
    var len     = $(".wiki_key_drama").length + 1;
    len         = $("#drara_name").val();
    var html    = str_html(len);
    var content = $("#drama");
    content.append(html);
    str_html_aotu_complete(len);
    tinymc_init();
    $("#key_drama_value"+len).addClass('mceNoEditor');
    $("#key_drama_value"+len).addClass('init');
    //$("#key_drama_value"+len).removeClass("mceNoEditor");
    //隐藏
    $("#wiki_key_drama"+ show).hide();
    $("#drara_name").focus();
}

function drama_del(id)
{
    var len   = $(".wiki_key_drama").length;
    if(id == len)
    {
        $("#wiki_key_drama" + id).remove();
    }else{
        alert('请先删除第'+ len +'集');
    }
}

function drama_ajax_del(id, wiki_id)
{
    $.ajax({
        url:   wikiUrl + "/../wiki_ext/ajax_drama_delete?id=" +id,
        dataType:   "json",
        success:function(data)
        {
            if(data.code == 0){
                alert(data.msg)
            }else{
                $("#wiki_key_drama" + id).remove();
            }
        },error:function()
        {
            alert('删除失败');
        }
    });
}

//隐藏剧情
function drama_ajax_hide(id) {
    $("#wiki_key_drama"+id).hide();
}

//电视剧
function str_html(id)
{
    var html    = '';
        html   += '<tr class="wiki_key_drama" id="wiki_key_drama' + id + '">';
        html   += '            <td class="key"><a href="javascript:drama_del('+ id +');"> 删除本集</a><br/>';
        html   += '                <label for="wiki_drama' + id + '">第' + id + '集剧情</label></td>';
        html   += '            <td>';
        html   += '                集数：<input type="text" id="key_parent' + id + '" name="drama[sort][]" value="' + id + '" format="*N">';
        html   += '                该集标题：<input type="text" id="key_parent_title' + id + '" name="drama[title][]">';
        html   += '                <textarea  style="width: 100%;" name="drama[value][]" id="key_drama_value' + id + '"></textarea>';
        html   += '            </td>';
        html   += '</tr>';
        id      = id - 1;

   return html;
}

//电视剧分集标题自动完成监听
function str_html_aotu_complete(id)
{
    $(document).ready(function(){
        $('#key_parent_title' + id).simpleAutoComplete(wikiUrl + '/auto_complete_wiki_ext_wiki_value',{
            autoCompleteClassName: 'autocomplete',
            autoFill: false,
            selectedClassName: 'sel',
            attrCallBack: 'rel',
            identifier: 'wiki_key',
            max       : 20
        });
    });
}