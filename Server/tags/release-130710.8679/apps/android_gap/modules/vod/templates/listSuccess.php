        <content class="clr">
        	<menu class="menu" data-role="menu">
            	<ul>
            		<li><a href="<?php echo url_for("/") ?>" class="index">首页</a></li>
                    <li class="there"><a href="<?php echo url_for("/vod/index") ?>" class="zb">直播</a></li>
                    <li><a href="#" class="jj">剧集</a></li>
                    <li><a href="#" class="lm">栏目</a></li>
                    <li><a href="#" class="sz">设置</a></li>	
                </ul>
            </menu>
            
            <div class="content " data-role="page">
            	<div class="ll">
                	<h2 class="tab"><a href="#">频道列表</a><a href="#" class="there">正在播放</a></h2>
                    <div class="clr">
                    	<div class="tvchoice listchoice">
                        	<ul>
                                <li><a href="<?php echo url_for("/vod/index?type=全部&style=".$style)?>" class="all">全部</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=电视剧&style=".$style)?>" class="tv">电视剧</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=电影&style=".$style)?>" class="mv">电影</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=体育&style=".$style)?>" class="sports">体育</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=娱乐&style=".$style)?>" class="entertainment">娱乐</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=少儿&style=".$style)?>" class="children">少儿</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=科教&style=".$style)?>" class="science">科教</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=财经&style=".$style)?>" class="finance">财经</a></li>
                                <li><a href="<?php echo url_for("/vod/index?type=综合&style=".$style)?>" class="total">综合</a></li>
                            </ul>
                        </div>
                    	
                        <div class="daychoice">
                        	<h2 class="taber"><a href="<?php echo url_for("/vod/index?type=".$searchCondition['type']."&style=tile")?>" class="tile">平铺</a><a href="<?php echo url_for("/vod/index?type=".$searchCondition['type']."&style=list")?>" class="listss">列表</a></h2>                        
                            
                            <div class="timechoice tileslist">
                            <?php if ($wiki_pager->count() > 0):?>  
                            	<ul>
                                <?php foreach ($wiki_pager as $wiki): ?>   
                            		<li><a href="#"><?php echo mb_strcut($wiki->getChannel(), 0, 25, 'utf-8')?></a><a href="<?php echo "/wiki/show?slug=".$wiki->getSlug()?>"><?php echo $wiki->getTitle()?></a><span>记录片/剧情</span></li>
                                <?php endforeach;?>        
                            	</ul>
                            <?php endif;?>    
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="lr">
                	<div class="thistv">
                    	<img src="movcover/5.jpg" alt=""/>
                        <ul class="ctrl">
                        	<li><a href="#" class="play"></a></li>
                            <li><a href="#" class="score"></a></li>
                            <li><a href="#" class="sc"></a></li>
                            <li><a href="#" class="full"></a></li>
                        </ul>
                      	<div class="playtime">
                        	<b>红星剧场：甄嬛传30</b>
                            <strong>下一个节目：档案午间版</strong>
                            <span><em style="width:15%;"></em></span>
                            <i>10:50</i><dfn>12:30</dfn>
                        </div>
                    </div>
                    
                    <div class="tvinfor clr">
                    	<a href="#" class="cover"><img src="movcover/6.jpg" alt=""/></a>
                        <ul>
                        	<li><h2>后宫·甄嬛传</h2></li>
                            <li>类型：剧情/古装</li>
                            <li>主演：孙俪/陈建斌/蔡少芬</li>
                            <li>集数：76</li>
                        </ul>
                        <a href="#" class="more">查看详情</a>
                    </div>
                </div>
            </div>
        </content>