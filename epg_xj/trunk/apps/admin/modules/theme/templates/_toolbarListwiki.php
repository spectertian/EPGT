            <header>
				<h2 class="content">管理专题:<?php echo $theme->getTitle()?></h2>
				<nav class="utility">
                  <?php if($add):?><li class="add"><a href="<?php echo url_for("theme/addwiki?id=$id")?>">添加</a></li><?php endif;?>
                  <li class="back"><a href="<?php echo url_for("theme/listwikis?id=$id")?>">返回wiki</a></li>
				  <li class="back"><a href="<?php echo url_for("theme/index")?>">返回专题</a></li>
				</nav>
			</header>