<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
			<ul id="navul">
				<li><a href="/search" class="there">搜索</a></li>
				<li><a href="/default/index">智能门户</a></li>
				<li><a href="/vod/index">影片库</a></li>
				<li><a href="/channel/index">一周节目</a></li>
				<li><a href="/user/cliplist">我的片单</a></li>                
			</ul>
		</div>
		<div class="search clr">
			<div class="srh">
				<form method="post" name="formsearch" id="" action="<?php echo url_for('search/list') ?>">
					<input type="text" class="src" name="q"/>
					<a href="#" class="srg" onclick="formsearch.submit();"></a>
				</form>				
				<dl id="program_relative">
					<dt>节目相关</dt>
					<dd>
						<ul class="clr" id="tags">
							<!-- 节目相关tag -->
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>热门搜索</dt>
					<dd>
						<ul class="clr" id="searchSuggest">
							<!-- 搜索关键词 -->
						</ul>
					</dd>
				</dl>
			</div>
			<div class="goodmovie">
				<h2>影片推荐</h2>
				<ul id="media_recommend">
                    <?php 
                          foreach($wikis as $wiki):
                    ?>                  
					<li>
						<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>">
							<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" alt=""/>
							<span><b><?php echo $wiki->getTitle();?></b></span>
						</a>
					</li>
                    <?php
                          endforeach;
                    ?>
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
</div>
<script type="text/javascript">
    function initPage() {
        publicInit();
        playVideo();
        $("#navul").splotNav();
        var currentSer = getCurrentService();
        var url = '<?php echo url_for('program/GetCurrentProgramTagsByChannelname?channelname=')?>';
        $.ajax({
            url: url+currentSer.name,
            type: 'get',
            dataType: 'html',
            success: function(data){
                if(!data) {
                    $('#program_relative').style("visibility","hidden");;
                }else{
                    $('#program_relative').style("visibility","visible");;
                    $('#tags').html(data);   
                }                
            }
        });
        getSearchSuggest();
        $("#media_recommend").scroll("b",4); 
    }
     
    
    function getSearchSuggest(keywords){
        $.ajax({
            url: '<?php echo url_for('search/searchSuggest')?>',
            type: 'post',
            dataType: 'html',
            data: {keyword: keywords},
            success: function(data){
                $('#searchSuggest').html(data); 
            }
        });
    }
</script>