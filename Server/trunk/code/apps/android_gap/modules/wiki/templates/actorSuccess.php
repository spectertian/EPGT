<h2 class="tit wt">
	<a href="<?php echo $previous?>" class="plist btnh">返回</a>
	<?php echo $wiki->getTitle()?>
	<?php if($wiki->getEnglishName()): ?>
	/ <span class="alt-title"><?php echo $wiki->getEnglishName() ?></span>
	<?php endif; ?>
	<?php //if($wiki->getNickname()): ?>
	<!-- <span class="alt-title">(<?php //echo $wiki->getNickname() ?>)</span> -->
	<?php //endif; ?>	
</h2>
	<!-- 喜欢/不喜欢/看过/加入片单/分享  -->
	<?php include_partial('nav_tool');?>
		<article>
			<section class="movieintro clear">
				<img src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报"/>
				<ul>
		            <li>
						<?php echo $wiki->getSex()?>
						<?php if($wiki->getBirthday()): ?>
						/ 生于<?php echo $wiki->getBirthday() ?>
						<?php endif; ?>
						
						<?php if($wiki->getBirthplace()): ?>
						/ <?php echo $wiki->getBirthplace() ?>
						<?php endif; ?>						
		            </li>
				<?php if($wiki->getOccupation()): ?>
		            <li>
		                <span>职业：</span>
						<?php echo $wiki->getOccupation()?>
		            </li>					
	            <?php endif; ?>	
				<?php if($wiki->getZodiac()): ?>	
		            <li>
		                <span>星座：</span>
						<?php echo $wiki->getZodiac()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getBloodType()): ?>	
		            <li>
		                <span>血型：</span>
						<?php echo $wiki->getBloodType()?>
		            </li>					
	            <?php endif; ?>	            
				<?php if($wiki->getNationality()): ?>	
		            <li>
		                <span>国籍：</span>
						<?php echo $wiki->getNationality()?>
		            </li>					
	            <?php endif; ?>	            
				<?php if($wiki->getRegion()): ?>	
		            <li>
		                <span>地域：</span>
						<?php echo $wiki->getRegion()?>
		            </li>					
	            <?php endif; ?>	            
				<?php if($wiki->getHeight()): ?>	
		            <li>
		                <span>身高：</span>
						<?php echo $wiki->getHeight()?>
		            </li>					
	            <?php endif; ?>	            
				<?php if($wiki->getWeight()): ?>	
		            <li>
		                <span>体重：</span>
						<?php echo $wiki->getWeight()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getDebut()): ?>	
		            <li>
		                <span>出道日期：</span>
						<?php echo $wiki->getDebut()?>
		            </li>					
	            <?php endif; ?>		            
				<?php if($wiki->getDebut()): ?>	
		            <li>
		                <span>宗教信仰：</span>
						<?php echo $wiki->getDebut()?>
		            </li>					
	            <?php endif; ?>		            	            
				<?php if($wiki->getHtmlCache()): ?>	
		            <li>
		                <span>人物简介：</span>
			            <?php echo $wiki->getHtmlCache(200, ESC_RAW); ?>... <a href="#detail">(展开)&raquo;</a>					             	            	            	            
		            </li>					
	            <?php endif; ?>	            
				</ul>
			</section>
			
			<?php if ($film0graphy) :?>
			<section class="play_sty">
				<h2>作品年表</h2>
				<?php $Unpublished = array()?>
				<ul class="clear">
					<?php foreach($film0graphy as $film) :?>
					<?php if ($film->getReleased() && $film->getYear($film->getReleased() > date('Y', time())))
					{
						$Unpublished[] = $film;
						continue;
					}?>				
					<li>
						<?php echo $film->getYear($film->getReleased())?>
						<a href="<?php echo "/wiki/show?slug=".$film->getSlug() ?>" slug="<?php echo $film->getSlug()?>" ><img src="<?php echo thumb_url($film->getCover(), 80, 120)?>" width="80"  height="120" alt="<?php echo $film->getTitle()?>"></a>
						<a href="<?php echo "/wiki/show?slug=".$film->getSlug()?>"><?php echo $film->getTitle()?></a>
					</li>
					<?php endforeach;?>
				</ul>
				
				<?php if(!empty($Unpublished)) :?>
				<ul class="clear">未上映：
	                <?php foreach($Unpublished as $film) :?>
	                <li>
	                    <a href="<?php echo "/wiki/show?slug=".$film->getSlug() ?>"> <?php echo $film->getTitle()?></a>
	                    <?php echo ($year = $film->getYear($film->getReleased())) ? '( '.$year.' )': ''?>
	                </li>
	                <?php endforeach;?>
				</ul>
				<?php endif;?>
			</section>
            <?php endif;?>					
					
		</article>