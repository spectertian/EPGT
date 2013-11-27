<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
			<ul>
				<li><a href="/default/index" class="there">智能门户</a></li>
				<li><a href="/vod">影片库</a></li>
				<li><a href="#">一周节目</a></li>
				<li><a href="/user/cliplist">我的片单</a></li>
				<li><a href="/search">搜索</a></li>
			</ul>
		</div>
		
		<div class="liststy playnow">
			<div class="hlist">
				<ul class="clr">
                    <?php include_component('default','liveList');?>
				</ul>
			</div>
		</div>
		
		<div class="liststy youlike">
			<div class="hlist">
				<ul class="clr">
                    <?php 
                          $r=1;
                          foreach($recommends as $recommend):
                          $wiki = $recommend->getWiki();
                    ?>                
					<li>
						<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>">
							<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" alt="" />
							<span><?php echo mb_strcut($wiki->getTitle(), 0, 12, 'utf-8');?></span>
						</a>
					</li>
            		<?php
                          $r++; 
                          endforeach;
                    ?>
				</ul>
			</div>
		</div>
		
		<div class="liststy playnow commant">
			<div class="hlist">
				<ul>
					<li>
						<a href="#">
							<img src="/pic/5.jpg" alt="" />
							<span>热血青春</span>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="/pic/5.jpg" alt="" />
							<span>热血青春热血青春热血青春热血青春热血青春</span>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="/pic/5.jpg" alt="" />
							<span>热血青春</span>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="/pic/5.jpg" alt="" />
							<span>热血青春</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="help">
			<ul>
				<li><img src="/img/fx.jpg" alt="选择"/>选择</li>
				<li><img src="/img/ok.jpg" alt="选择"/>进入</li>
				<li><img src="/img/cd.jpg" alt="选择"/>云媒体首页</li>
				<li><img src="/img/pd.jpg" alt="选择"/>帮助</li>
			</ul>
		</div>
	</div>