<form method="get" action="">
    选择模型：
              <select name="m">
                        <option value="all"<?php echo ('all' == $m) ? 'selected=selected' : ''?>>全部</option>
                        <option value="film"<?php echo ('film' == $m) ? 'selected=selected' : ''?>>电影</option>
                        <option value="teleplay"<?php echo ('teleplay' == $m) ? 'selected=selected' : ''?>>电视剧</option>
                        <option value="television"<?php echo ('television' == $m) ? 'selected=selected' : ''?>>栏目</option>
                        <option value="actor"<?php echo ('actor' == $m) ? 'selected=selected' : ''?>>艺人</option>
                    </select>

    选择节目：
              <select name="j">
                        <option value="all"<?php echo ('all' == $j) ? 'selected=selected' : ''?>>全部</option>
                        <option value="电影"<?php echo ('电影' == $j) ? 'selected=selected' : ''?>>电影</option>
                        <option value="娱乐"<?php echo ('娱乐' == $j) ? 'selected=selected' : ''?>>娱乐</option>
                        <option value="少儿"<?php echo ('少儿' == $j) ? 'selected=selected' : ''?>>少儿</option>
                        <option value="科教"<?php echo ('科教' == $j) ? 'selected=selected' : ''?>>科教</option>
                        <option value="财经"<?php echo ('财经' == $j) ? 'selected=selected' : ''?>>财经</option>
                         <option value="综合"<?php echo ('综合' == $j) ? 'selected=selected' : ''?>>综合</option>
                    </select>
                   &nbsp;<input type="submit" value="查询">
</form>

