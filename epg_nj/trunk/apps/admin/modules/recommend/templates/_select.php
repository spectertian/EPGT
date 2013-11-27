<form method="get" action="">
    选择场景：
              <select name="m">
                        <option value="all"<?php echo ('all' == $m) ? 'selected=selected' : ''?>>全部</option>
                        <option value="index"<?php echo ('index' == $m) ? 'selected=selected' : ''?>>首页</option>
                        <option value="list"<?php echo ('list' == $m) ? 'selected=selected' : ''?>>列表</option>
                        <option value="channel"<?php echo ('channel' == $m) ? 'selected=selected' : ''?>>栏目</option>
                        <option value="search"<?php echo ('search' == $m) ? 'selected=selected' : ''?>>搜索</option>
                        <option value="indexhot"<?php echo ('indexhot' == $m) ? 'selected=selected' : ''?>>热门排行</option>
                    </select>
    是否显示：
              <select name="s">
                        <option value="2" <?php echo ($show==2) ? 'selected=selected' : ''?>>全部</option>
                        <option value="1" <?php echo ($show==1) ? 'selected=selected' : ''?>>显示</option>
                        <option value="-1" <?php echo ($show==-1) ? 'selected=selected' : ''?>>不显示</option>
                    </select>
                   &nbsp;<input type="submit" value="查询">
</form>