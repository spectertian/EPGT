<script type="text/javascript"><!--
var sort = 1000;
var click = '';
function stills(key, url)
{
    $('#wiki_stills_value').val(key);
    $("#show_pic").attr('src',url);
    $("#show_pic").show();
    tb_remove();
}
function url_save(id){
    click = id;
}

//上传封面
function screenshots(key ,url) {
    if (undefined == url) {
        $.each(key, function(k,v){
           key  = v.key;
           url  = v.link;
           return ;
        });
    }
    $("#screenshots_"+click).val(key);
    $("#screenshots_pic_"+click).show();
    $("#screenshots_pic_"+click).attr('src', url);
    thickboxInit();
//    tb_remove();
}
function rnd_str(str_0,str_1,str_2,str_3) {
    var seed_array  =   new Array();
    var seedary;
    var i;

    seed_array[0]=""
    seed_array[1]= "a b c d e f g h i j k l m n o p q r s t u v w x y z";
    seed_array[2]= "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z";
    seed_array[3]= "0 1 2 3 4 5 6 7 8 9";


    if (!str_1&&!str_2&&!str_3){str_1=true;str_2=true;str_3=true;}

    if (str_1){seed_array[0]+=seed_array[1];}
    if (str_2){seed_array[0]+=" "+seed_array[2];}
    if (str_3){seed_array[0]+=" "+seed_array[3];}

    seed_array[0]= seed_array[0].split(" ");
    seedary=""
    for (i=0;i<str_0;i++)
    {
    seedary+=seed_array[0][Math.round(Math.random( )*(seed_array[0].length-1))]
    }
    return(seedary);

}

/**
 * 隐藏弹出框
 */
function thickboxInit() {
        $("#fancybox-overlay") .hide();
        $("#fancybox-wrap").hide();
}

//电视剧分集
function screenshotAdd() {
    var html    = getScreenshotHtml('none','','');
    var right   = $("#right");
    var content = right.html();
    $("#right").append(html);
}

//栏目分期
function mateScreenshotAdd() {
    var html    = getScreenshotHtml('none','','');
    var right   = $("#widgets ul");
    var content = right.html();
    right.append(html);
}
//主题剧照上传
function themescreenshotAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"theme[img]\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#right").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"theme[img]\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right").html(html);
    thickboxInit();
}

//广告剧照
function adscreenshotAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"ad[img]\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#right").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"ad[img]\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right").html(html);
    thickboxInit();
}

//短视频封面
function smscreenshotAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"sm[img]\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#right").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"sm[img]\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right").html(html);
    thickboxInit();
}

//短视频包封面
function smpscreenshotAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"sm[img]\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#right").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"sm[img]\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right").html(html);
    thickboxInit();
}

//主题关联wiki图片上传lfc
function themeitemAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"img\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#right").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"img\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right").html(html);
    thickboxInit();
}



//运营商图片上传lfc

function spitemAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"sp[logo]\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#right").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"sp[logo]\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right").html(html);
    thickboxInit();
}



//推荐列表大图上传lfc

function recommandpicAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"pic\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#rightpic").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"pic\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#rightpic").html(html);
    thickboxInit();
}

//推荐列表小图上传lfc

function recommandsmallpicAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"smallpic\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#rightsmallpic").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"smallpic\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";        
    });
    $("#rightsmallpic").html(html);
    thickboxInit();
}
//新建推荐剧照上传
function channelrecommendscreenshotAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"pic\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right");
        //var content = right.html();
        $("#right").html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"pic\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();$('#addForm #file-uploads').text('上传剧照');\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right").html(html);
    $("#addForm #file-uploads").text('更新剧照');
    thickboxInit();
}
//推荐剧照修改
function channelrecommendscreenshotupdate(key,url) {
    var html    = '';
    if (undefined != url) {
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"recommend["+key[0].which_recommend+"][pic]\" value=\""+''+"\" />";
        html+="     <img style=\"display:"+'none'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+''+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();$('#up_"+key[0].which_recommend+" a').text('上传剧照');\">删除<\/a>";
        html+="   </li>";
        var right   = $("#right_"+key[0].which_recommend);
        //var content = right.html();
        $("#right_"+key[0].which_recommend).html(html);
        if (undefined == url) {
            $.each(key, function(k,v){
               key  = v.key;
               url  = v.link;
               return ;
            });
        }
        $("#screenshots_"+click).val(key);
        $("#screenshots_pic_"+click).show();
        $("#screenshots_pic_"+click).attr('src', url);
        thickboxInit();        
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
        click       = sort;
        html+="<li id=\"screenshots_index_"+sort+"\">";
        html+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"recommend["+key[0].which_recommend+"][pic]\" value=\""+v.key+"\" />";
        html+="     <img style=\"display:"+'show'+";\" id=\"screenshots_pic_"+sort+"\" src=\""+v.link+"\" alt=\"加载中\" />";
        html+= "<a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();$('#up_"+key[0].which_recommend+" a').text('上传剧照');\">删除<\/a>";
        html+="   </li>";        
    });
    $("#right_"+key[0].which_recommend).html(html);
    $("#up_"+key[0].which_recommend+' a').text('更新剧照');
    thickboxInit();
}
function screenshotAdds(key,url) {
    var html    = '';
    if (undefined != url) {
        screenshotAdd();
        screenshots(key, url);
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        html    += getScreenshotHtml('show',v.key, v.link);
    });
    $("#right").append(html);
    thickboxInit();
}



/**
 *分集剧照
 */
function dramaScreenshot(key,url){
    if (undefined != url) {
        screenshotAdd();
        screenshots(key, url);
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        getDramaScreenshotHtml('show',v.key, v.link,v.mark);
    });
    
    thickboxInit();
}



//栏目分期剧照
function columnDramaScreenshot(key,url){
    if (undefined != url) {
        screenshotAdd();
        screenshots(key, url);
        thickboxInit();
        return ;
    }
    $.each(key, function(k,v){
        getMateDramaScreenshotHtml('show',v.key, v.link,v.mark);
    });

    thickboxInit();
}

//栏目分期剧照
//分集剧照html
function getMateDramaScreenshotHtml(display,key,url,mark) {
    var html     = '';
    html+= "<li class=\"screenshots"+sort+"\" id=\"meta_screenshots\"><span style=\"display:none\"><input type=\"checkbox\" name=\"meta_screenshots[]\" value="+key+" checked=\"checked\"\/><\/span>";
    html+= "<a href=\"#\"><img src="+url+" width=\"100%\"><\/a>"
    html+= "<a id=\"file-uploads\" class=\"update\" href=\"#\">更改<\/a> | <a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
    html+="<\/li>"
    $("#widgets ul").append(html);
}



//电视剧分集剧照html
function getDramaScreenshotHtml(display,key,url,mark) {
    var str     = '';
    var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
    click       = sort;
    str+="<li id=\"screenshots_index_"+sort+"\">";
    str+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"meta[screenshots]["+mark+"][]\" value=\""+key+"\" />";
    str+="     <img style=\"display:"+display+";\" id=\"screenshots_pic_"+sort+"\" src=\""+url+"\" alt=\"加载中\" />";
    str+="     <a href=\"#\">更改</a> | <a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
    str+="   </li>";
    //直接插入
    $("#right_"+mark).append(str);
}


//一般剧照
function getScreenshotHtml(display,key,url) {
    var str     = '';
    var sort    = rnd_str(30,true,true,true) + Math.floor(Math.random()*10000+1);
    click       = sort;
    str+="<li id=\"screenshots_index_"+sort+"\">";
    str+="     <input type=\"hidden\" id=\"screenshots_"+sort+"\" name=\"wiki[screenshots][]\" value=\""+key+"\" />";
    str+="     <img style=\"display:"+display+";\" id=\"screenshots_pic_"+sort+"\" src=\""+url+"\" alt=\"加载中\" />";
    str+="     <a href=\"#\">更改</a> | <a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
    str+="   </li>";
    return str;
}



function getSiteWikiData() {
    var url = $.trim($('#wiki-url').val());
    if (url.length == 0) {
        alert('请输入要采集维基地址！');
        $('#wiki-url').focus();
        return false;
    }

    $.ajax({
       type: "post",
       dataType: "josn",
       url: "<?php echo url_for('wiki/getWikiSiteData') ?>",
       data: "url=" + url + '&model=' + $('#wiki_model').val(),
       beforeSend: function(){
           $('#get-wiki-btn').val('维基采集中..').attr('disabled','disabled');
       },
       success: function(data){
           $.each(eval("("+data+")"), function(k,v) {
               if (k == 'wiki_metas') {
                var htmlheader = '<a href="#" class="button" onClick="javascript:showMain();">编辑主条目</a>';
                htmlheader += '<label>请选择：<select id="showDrama" onchange="showDramaAction();">';
                var htmlbody = '<div class="diversity">';
                for(i in v) {
                    htmlheader += '<option value="'+ v[i].mark + '" id="opt_'+ v[i].mark +'">第 '+ v[i].mark +' 集</option>';
                    htmlbody += '<div class="widget-body" id="drama_'+ v[i].mark + '" style="display:none">';
                    htmlbody += '<ul class="wiki-meta">';
                    htmlbody += '<li><label>分集/名称：</label> <span>第 '+ v[i].mark + ' 集</span>&nbsp; <input type="text" value="'+ v[i].title + '" style="width:70%" name="meta[title][]"></li>';
                    htmlbody += '<input type="hidden" name="meta[mark][]" value="'+ v[i].mark +'">';
                    htmlbody += '<li><label>分集介绍：</label> <textarea rows="30" name="meta[content][]">'+ v[i].content + '</textarea></li>';
                    htmlbody += '<li><input type="button" onclick="javascript:eleRemove('+ v[i].mark +');" value="删除"></li>';
                    htmlbody += '</ul></div>';
                 }
                 htmlheader += '</select></label><a href="javascript:dramaAdd();" class="button">添加分集剧情</a>';
                 htmlbody += '</div>';
                 $('.header-meta').html(htmlheader);
                 $('#admintable').append(htmlbody);
               } else {
                    $('#'+k).val(v);
               }
           });
           $('#get-wiki-btn').val('采集维基').attr('disabled', '');
       },
       error: function() {
           alert('采集失败！');
           $('#get-wiki-btn').val('采集维基').attr('disabled', '');
       }
    });
}

/*弹出层*/
$(document).ready(function() {
    $(".update").click(function(){
        $(this).parent().remove();
    });

//一般情况下的弹出层加载，添加分集的不是这个（待优化）
    $("#file-upload,#file-uploads").fancybox({
        'width'			: 960,
        'height'		: 600,
        'autoScale'		: false,
        'transitionIn'		: 'none',
        'transitionOut'		: 'none',
        'type'                  : 'iframe'
        //'autoDimensions'    : false
    });
    
    $(".add-playlist").fancybox({
        'width'			: 760,
        'height'		: 100,
        'autoScale'		: false,
        'transitionIn'		: 'none',
        'transitionOut'		: 'none',
        'type'			: 'iframe'
        //'autoDimensions'    : false
    });
       
    $(".add-videolist").fancybox({
        'width'			: 760,
        'height'		: 150,
        'autoScale'		: false,
        'transitionIn'		: 'none',
        'transitionOut'		: 'none',
        'type'			: 'iframe'
        //'autoDimensions'    : false
    });            
});

/*提交*/
 function submitform(action){
        if (action) {
            document.adminForm.batch_action.value=action;
        }
        if (typeof document.adminForm.onsubmit == "function") {
            document.adminForm.onsubmit();
        }
        document.adminForm.submit();
    }

--></script>




