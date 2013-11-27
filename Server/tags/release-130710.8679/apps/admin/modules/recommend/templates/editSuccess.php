<?php include_partial("wiki/screenshots"); ?>
<div id="content">
    <div class="content_inner">
        <?php include_partial('toolbarList',array('pageTitle'=>'修改推荐'))?>
        <?php include_partial('global/flashes') ?>
        <div class="table_nav">
            <form method="post" action="" enctype="multipart/form-data" name="editfrom">
                <tbody>
                <table>
                    <tr>
                        <td style="width: 6%;">名称</td>
                        <td><input type="text" value="<?php echo $recommend->getTitle();?>" name="name" size="30" style="width: 150px; background-color:#cccccc" readonly="true"> (推荐的名称，不可修改)</td>
                    </tr>
                    <tr>
                        <td>区域</td>
                        <td>
                            <select name="scene">
                                <option value="">请选择区域</option>
								<option value="index"<?php echo ('index' == $recommend->getScene()) ? 'selected=selected' : ''?>>首页</option>
								<option value="list"<?php echo ('list' == $recommend->getScene()) ? 'selected=selected' : ''?>>列表</option>
								<option value="channel"<?php echo ('channel' == $recommend->getScene()) ? 'selected=selected' : ''?>>栏目</option>
								<option value="search"<?php echo ('search' == $recommend->getScene()) ? 'selected=selected' : ''?>>搜索</option>
								<option value="indexhot"<?php echo ('indexhot' == $recommend->getScene()) ? 'selected=selected' : ''?>>热门排行</option>
								<option value="tcl_index_hotplay"<?php echo ('tcl_index_hotplay' == $recommend->getScene()) ? 'selected=selected' : ''?>>tcl首页热播推荐</option>
                            </select>(表示显示在哪里，如首页(index))</td>
                    </tr>
                    <tr>
                        <td>排序</td>
                        <td><input type="text" value="<?php if($recommend !=null) echo $recommend->getSort();?>" name="sort" size="30" style="width: 150px;"> （表示所推荐的显示前后,请使用数字，数字越小，所将会显示在更前面,从1开始）</td>
                    </tr>
                     <tr>
                        <td>显示</td>
                        <?php 
                        if($recommend!=null) $ispublic = $recommend->getIsPublic();//var_dump($ispublic);
                        ?>
                        <td><input type="radio" name="ispublic" value="true" <?php if ($ispublic==true) echo "checked"?> > 显示 <input type="radio" name="ispublic" value="false" <?php if ($ispublic==false) echo "checked"?> > 不显示</td>
                    </tr>
                    <tr>
                        <td>大图片</td>
                        <td><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=recommandpicAdds">上传图片</a>( 请上传清晰度的相片 980*300)<div id="rightpic">
		            <input type="hidden" name="pic" id="pic" value="<?php echo $recommend->getPic()?>"/></div><br />
                            <?php if($recommend !=NULL) :?>
                            <?php if($recommend->getPic()!=NULL):?>
                            <img src="<?php echo thumb_url($recommend->getPic(), 100,200)?>" >
                            <?php endif;?>
                            <?php endif;?>
                        </td>
                    </tr>
                    <tr>
                        <td>小图片</td>
                        <td><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=recommandsmallpicAdds">上传图片</a>( 请上传清晰度的相片 160*90)<div id="rightsmallpic">
		            <input type="hidden" name="smallpic" id="smallpic" value="<?php echo $recommend->getSmallPic()?>"/></div><br />
                            <?php if($recommend !=NULL) :?>
                            <?php if($recommend->getSmallPic()!=NULL):?>
                            <img src="<?php echo thumb_url($recommend->getSmallPic(), 100,200)?>" >
                            <?php endif;?>
                            <?php endif;?>
                        </td>
                    </tr>                    
                    <tr>
                        <td>推荐</td>
                        <td>
                            <textarea name="recommend_title" rows="3" cols="20" style="width: 80%;"><?php if($recommend !=null) echo $recommend->getDesc();?></textarea> <br />(推荐的原因或者宣传词)
                        </td>
                    </tr>
                    <tr>
                        <td>内容</td>
                        <?php 
                        if($recommend!=null) $isdesc = $recommend->getIsdescDisplay();//var_dump($ispublic);
                        ?>
                        <td><input type="radio" name="isdesc" value="true" <?php if ($isdesc==true) echo "checked"?> > 显示 <input type="radio" name="isdesc" value="false" <?php if ($isdesc==false) echo "checked"?>  > 不显示 (推荐内容是否显示)</td>
                    </tr>
                    <tr>
                        <td>地址</td>
                        <td><input type="text" name="url" value="<?php if($recommend !=null) echo $recommend->getUrl();?>"  style="width: 150px;"/> (图片链接地址)</td>
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