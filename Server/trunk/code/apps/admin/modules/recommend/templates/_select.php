<form method="get" action="">
    选择模型：
              <select name="m">
                        <option value="all"<?php echo ('all' == $m) ? 'selected=selected' : ''?>>全部</option>
                        <option value="index"<?php echo ('index' == $m) ? 'selected=selected' : ''?>>首页</option>
                        <option value="indexhot"<?php echo ('indexhot' == $m) ? 'selected=selected' : ''?>>热门排行</option>
                        <option value="tcl_index_hotplay"<?php echo ('tcl_index_hotplay' == $m) ? 'selected=selected' : ''?>>tcl首页热播推荐</option>
                        <option value="hncatv_index_hotplay"<?php echo ('hncatv_index_hotplay' == $m) ? 'selected=selected' : ''?>>河南广电首页推荐</option>
                    </select>
    是否显示：
              <select name="s">
                        <option value="2" <?php echo ($show==2) ? 'selected=selected' : ''?>>全部</option>
                        <option value="1" <?php echo ($show==1) ? 'selected=selected' : ''?>>显示</option>
                        <option value="-1" <?php echo ($show==-1) ? 'selected=selected' : ''?>>不显示</option>
                    </select>
                   &nbsp;<input type="submit" value="查询">
</form>