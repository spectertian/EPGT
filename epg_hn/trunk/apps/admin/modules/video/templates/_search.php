<form method="get" action="">
                    名称：
                    <input type="text" value="<?php echo $q?>" name="q" id="q">
                    <input type="submit" value="查询">
                    <input type="button" value="清空" onClick="clearSearch(this)">&nbsp;
          <a<?php echo ('film' == $model) ? ' class="active"' : ''?>  href="<?php echo url_for('video/temp?model=film')?>"><strong>电影</strong></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a<?php echo ('teleplay' == $model) ? ' class="active"' : ''?> href="<?php echo url_for('video/temp?model=teleplay')?>"><strong>电视剧</strong></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a<?php echo ('television' == $model) ? ' class="active"' : ''?> href="<?php echo url_for('video/temp?model=television')?>"><strong>栏目</strong></a>