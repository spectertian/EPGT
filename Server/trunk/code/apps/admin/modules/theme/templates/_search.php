<form method="get" action="">
                    专题名称:
            <input type="text" value="<?php echo $mc?>" name="mc" id="mc">
                    模型选择:
            <select name="scene">
				<?php 
						foreach ($sceneArray as $k=>$v)
						{
							echo "<option value=\"{$k}\"";
							 if($k==$scene)
								{
								 	echo 'selected ="true"' ;
								}
							echo ">{$v}</option>";
						}
					?>
			</select>
            <input type="submit" value="查询">
            <div class='paginator'>
              <?php if($page!=1): ?>
              <span class="first-page">
                <a href="/theme?page=1&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">最前页 </a>
              </span>
              <?php endif; ?>
              <?php if($page>1): ?>
              <span class="prev-page">
                <a href="/theme?page=<?php echo $page-1; ?>&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">上一页</a>
              </span>
              <?php endif; ?>
              <span class="pages">
              <?php foreach ($pagegroup as $v): ?>
              <?php 
                if($page ==$v) 
                  echo '<span class="present">'.$v.'</span>';
                else
                  echo '<a href="/theme?page='.$v.'&scene='.$scene.'&mc='.$mc.'">'.$v.'</a>';
              ?>
              <?php endforeach;?>
              </span>
              <?php if($page<$pagetotal): ?>
              <span class="next-page">
                <a href="/theme?page=<?php echo $page+1; ?>&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">下一页</a>
              </span>
              <?php endif; ?>
              <?php if($page!=$pagetotal): ?>
              <span class="last-page">
                <a href="/theme?page=<?php echo $pagetotal; ?>&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">最末页</a>
              </span>
              <?php endif; ?>
            </div>
</form><br />