<script>
tinyMCE.init({
    theme : "advanced", //总共两种模板：advanced和simple
    theme_advanced_toolbar_location : "top", //工具栏默认是在底部，调到顶部的话要加入这个句配置。
    theme_advanced_toolbar_align : "left", //工具栏按钮默认是全局居中，要让按钮左对齐要加入这句配置。
    theme_advanced_statusbar_location : "bottom", //默认是不显示状态栏的（功能和DW的状态栏一样），加入此句子可以调出状态栏并且显示在编辑器下方。
    theme_advanced_resizing : true, //可以动态调整编辑器大小（按钮在编辑器右下方）
    mode : "textareas",
    skin: "default" //这是office风格，挺清爽的。

});
</script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>NBA联盟</legend>
        <table class="admintable" id="html">
            <tbody>
                <tr>
                    <td class="key"><label for="wiki_title">名称</label></td>
                    <td>
                        <input type="text" id="wiki_title" name="wiki[title]" value="<?php echo $form->getObject()->getTitle();?>">
                        <input type="hidden" id="wiki_style" name="wiki[style]" value="<?php echo $sf_request->getParameter('style');?>">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="wiki_content">内容</label></td>
                    <td>
                        <textarea name="wiki[content]" class="mceNoEditor" style="width: 100%;"><?php echo $form->getObject()->getContent(); ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</div>
<div class="col width-40">
    <div class="pane-sliders" id="menu-pane">
        <div class="panel">
            <h3 id="param-page" class="title jpane-toggler-down"><span>辅助参数</span></h3>
            <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody  id="index">
                    </tbody></table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
</script>