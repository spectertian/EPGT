<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
            <div class="navh">
    			<ul id="navul">
    				<li><a href="/search" class="there">搜索</a></li>
    				<li><a href="/default/index">智能门户</a></li>
    				<li><a href="/vod/index">影片库</a></li>
    				<li><a href="/channel/index">一周节目</a></li>
    				<li><a href="/user/cliplist">我的片单</a></li>                
    			</ul>
            </div>
		</div>
		<div class="search clr">
			<div class="srh">
				<form method="post" name="formsearch" id="" action="<?php echo url_for('search/list') ?>">
					<input type="text" class="src" name="q"/>
					<a class="srg" onclick="postSearch();" href="#"></a>
				</form>				
				<dl id="program_relative">
					<dt>节目相关</dt>
					<dd>
						<ul class="clr" id="tags">
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>热门搜索</dt>
					<dd>
						<ul class="clr" id="searchSuggest"></ul>
					</dd>
				</dl>
			</div>
			<div class="goodmovie">
				<h2>节目推荐</h2>
				<ul id="media_recommend">
                <?php if($refer=='local'||$refer=='tcl'):?>
                    <?php foreach($wikis as $wiki):?> 
                    <?php if(!$wiki) continue;?>                  
					<li>
						<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>">
							<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" alt=""/>
							<span><b><?php echo $wiki->getTitle();?></b></span>
						</a>
					</li>
                    <?php endforeach;?>
                <?php else: //运营中心的?>
                    <?php 
                          foreach($wikis as $wiki):
                          if($wiki['poster']):
                    ?>          
                	<li>
                		<a href="<?php echo $wiki['url']; ?>">
                			<img src="<?php echo $wiki['poster'];?>" />
                			<span><b><?php echo $wiki['Title'] ;?></b></span>
                		</a>
                	</li>
                	<?php
                          endif;
                          endforeach;
                    ?>
                <?php endif;?>
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
        Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
        getSearchSuggest();
        publicInit();
        playVideoReturn();
        $("#navul").animateNav({speed: 10, step: 75, width: 150});
        getCurrentProgramTags();
        //$("#media_recommend").textScroll({"strnum":4});
        $("#media_recommend").scroll("b",4); 
        $('#program_relative').style("visibility","hidden");
        $('#navul').find('a')[0].focus();
    }
    
    function postSearch() {
        if(formsearch.q.value == '') {
            showTip("请输入关键词!");
            formsearch.q.focus();
            return false;
        }else {
            this.location = '<?php echo $search_url;?>?searchword=' + formsearch.q.value + '&channelid=256823&BackUrl='+window.location.href;
        }
    }
    
    function goSearch(tag) {
        this.location = '<?php echo $search_url;?>?searchword=' + tag + '&channelid=256823&BackUrl='+window.location.href;
    }
    
    function getCurrentProgramTags() {
        var currentSer = getCurrentService();
        var programs=currentSer.getPrograms(0);
        starttime=new Date();
        for(var j = 0; j < programs.length; j++) {
            if(starttime>=programs[j].startTime && starttime<programs[j].endTime){
                keyword=programs[j].name;
                keyword=keyword.replace(/[^\u4e00-\u9fa5]/gi,""); //只获取汉字，去掉诸如(5)一类的
                //alert(keyword);
            }
        }        
        var url = '<?php echo url_for('program/GetCurrentProgramTagsByChannelname?channelname=')?>';
        $.ajax({
            //url: url+currentSer.name+"&keyword="+keyword,
            url: '<?php echo url_for('program/GetCurrentProgramTagsByChannelname')?>',
            type: 'post',
            dataType: 'html',
            data: {channelname:currentSer.name,keyword: keyword},
            success: function(data){
                if(data=='null') {
                    $('#program_relative').style("visibility","hidden");
                }else{
                    $('#program_relative').style("visibility","visible");
                    $('#tags').html(data);   
                }                
            }
        });
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