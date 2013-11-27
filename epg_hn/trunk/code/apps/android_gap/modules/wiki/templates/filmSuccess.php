<h2 class="tit wt"><a href="<?php echo $previous?>" class="plist btnh">返回</a><?php echo $wiki->getTitle()?></h2>
	<!-- 喜欢/不喜欢/看过/加入片单/分享  -->
	<?php include_partial('nav_tool', array('wiki' => $wiki, 'related_programs' => $related_programs));?>
		<article>
			<section class="movieintro clear">
				<img src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报"/>
				<ul>
				<?php if($Directors = $wiki->getDirector()): $i = 0 ?>
		            <li>
		                <span>导演：</span>
		                <?php foreach($Directors as $Director) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Director;?>
		                <?php endforeach;?>
		            </li>					
				<?php endif; ?>
				<?php if($Writers = $wiki->getWriter()): $i = 0 ?>
		            <li>
		                <span>编剧：</span>
		                <?php foreach($Writers as $Writer) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Writer;?>
		                <?php endforeach;?>
		            </li>
				<?php endif; ?>
				<?php if($Stars = $wiki->getStarring()): $i = 0 ?>
		            <li>
		                <span>主演：</span>
		                <?php foreach($Stars as $Star) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
		                <?php endforeach;?>
		            </li>					
	            <?php endif; ?>
				<?php if($tags = $wiki->getTags()): $i = 0 ?>
		            <li>
		                <span>类型：</span>
		                <?php foreach($tags as $tag) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $tag;?>
		                <?php endforeach;?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getCountry()): ?>
		            <li>
		                <span>国家：</span>
						<?php echo $wiki->getCountry()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getLanguage()): ?>
		            <li>
		                <span>语言：</span>
						<?php echo $wiki->getLanguage()?>
		            </li>					
	            <?php endif; ?>	
				<?php if($wiki->getProduced()): ?>	
		            <li>
		                <span>制作日期：</span>
						<?php echo $wiki->getProduced()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getHtmlCache()): ?>	
		            <li>
		                <span>剧情简介：</span>
			            <?php echo $wiki->getHtmlCache(200, ESC_RAW); ?>... <a href="#detail">(展开)&raquo;</a>					             	            	            	            
		            </li>					
	            <?php endif; ?>	            
				</ul>
			</section>
			
			<section class="play_sty">
				<h2>观看方式</h2>
				<ul class="clear">
				<?php if ($videos = $wiki->getVideos()) :?>
				<?php $status=false?>
				<?php foreach($videos as $video) :?>
					<?php $tvconfig = $video->getConfig();?>
					<?php if ($video->getReferer() == 'qiyi'):?>
						<li>
						<a href="<?php echo "http://proxy.kkttww.net:8080/urlproxy/qiyi/?redirect=1&tv_id=".$tvconfig['tvId']?>"  class="" title="播放<?php echo $video->getRefererZhcn()?>片源" target="_blank"><?php echo $video->getRefererZhcn()?>视频</a>
						<a href="#" onclick='playMedia({"type" : 2, "format": "avi", "url": "1d7c1WCcaMih2c7j4a5aepuYsJmUqGVaeJiYnaGUp2dae6WjnI6xqFd2fYmlpJfc1WY%3d"})'>PPTV视频</a>
						<a href="#" onclick='playMediaToTV({"type" : 2, "format": "avi", "url": "1d7c1WCcaMih2c7j4a5aepuYsJmUqGVaeJiYnaGUp2dae6WjnI6xqFd2fYmlpJfc1WY%3d"})'>PPTV视频ToTV</a>
						</li>
						<?php $status=true?>
					<?php endif;?>
				<?php endforeach;?>
				<?php if($status==false):?>
				<li>很抱歉，该节目还没有奇异片源！</li>
				<?php endif;?>
				<?php else: ?>
				<li>很抱歉，该节目还没有片源！</li>
				<?php endif;?>			
				</ul>
			</section>
			<?php include_partial('program_guide', array('programs' => $related_programs))?>
					
		</article>