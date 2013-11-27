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

		<div class="srhlist">
			<div class="srh">
				<form method="post" name="formsearch" id="" action="<?php echo url_for('search/list') ?>">
					<input type="text" class="src" name="q"/>
					<a href="#" class="srg" onclick="formsearch.submit();"></a>
				</form>
			</div>
			
			<div class="movielist">
					<ul class="clr">
                    <?php if ($wiki_pager->count() > 0):?>
                        <?php $i = 0; ?>
                        <?php foreach ($wiki_pager as $wiki): ?>
						<li>
							<a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" id="<?php echo "a".$i;?>" <?php if($i % 7 == 6) echo "class='last'" ?> <?php if($i % 7 == 0) echo "class='first'"?>>
								<img src="<?php echo thumb_url($wiki->getCover(), 114, 152)?>" alt=""/>
								<span><b><?php echo $wiki->getTitle();?></b></span>
							</a>
						</li>
                        <?php $i++;?>
                        <?php endforeach;?>
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
                <li><img src="/img/backs.png" alt=""/></li>
                <li><img src="/img/nexts.png" alt=""/></li>
			</ul>
		</div>
	</div>
	
</div>
<script type="text/javascript">
function initPage() {	
	publicInit();
    $("#navul").splotNav();
    ckeckPageEvt();	
    $("#a0")[0].focus();
    playVideo();
    $(".clr").scroll("b",4);  
}

function ckeckPageEvt() {
    $(".last").each(function(){
        $(this).keydown(function(evt){
            var evtcode = evt.which ? evt.which : evt.code;
            switch (evtcode) {
                case jLim.VK_RIGHT: 
                  showPageNext();
                  evt.preventDefault();
                  break;
            } 
            return false;
        });
    });
    $(".first").each(function(){
        $(this).keydown(function(evt){
            var evtcode = evt.which ? evt.which : evt.code;
            switch (evtcode) {		
                case jLim.VK_LEFT:
                  showPagePrior();
                  evt.preventDefault();
                  break;
            } 
            return false;
        });
    });
    $("#a0")[0].focus();
}

function showPagePrior() {   
    var page = <?php echo $page?>;
    if(page <= 1) {
        showTip("已经是第一页了！");
    }else{
        page=page-1;
        location.href="/search/list/q/<?php echo $q?>/page/"+page;
        ckeckPageEvt();  
    }
}

function showPageNext() {
    var page = <?php echo $page?>;
    page=page+1;
    location.href="/search/list/q/<?php echo $q?>/page/"+page;
    ckeckPageEvt();
}
</script>