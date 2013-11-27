<?php include_partial("wiki/screenshots"); ?>
<div id="content">
    <div class="content_inner">
        <?php include_partial('toolbarList',array('pageTitle'=>'添加推荐'))?>
        <?php include_partial('global/flashes') ?>
        <div class="table_nav">
            <form method="post" action="" enctype="multipart/form-data" name="editfrom">
                <tbody>
                <table>
                    <tr>
                        <td style="width: 6%;">名称</td>
                        <td><input type="text"  name="name" size="30" style="width: 150px;" id="name"> (推荐的名称，不可修改)</td>
                    </tr>
                    <tr>
                        <td>区域</td>
                        <td>
                            <select name="scene">
                                <option value="">请选择区域</option>
                                <option value="index">首页</option>
								<option value="list">列表</option>
								<option value="channel">栏目</option>
								<option value="search">搜索</option>
								<option value="indexhot">热门排行</option>
								<option value="tcl_index_hotplay">tcl首页热播推荐</option>
                            </select>
                            (表示显示在哪里，如首页(index))</td>
                    </tr>
                    <tr>
                        <td>排序</td>
                        <td><input type="text"  name="sort" size="30" style="width: 150px;"> （表示所推荐的显示前后,请使用数字，数字越小，所将会显示在更前面,从1开始）</td>
                    </tr>
                     <tr>
                        <td>显示</td>

                        <td><input type="radio" name="ispublic" value="true" > 显示 <input type="radio" name="ispublic" value="false"  > 不显示</td>
                    </tr>
                    <tr>
                        <td>大图片</td>
                        <td><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=recommandpicAdds">上传图片</a>( 请上传清晰度的相片 980*300)<div id="rightpic">
		            </div> <!--<input type="file" name="pic" id="pic" />--> 
                        </td>
                    </tr>
                    <tr>
                        <td>小图片</td>
                        <td><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=recommandsmallpicAdds">上传图片</a>( 请上传清晰度的相片 160*90)<div id="rightsmallpic">
		            </div><!--<input type="file" name="smallpic" id="pic" />-->
                        </td>
                    </tr>
                    <tr>
                        <td>推荐</td>
                        <td>
                            <textarea name="recommend_title" rows="3" cols="20" style="width: 80%;"></textarea>
                            <br />(推荐的原因或者宣传词)
                        </td>
                    </tr>
                    <tr>
                        <td>内容</td>
                        <td><input type="radio" name="isdesc" value="true" > 显示 <input type="radio" name="isdesc" value="false"  > 不显示 (推荐内容是否显示)</td>
                    </tr>
                    <tr>
                        <td>链接</td>
                        <td>
                            <input type="text" name="url" value="" style="width: 150px;"/> (链接地址)
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="保存" /></td>
                    </tr>
                </table>
                </tbody>
            </form>
        </div>
    </div>
</div>
