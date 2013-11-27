<form method="get" action="">类型
<select name="m" id="m">
<option value="">不限</option>
<option value="teleplay" <?php echo ($m == 'teleplay') ? 'selected=selected' : ''?>>电视剧</option>
<option value="film" <?php echo ($m == 'film') ? 'selected=selected' : ''?>>电影</option>
<option value="television" <?php echo ($m == 'television') ? 'selected=selected' : ''?>>栏目</option>
</select>
                    名称：
                    <input type="text" value="<?php echo $q?>" name="q" id="q">
                    <input type="submit" value="查询">
                    <input type="button" value="清空" onClick="clearSearch(this)">&nbsp;
                    <!--
          <a<?php echo ('film' == $model) ? ' class="active"' : ''?>  href="<?php echo url_for('video/temp?model=film')?>"><strong>电影</strong></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a<?php echo ('teleplay' == $model) ? ' class="active"' : ''?> href="<?php echo url_for('video/temp?model=teleplay')?>"><strong>电视剧</strong></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a<?php echo ('television' == $model) ? ' class="active"' : ''?> href="<?php echo url_for('video/temp?model=television')?>"><strong>栏目</strong></a>
          -->