<script type="text/javascript">
tinymc_init();
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

function screenshots(key ,url) {
    $("#screenshots"+click).val(key);
    $("#screenshots_pic"+click).show();
    $("#screenshots_pic"+click).attr('src', url);
    tb_remove();
}
</script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>Wiki【电影模型】</legend>
        <table class="admintable">
            <tbody>
                <tr>
                    <td class="key"><label for="wiki_title">电影名称</label></td>
                    <td>
                        <input type="text" id="wiki_title" name="wiki[title]" value="<?php echo $form->getObject()->getTitle();?>"  size="70">
                        <input type="hidden" id="wiki_style" name="wiki[style]" value="<?php echo $sf_request->getParameter('style');?>">
                    </td>
                    </tr>
                <tr>
                    
                <tr>
                    <td class="key"><label for="wiki_alias">电影别名</label></td>
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
                    <td class="key"><label for="wiki_content">电影简介</label></td>
                    <td>
                        <textarea name="wiki[content]" style="width: 100%;" id="wiki_content"><?php echo $form->getObject()->getContent(); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="wiki_content">封面</label></td>
                    <td>
                        <input type="hidden" id="key_drama" name="ext[Stills][Sort]" value="0">
                        <input type="hidden" id="key_sccreen_writer" name="ext[Stills][WikiKey]" value="stills">
                        <?php $pic   =  file_url($form->getObject()->getStills());?>
                        <?php if(!$pic){?>
                        <?php $show = 'none';?>
                        <?php }?>
                        <img src="<?php echo $pic;  ?>" id="show_pic" alt="封面加载中" style="display: <?php echo $show;?>;"/>
                        <br/>
                        <input name="ext[Stills][WikiValue]" style="width: 100%;" id="wiki_stills_value" value="<?php echo $form->getObject()->getStills();?>" type="hidden" size="30"/>
                        <a href="<?php echo url_for('media/link')?>?function_name=stills&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save(1);">上传封面</a>
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
                    <tbody id="index">
                        <tr>
                            <td width="40%" class="paramlist_key">英文名:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Ename][Sort]" value="0">
                                    <input type="hidden" id="key_ename" name="ext[Ename][WikiKey]" value="ename">
                                    <input type="text" id="ename" name="ext[Ename][WikiValue]" value="<?php echo $form->getObject()->getEname(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">编剧:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[SccreenWriter][Sort]" value="0">
                                    <input type="hidden" id="key_sccreen_writer" name="ext[SccreenWriter][WikiKey]" value="sccreen_writer">
                                    <input type="text" id="sccreen_writer" name="ext[SccreenWriter][WikiValue]" value="<?php echo $form->getObject()->getSccreenWriter(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">导演:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="hidden" id="key_drama" name="ext[Director][Sort]" value="0">
                                    <input type="hidden" id="key_director" name="ext[Director][WikiKey]" value="director">
                                    <input type="text" id="wiki_key_director" name="ext[Director][WikiValue]" value="<?php echo $form->getObject()->getDirector(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key" width="70%">主演:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Starring][Sort]" value="0">
                                    <input type="hidden" id="key_starring" name="ext[Starring][WikiKey]" value="starring">
                                    <input type="text" id="wiki_key_starring" name="ext[Starring][WikiValue]" value="<?php echo $form->getObject()->getStarring(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key" width="70%">片长:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Time][Sort]" value="0">
                                    <input type="hidden" id="key_timeg" name="ext[Time][WikiKey]" value="time">
                                    <input type="text" id="wiki_time" name="ext[Time][WikiValue]" value="<?php echo $form->getObject()->getTime(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key" width="70%">地区:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Area][Sort]" value="0">
                                    <input type="hidden" id="key_area" name="ext[Area][WikiKey]" value="area">
                                    <input type="text" id="wiki_key_area" name="ext[Area][WikiValue]" value="<?php echo $form->getObject()->getArea(); ?>" size="43"><input type="button" value="重置" onclick="button_clear('wiki_key_producer');">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key" width="70%">国家:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Country][Sort]" value="0">
                                    <input type="hidden" id="key_producer" name="ext[Country][WikiKey]" value="country">
                                    <input type="text" id="wiki_key_producer" name="ext[Country][WikiValue]" value="<?php echo $form->getObject()->getCountry(); ?>" size="43"><input type="button" value="重置" onclick="button_clear('wiki_key_producer');">
                            </td>
                        </tr>

                        <tr>
                            <td width="40%" class="paramlist_key" width="70%">出品年份:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Produced][Sort]" value="0">
                                    <input type="hidden" id="key_produced" name="ext[Produced][WikiKey]" value="produced">
                                    <input type="text" id="wiki_key_produced" name="ext[Produced][WikiValue]" value="<?php echo $form->getObject()->getProduced(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key" width="70%">上映日期:</td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="key_drama" name="ext[Release][Sort]" value="0">
                                    <input type="hidden" id="key_release" name="ext[Release][WikiKey]" value="release">
                                    <input type="text" id="wiki_key_release" name="ext[Release][WikiValue]" value="<?php echo $form->getObject()->getRelease(); ?>" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="paramlist_key">语言:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="hidden" id="key_drama" name="ext[Language][Sort]" value="0">
                                    <input type="hidden" id="key_language" name="ext[Language][WikiKey]" value="language">
                                    <input type="text" id="wiki_key_language" class="reset_value" name="ext[Language][WikiValue]" value="<?php echo $form->getObject()->getLanguage(); ?>" size="43"><input type="button" value="重置" onclick="button_clear('wiki_key_language');">
                            </td>
                        </tr>

                        <?php
                        $i = 0;
                        foreach ($form->getObject()->getScreenshotAll() as $rs) {
                        $i ++;
                        $show   = 'show';
                        $pic_url    = file_url($rs->getWikiValue());
                        if (!$pic_url) {
                            $show   = 'none';
                        }
                        ?>
                        <tr id="screenshots_index<?php echo $rs->getSort();?>">
                            <td width="40%" class="paramlist_key">电影剧照</td>
                            <td class="paramlist_value" width="70%">
                                    排序：<input type="text" format="*N" value="<?php echo $rs->getSort();?>" name="screenshots[Sort][]" id="key_parent<?php echo $rs->getSort();?>">
                                    <a href="<?php echo url_for('media/link');?>?function_name=screenshots&height=600&width=1000&TB_iframe=false" class="thickbox" onclick="url_save(<?php echo $rs->getSort();?>);">上传剧照</a><input type="button" value="删除" onclick="ajax_screenshots_del(<?php echo $rs->getId();?>,<?php echo $rs->getSort();?>);">
                                    <input type="hidden" id="key_screenshots<?php echo $rs->getSort();?>" name="screenshots[WikiKey][]" value="screenshots">
                                    <input type="hidden" id="key_screenshots_hide<?php echo $rs->getSort();?>" name="screenshots[Old][]" value="<?php echo $rs->getSort();?>">
                                    <input id="screenshots<?php echo $rs->getSort();?>" name="screenshots[WikiValue][]" type="hidden" value="<?php echo $rs->getWikiValue(); ?>"/>
                                    <br/>
                                    <img style="display: <?php echo $show;?>;" id="screenshots_pic<?php echo $rs->getSort();?>" src="<?php echo $pic_url;?>" alt="加载中">
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
