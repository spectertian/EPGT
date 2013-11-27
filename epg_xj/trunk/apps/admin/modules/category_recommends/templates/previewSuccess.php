	<?php include_partial('global/flashes') ?> 
	<div id="warp">
    
      <div class="r">
            	<header>
                    <h2 class="content"><?php echo $PageTitle; ?>:<?php echo $name; ?></h2>
                    <nav class="utility">
                        <li class="back"><a href="<?php echo url_for("category_recommends/list");?>">返回列表</a></li>
                    </nav>
                </header>
				<?php include_partial('global/flashes') ?>
				<div id="stock">
					<?php $re =array(); ?>
					<?php if($templates):?>
						<?php foreach($templates as $k => $v):?>
								<div class="listwarp" id="<?php echo 'stock_'.$k; ?>"><ul class="list" id="<?php echo 'stock_'.$k.'s'; ?>">
							<?php foreach ($v as $k1 => $v1 ):?>
								<?php $nodename = 'stock_'.$v1['row'].'_'.$v1['column'] ?>
									<li class="mvcover"  id="<?php echo $nodename; ?>" ><img src="<?php echo file_url($v1['img']); ?>" alt="<?php echo $v['name']; ?>"><?php echo $v1['name']; ?></li>
							<?php endforeach; ?>
								</ul></div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
    </div>
