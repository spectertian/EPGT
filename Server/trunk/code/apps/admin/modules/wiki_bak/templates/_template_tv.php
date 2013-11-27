<script>
var sort    = 1000;
var click   = '';
var fun     = '';
function fileChange(key, url) {
    if(fun =='stills') {
        tv_stills(key,url);
    }else if(fun =='screenshots') {
        screenshots(key,value);
    }
}
function tv_stills(key ,url) {
    $("#tv_stills_show_pic").attr('src', url);
    $("#tv_stills_pic").val(key);
    tb_remove();
    $("#tv_stills_show_pic").css('display','');
}
function url_save(id){
    click = id;
    //fun   = action;
}
function screenshots(key ,url) {
    $("#screenshots_pic"+click).attr('src', url);
    $("#screenshots"+click).val(key);
    $("#screenshots_pic"+click).show();
    tb_remove();
}

//初始化编辑器
function init_bjq()
{
    $("#wiki_key_drama" + show).hide();
    var id  = $("#drara_other").val();
    //防止编辑器重复初始化
    $(".init").addClass('mceNoEditor');

    
    $(".init").addClass('mceNoEditor');
    var ele = $("#key_drama_value"+id);
    if(ele.hasClass('init')){
        return ;
    }
    ele.removeClass('mceNoEditor');
    ele.addClass('init');
    //编辑器初始化
    tinymc_init();
    ele.addClass('mceNoEditor');
    $("#wiki_key_drama"+id).show();
    $("#drara_name").focus();
    alert(show+':'+id);
    show    = id;
}

function showAll()
{
    //防止编辑器重复初始化
    $(".init").addClass('mceNoEditor');
    
    $("select option").each(function(){
        var id  = $(this).val();
        //开始初始化编辑器
        var ele = $("#key_drama_value"+id);
        if(ele.hasClass('init')){
            $("#wiki_key_drama"+id).show();
            return ;
        }
        ele.removeClass('mceNoEditor');
        ele.addClass('init');
        $("#wiki_key_drama"+id).show();
        //编辑器初始化
        tinymc_init();
        //添加编辑器初始化标识
        ele.addClass('mceNoEditor');
    });
    $("#drara_name").focus();
}
</script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>Wiki【电视剧】</legend>
        <table class="admintable" id="html">
            <tbody>
                <tr>
                    <td class="key"><label for="wiki_title">电视剧名称</label></td>
                    <td>
                        <input type="text" id="wiki_title" name="wiki[title]" value="<?php echo $form->getObject()->getTitle();?>">
                        <input type="hidden" id="wiki_style" name="wiki[style]" value="<?php echo $sf_request->getParameter('style');?>">
                    </td>
                </tr>
                
                <tr>
                    <td class="key"><label for="wiki_alias">电视剧别名</label></td>
                    <td>
                        <input type="text" id="wiki_alias" name="wiki_alias" value="<?php echo $form->getObject()->getAlias();?>"  size="50">(逗号分割)
                    </td>
                    </tr>
                <tr>

                <tr>
                    <td class="key"><label for="wiki_title">维基标签</label></td>
                    <td>
                        <input type="text" id="wiki_tags" name="wiki_tags" value="<?php echo $form->getObject()->getTags();?>"  size="70">
                    </td>
                    </tr>
                <tr>
                    
                <tr>
                    <td class="key"><label for="wiki_content">电视剧情</label></td>
                    <td>
                        <textarea name="wiki[content]" class="init" style="width: 100%;"><?php echo $form->getObject()->getContent(); ?></textarea>
                    </td>
                </tr>

                <tbody id="drama">
                </tbody>
                <?php
                $i = 0;
                $class  = 'init';
                $show   = 'show';
                foreach ($form->getObject()->getDramaAll() as $rs) {
                $i ++;
                ?>
                <tr class="wiki_key_drama" id="wiki_key_drama<?php echo $rs->getId();?>" style="display: <?php echo $show;?>">
                    <td class="key">
                        <a href="javascript:drama_ajax_del(<?php echo $rs->getId();?>,<?php echo $rs->getSort();?>);"> 删除本集</a><br/>
                        <a href="javascript:drama_ajax_hide(<?php echo $rs->getId();?>);"> 隐藏本集</a><br/>
                        <label for="wiki_drama">第<?php echo $rs->getSort();?>集剧情</label>
                    </td>
                    <td>
                        <?php $arr  = json_decode($rs->getWikiValue());?>
                        <input type="hidden" id="key_drama" name="WikiKey[]" value="drama">
                        集数：<input type="text" format="*N" value="<?php echo $rs->getSort();?>" name="drama[sort][]" id="key_Sort<?php echo $rs->getSort();?>">
                        <input type="hidden" id="key_drama_hide<?php echo $rs->getId();?>" name="drama[Old][]" value="<?php echo $rs->getSort();?>">
                        该集标题：<input type="text" value="<?php echo $arr->title;?>" name="drama[title][]" id="key_Sort_title<?php echo $i;?>" class="juqing">
                        <textarea  class="<?php echo $class;?>" name="drama[value][]" style="width: 100%;"id="key_drama_value<?php echo $rs->getId();?>"><?php echo $arr->msg;?></textarea>
                    </td>
                 </tr>
                 <?php $class ='mceNoEditor';?>
                 <?php $show  ='none';?>
                <?php } ?>
                <tr>
                    <td class="key"><label for="wiki_title">剧情</label></td>
                    <td>
                        <input id ="drara_name" size="10"/><input type="button" value="添加剧情" onclick="drama_add();"/>
                        <input type="button" value="显示全部" onclick="showAll();"/>
                        <select id="drara_other" onchange="init_bjq();">
                            <?php foreach ($form->getObject()->getDramaAll() as $rss) { ?>
                            <option value="<?php echo $rss->getId();?>">第 <?php echo $rss->getSort();?> 集 </option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</div>
<div class="col width-40">
    <div class="pane-sliders" id="menu-pane">
        <div class="panel">
            <h3 id="param-page" class="title jpane-toggler-down"><span>辅助参数</span>&nbsp;&nbsp;&nbsp;<a href="<?php echo url_for('media/link')?>?function_name=screenshots_adds&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save(1);">插入剧照</a></h3>
            <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody  id="index">
                        <tr>
                            <?php $show = 'show';?>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="stills">封面</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" value="stills" name="ext[Stills][WikiKey]" id="key_pic">
                                    <input type="hidden" id="key_drama" name="ext[Stills][Sort]" value="0">
                                    <input name="ext[Stills][WikiValue]" id="tv_stills_pic" class="mceNoEditor" value="<?php echo $form->getObject()->getStills();?>" type="hidden"/>
                                    <a href="<?php echo url_for('media/link')?>?function_name=tv_stills&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save(1);">上传封面</a>
                                    <br/>
                                    <?php $pic  =  file_url($form->getObject()->getStills());
                                        if(empty($pic)){
                                            $show   = 'none';
                                        }
                                    ?>
                                    <img id="tv_stills_show_pic" src="<?php echo $pic;?>" alt="加载中" style="display: <?php echo $show;?>"/>
                            </td>
                        </tr>
                        
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="director">导演</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" value="director" name="ext[Director][WikiKey]" id="key_director">
                                    <input type="hidden" id="key_drama" name="ext[Director][Sort]" value="0">
                                    <input type="text" size="63" value="<?php echo $form->getObject()->getDirector();?>" name="ext[Director][WikiValue]" id="director">
                            </td>
                        </tr>

                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="starring">主演</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" value="主演" name="title[]" id="title_starring">
                                    <input type="hidden" value="starring" name="ext[Starring][WikiKey]" id="key_starring">
                                    <input type="hidden" id="key_drama" name="ext[Starring][Sort]" value="0">
                                    <input type="text" size="63" value="<?php echo $form->getObject()->getStarring();?>" name="ext[Starring][WikiValue]" id="starring">
                            </td>
                        </tr>

                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="episodes">集数</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Episodes][Sort]" value="0">
                                    <input type="hidden" value="episodes" name="ext[Episodes][WikiKey]" id="key_episodes">
                                    <input type="text" size="63" value="<?php echo $form->getObject()->getEpisodes();?>" name="ext[Episodes][WikiValue]" id="episodes">
                            </td>
                        </tr>

                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="area">地区</label></span></td>
                            <td class="paramlist_value">
                                        <input type="hidden" id="key_drama" name="ext[Area][Sort]" value="0">
                                        <input type="hidden" value="area" name="ext[Area][WikiKey]" id="key_area">
                                        <input type="text" size="55" value="<?php echo $form->getObject()->getArea();?>" name="ext[Area][WikiValue]" id="WikiKey_area"><input type="button" onclick="button_clear('WikiKey_producer');" value="重置">
                           </td>
                       </tr>

                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="produced">出品年份</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Produced][Sort]" value="0">
                                    <input type="hidden" value="produced" name="ext[Produced][WikiKey]" id="key_year">
                                    <input type="text" size="63" value="<?php echo $form->getObject()->getProduced();?>" name="ext[Produced][WikiValue]" id="year">
                            </td>
                        </tr>
                        
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="company">出品公司</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Company][Sort]" value="0">
                                    <input type="hidden" value="company" name="ext[Company][WikiKey]" id="key_company">
                                    <input type="text" size="63" value="<?php echo $form->getObject()->getCompany();?>" name="ext[Company][WikiValue]" id="company">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="broadcast_time">播出时间</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[BroadcastTime][Sort]" value="0">
                                    <input type="hidden" value="broadcast_time" name="ext[BroadcastTime][WikiKey]" id="key_time">
                                    <input type="text" size="63" value="<?php echo $form->getObject()->getBroadcastTime();?>" name="ext[BroadcastTime][WikiValue]" id="time">
                            </td>
                        </tr>
                        <?php
                        $i = 0;
                        $pic_show   = 'show';
                        foreach ($form->getObject()->getScreenshotAll() as $rs) {
                        $i ++;
                        $pic_url_s  = file_url($rs->getWikiValue());
                        if(empty($pic_url_s)){
                            $pic_show   = 'none';
                        }

                        ?>
                        <tr id="screenshots_index<?php echo $rs->getSort();?>">
                            <td width="40%" class="paramlist_key">剧照</td>
                            <td class="paramlist_value" width="70%">
                                    排序：<input type="text" format="*N" value="<?php echo $rs->getSort();?>" name="screenshots[Sort][]" id="key_parent<?php echo $rs->getSort();?>">
                                    <a href="<?php echo url_for('media/link');?>?function_name=screenshots&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save(<?php echo $rs->getSort();?>);">上传剧照</a><input type="button" value="删除" onclick="ajax_screenshots_del(<?php echo $rs->getId();?>,<?php echo $rs->getSort();?>);">
                                    <input type="hidden" id="key_screenshots<?php echo $rs->getSort();?>" name="screenshots[WikiKey][]" value="screenshots">
                                    <input type="hidden" id="key_screenshots_hide<?php echo $rs->getSort();?>" name="screenshots[Old][]" value="<?php echo $rs->getSort();?>">
                                    <input id="screenshots<?php echo $rs->getSort();?>" name="screenshots[WikiValue][]" type="hidden" value="<?php echo $rs->getWikiValue(); ?>"/>
                                    <br/>
                                    <img id="screenshots_pic<?php echo $rs->getSort();?>" src="<?php echo $pic_url_s;?>" alt="加载中"  style="display: <?php echo $pic_show;?>">
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody></table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        tinymc_init();
        //$(".mceNoEditor").removeClass('mceNoEditor');
        <?php foreach ($form->getObject()->getDramaAll() as $rs) {?>
        //该集标题
        $('#key_Sort_title<?php echo $rs->getSort();?>').simpleAutoComplete(wikiUrl  + '/auto_complete_wiki_ext_WikiValue',{
            autoCompleteClassName: 'autocomplete',
            autoFill: false,
            selectedClassName: 'sel',
            attrCallBack: 'rel',
            identifier: 'WikiKey',
            max       : 20
        });
        <?php } ?>
            
    });
    var show    = $("select option").first().val();
    //$(".init").addClass('mceNoEditor');
    
</script>