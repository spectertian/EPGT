<script type="text/javascript">
tinymc_init();
var sort = 1000;
var click = '';
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
}

function screenshots(key ,url) {
    $("#screenshots_pic"+click).attr('src', url);
    $("#screenshots"+click).val(key);
    tb_remove();
}
</script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>Wiki【栏目模型】</legend>
        <table class="admintable">
            <tbody>
                <tr>
                    <td class="key"><label for="wiki_title">栏目名称</label></td>
                    <td>
                        <input type="text" id="wiki_title" name="wiki[title]" value="<?php echo $form->getObject()->getTitle();?>"  size="70">
                        <input type="hidden" id="wiki_style" name="wiki[style]" value="<?php echo $sf_request->getParameter('style');?>">
                    </td>
                    </tr>
                <tr>
                    
                <tr>
                    <td class="key"><label for="wiki_tags">维基标签</label></td>
                    <td>
                        <input type="text" id="wiki_tags" name="wiki_tags" value="<?php echo $form->getObject()->getTags();?>"  size="70">
                    </td>
                    </tr>
                <tr>
                    <td class="key"><label for="wiki_content">栏目简介</label></td>
                    <td>
                        <textarea name="wiki[content]" style="width: 100%;" id="wiki_content"><?php echo $form->getObject()->getContent(); ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</div>
<div class="col width-40">
    <div class="pane-sliders" id="menu-pane">
        <div class="panel">
            <h3 id="param-page" class="title jpane-toggler-down"><span>辅助参数</span>
                &nbsp;<a href="javascript:screenshots_add();">添加剧照</a>
            </h3>
            <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody id="index">
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="stills">封面</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" value="stills" name="ext[Stills][WikiKey]" id="key_pic">
                                    <input type="hidden" id="key_drama" name="ext[Stills][Sort]" value="0">
                                    <input name="ext[Stills][WikiValue]" id="tv_stills_pic" class="mceNoEditor" value="<?php echo $form->getObject()->getStills();?>" type="hidden"/>
                                    <a href="<?php echo url_for('media/link')?>?function_name=tv_stills&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save(1);">上传封面</a>
                                    <br/>
                                    <?php $pic  =  file_url($form->getObject()->getStills());
                                        if(!$pic){
                                            $show   = 'none';
                                        }
                                    ?>
                                    <img id="tv_stills_show_pic" src="<?php echo $pic;?>" alt="加载中" style="display: <?php echo $show;?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">主持人/嘉宾主持/主讲人/旁白/解说:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_host" name="ext[Host][Sort]" value="0">
                                    <input type="hidden" id="key_host" name="ext[Host][WikiKey]" value="host">
                                    <input type="text" id="wiki_key_host" name="ext[Host][WikiValue]" value="<?php echo $form->getObject()->getHost(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">嘉宾:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_guest" name="ext[Guest][Sort]" value="0">
                                    <input type="hidden" id="key_guest" name="ext[Guest][WikiKey]" value="guest">
                                    <input type="text" id="wiki_key_guest" name="ext[Guest][WikiValue]" value="<?php echo $form->getObject()->getGuest(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">播出时间:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_play_time" name="ext[PlayTime][Sort]" value="0">
                                    <input type="hidden" id="key_play_time" name="ext[PlayTime][WikiKey]" value="play_time">
                                    <input type="text" id="wiki_key_Play_time" name="ext[PlayTime][WikiValue]" value="<?php echo $form->getObject()->getPlayTime(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">播出频道:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="hidden" id="key_play_channel" name="ext[PlayChannel][Sort]" value="0">
                                    <input type="hidden" id="key_play_channel" name="ext[PlayChannel][WikiKey]" value="play_channel">
                                    <input type="text" id="wiki_key_play_channel" name="ext[PlayChannel][WikiValue]" value="<?php echo $form->getObject()->getPlayChannel(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key" width="70%">地区:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_area" name="ext[Area][Sort]" value="0">
                                    <input type="hidden" id="key_area" name="ext[Area][WikiKey]" value="area">
                                    <input type="text" id="wiki_key_area" name="ext[Area][WikiValue]" value="<?php echo $form->getObject()->getArea(); ?>" size="43"><input type="button" value="重置" onclick="button_clear('wiki_key_area');">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">语言:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="hidden" id="key_language" name="ext[Language][Sort]" value="0">
                                    <input type="hidden" id="key_language" name="ext[Language][WikiKey]" value="language">
                                    <input type="text" id="wiki_key_language" class="reset_value" name="ext[Language][WikiValue]" value="<?php echo $form->getObject()->getLanguage(); ?>" size="43"><input type="button" value="重置" onclick="button_clear('wiki_key_language');">
                            </td>
                        </tr>
                        <?php
                        $i = 0;
                        foreach ($form->getObject()->getScreenshotAll() as $rs) {
                        $i ++;
                        ?>
                        <script type="text/javascript">
                            sort = <?php echo $rs->getSort() + 1 ; ?>
                        </script>
                        <tr id="screenshots_index<?php echo $rs->getSort();?>">
                            <td width="40%" class="paramlist_key">电影剧照</td>
                            <td class="paramlist_value" width="70%">
                                    排序：<input type="text" format="*N" value="<?php echo $rs->getSort();?>" name="screenshots[Sort][]" id="key_parent<?php echo $rs->getSort();?>">
                                    <a href="<?php echo url_for('media/link');?>?function_name=screenshots&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save(<?php echo $rs->getSort();?>);">上传剧照</a><input type="button" value="删除" onclick="ajax_screenshots_del(<?php echo $rs->getId();?>,<?php echo $rs->getSort();?>);">
                                    <input type="hidden" id="key_screenshots<?php echo $rs->getSort();?>" name="screenshots[WikiKey][]" value="screenshots">
                                    <input type="hidden" id="key_screenshots_hide<?php echo $rs->getSort();?>" name="screenshots[Old][]" value="<?php echo $rs->getSort();?>">
                                    <input id="screenshots" name="screenshots[WikiValue][]" type="hidden" value="<?php echo $rs->getWikiValue(); ?>"/>
                                    <br/>
                                    <img style="" alt="加载中" id="screenshots_pic<?php echo $rs->getId();?>'" src="<?php echo file_url($rs->getWikiValue());?>" alt="加载中">
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
