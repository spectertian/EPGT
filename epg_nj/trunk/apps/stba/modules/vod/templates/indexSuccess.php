<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
            <div class="navh">
    			<ul id="navul">
                    <li><a href="/default/index">智能门户</a></li>
    				<li><a href="/vod/index"  class="there">影片库</a></li>
    				<li><a href="/channel/index">一周节目</a></li>
                    <li><a href="/user/cliplist">预约管理</a></li>
    			</ul>
            </div>
		</div>
		
		<div class="moviemenu">
			<ul class="clr" id="typeul">
                <li><h3><a href="javascript:void(0);" <?php if($searchCondition['type']=="全部" || $searchCondition['type']=="" ):?> class="there" <?php endif;?> onclick="listForm('type','全部',1);">全部</a></h3></li>
				<?php foreach ($types as $value):?>
				<li><a href="javascript:void(0);" <?php if ($value == $searchCondition['type']):?> class="there" <?php endif;?> onclick="listForm('type','<?php echo $value?>',1);"><?php echo $value;?></a></li>
				<?php endforeach;?>	
			</ul>
			<ul class="clr" id="areaul">
				<li><h3><a href="javascript:void(0);" <?php if($searchCondition['area']=="全部" || $searchCondition['area']=="" ):?> class="there" <?php endif;?> onclick="listForm('area','全部',1);">全部</a></h3></li>
				<?php foreach ($areas as $value):?>
				<li><a href="javascript:void(0);" <?php if ($value == $searchCondition['area']):?> class="there" <?php endif;?>  onclick="listForm('area','<?php echo $value?>',1);"><?php echo $value;?></a></li>
				<?php endforeach;?>	
			</ul>
			<ul class="clr" id="tagul">
				<li><h3><a href="javascript:void(0);" <?php if($searchCondition['tag']=="全部" || $searchCondition['tag']=="" ):?> class="there" <?php endif;?> onclick="listForm('tag','全部',1);">全部</a></h3></li>
                <?php $i = 0;?>
                <?php foreach($wikiTagsRepons as $key => $tags) :?>
                <?php if($i > 11): break; endif;?>
				<li><a href="javascript:void(0);"  <?php  if($searchCondition['tag']==$tags):?> class="there" <?php endif;?>  onclick="listForm('tag','<?php echo $tags?>',1);"><?php echo $tags;?></a></li>
                <?php $i++;?> 
                <?php endforeach;?>       
			</ul>
            <?php
            $class1=$sort==4?'gxrq':'gxrqw';  //更新日期
            $class2=$sort==0?'zntj':'zntjw';  //智能推荐
            ?>
			<!--<p class="px"><span class="<?php echo $class1;?>"></span><span class="<?php echo $class2;?>"></span>推荐：</p>-->
		</div>
        <form name="listform">
        <input type="hidden" name="type" id="f_type" value="<?php echo $searchCondition['type']?>">
        <input type="hidden" name="area" id="f_area" value="<?php echo $searchCondition['area']?>">
        <input type="hidden" name="tag" id="f_tag" value="<?php echo $searchCondition['tag']?>">
        <input type="hidden" name="page" id="f_page" value="<?php echo $page?>">	
        </form>
		<div class="movielist">
            <?php if ($wiki_pager->count() > 0):?>
			<ul id="movieList" class="clr">
                <?php $i = 0; ?>
				<?php foreach ($wiki_pager as $wiki): ?>
				<li>
					<a id="<?php echo "a".$i;?>" href="<?php echo url_for("wiki/show?refer=history&id=".$wiki->getId())?>" <?php if($i % 7 == 6) echo "class='last'" ?> <?php if($i % 7 == 0 and $page > 1) echo "class='first'" ?>>
						<img src="<?php echo thumb_url($wiki->getCover(), 114, 152,$_SERVER['HTTP_HOST'])?>"/>
						<span><b><?php echo $wiki->getTitle();?></b></span>
					</a>
				</li>
                <?php $i++;?>
				<?php endforeach;?>
			</ul>
            <?php else:?>
			<p>该栏目暂无相关内容，请选择其他选项观看节目</p>
            <?php endif;?>	
		</div>
		
		<?php if($page!=1):?><a href="#" class="back backs" style="display: none;"></a><?php endif;?><?php if($page!=$wiki_pager->getLastPage()):?><a href="#" class="next nexts" style="display: none;"></a><?php endif;?>
		
		<div class="help">
			<ul>
                <li><img src="/pic/footvod.png"/></li>
                <!--
				<li>按<img src="/img/sty2/fx.png" alt="选择"/>选择</li>
				<li>按<img src="/img/sty2/ok.png" alt="选择"/>进入</li>
				<li>按<img src="/img/sty2/cd.png" alt="选择"/>云媒体首页</li>
                <li>按<img src="/img/sty2/tv.png" alt="选择"/>进入频道</li>
                <li>按<img src="/img/sty2/bn.png" alt=""/>翻页</li>
                <li>按<font style="color:#00ff00">数字键</font>快速翻页</li>
                -->
                <?php if ($wiki_pager->getLastPage() > 0):?>
                <li><span><?php echo $page?>/<?php echo $wiki_pager->getLastPage();?>页</span></li>
                <?php endif;?>
			</ul>
		</div>
	</div>
	
</div>
<script type="text/javascript">
function initPage() {
	publicInit();
    //$("#navul").animateNav({speed: 10, step: 75, width: 150});
    $('#navul').find('a')[1].focus();
    ckeckPageEvt();	
    $("#movieList").scroll("b",4);                   //wiki标题文字滚动
    playVideo();
    //设置焦点
    var name="<?php echo $name;?>";
    switch(name){
        case "type":
            $("#typeul").find("a").each(function(){
                if($(this).text()=="<?php echo $searchCondition['type'];?>"){
                    this.focus();
                }
            });  
            break;
        case "area":
            $("#areaul").find("a").each(function(){
                if($(this).text()=="<?php echo $searchCondition['area'];?>"){
                    this.focus();
                }
            });  
            break;
        case "tag":
            $("#tagul").find("a").each(function(){
                if($(this).text()=="<?php echo $searchCondition['tag'];?>"){
                    this.focus();
                }
            }); 
            break;  
    }
    var pagechange="<?php echo $pagechange;?>";
    if(pagechange==1){
        $("#a0")[0].focus();
    }
}

function listForm(name,value) {
    $("#f_"+name).attr("value",value);
    if(name=='type'){
        type=$("#f_type").attr("value");
        tag="全部";
        area="全部";
    }else{
        type='<?php echo $searchCondition['type'];?>';
        if(name=='tag'){
            tag=$("#f_tag").attr("value");
        }else{
            tag='<?php echo $searchCondition['tag'];?>';
        }    
        if(name=='area'){
            area=$("#f_area").attr("value");
        }else{
            area='<?php echo $searchCondition['area'];?>';
        }     
    }
    var mypost="sort=<?php echo $sort?>&type="+type+"&area="+area+"&tag="+tag+"&page=1&name="+name;
    location.href='/vod/index?'+mypost;
}

function goPage() {
	var lasttype = $('#typeul').find('.there').html();
    var page = parseInt($('#mypage').attr('value'));
    if($('#mypage').attr('value')=='') page=1;
    if(page > <?php echo $wiki_pager->getLastPage();?>) {
        page=1; 
    }    
    var mypost="sort=<?php echo $sort?>&type=<?php echo $searchCondition['type']?>&area=<?php echo $searchCondition['area']?>&tag=<?php echo $searchCondition['tag']?>&lasttype="+lasttype+"&page="+page+"&pagechange=1";
    location.href='/vod/index?'+mypost;    
}

function showPagePrior() {
	var lasttype = $('#typeul').find('.there').html();
    var page = <?php echo $page?>;
    if(page <= 1) {
        showTip("已经是第一页了！");
    }else{
        page=page-1; 
    }    
    var mypost="sort=<?php echo $sort?>&type=<?php echo $searchCondition['type']?>&area=<?php echo $searchCondition['area']?>&tag=<?php echo $searchCondition['tag']?>&lasttype="+lasttype+"&page="+page+"&pagechange=1";
    location.href='/vod/index?'+mypost;    
}

function showPageNext() {
	var lasttype = $('#typeul').find('.there').html();
    var page = <?php echo $page?>;
    <?php if($page<$wiki_pager->getLastPage()&&$wiki_pager->getLastPage()>0):?>
    page=page+1; 
    var mypost="sort=<?php echo $sort?>&type=<?php echo $searchCondition['type']?>&area=<?php echo $searchCondition['area']?>&tag=<?php echo $searchCondition['tag']?>&lasttype="+lasttype+"&page="+page+"&pagechange=1";
    location.href='/vod/index?'+mypost;
    <?php else:?>
    showTip("已经是最后一页了！");
    <?php endif;?>
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
}

function eventHandler(evt){
	var evtcode = evt.which ? evt.which : evt.code;
	switch (evtcode) {	
        case 48:
	    case 49:
        case 50:
        case 51:
        case 52:
        case 53:
        case 54:
        case 55:
        case 56:
        case 57:
            goPageByNumber(parseInt(evtcode)-48);
            break;
		case 112:   //"KEY_INFO"
        case 35:    //end键
			showHotLivePage();
			break;
		case 36:    //"HOME键"
        case 3864:  //"KEY_LIANXIANG"
        case 0x31:  //1
            var lasttype = $('#typeul').find('.there').html();
            var page = <?php echo $page?>;
            var mypost="sort=0&type=<?php echo $searchCondition['type']?>&area=<?php echo $searchCondition['area']?>&tag=<?php echo $searchCondition['tag']?>&lasttype="+lasttype+"&page="+page+"&pagechange=1";
            location.href='/vod/index?'+mypost; 
			break;
        case 0x32:  //2
            var lasttype = $('#typeul').find('.there').html();
            var page = <?php echo $page?>;
            var mypost="sort=4&type=<?php echo $searchCondition['type']?>&area=<?php echo $searchCondition['area']?>&tag=<?php echo $searchCondition['tag']?>&lasttype="+lasttype+"&page="+page+"&pagechange=1";
            location.href='/vod/index?'+mypost; 
			break;
		case 113:    //"KEY_MENU"
			showTip("KEY_MENU");
			break;	
        case 33:     //"Pg Up键"
        case 0x78:   //上页
            showPagePrior();
            evt.preventDefault();
            break;
        case 34:    //"Pg Down键"
        case 0x79:  //下页
            showPageNext();
            evt.preventDefault();
            break;
        case 0x72:  //退出
            showPlayPage();
            evt.preventDefault();
            break;
        case 0x30:  //0
            //getChannelsAndPost();
            //evt.preventDefault();
            break;
	}	
}
/*
 *以下代码实现按数字键跳转页面的功能
 */
var remeberKeyValue="", remeberTimer=-1;
function goPageByNumber(_str){
	if(remeberTimer!=-1){
		clearTimeout(remeberTimer);
	}
    remeberKeyValue += _str;
	if(remeberKeyValue.length<4&&parseInt(remeberKeyValue)!=0&&parseInt(remeberKeyValue)<=181){
        showOkTip(remeberKeyValue);
	}else{
	    remeberKeyValue=""; 
	}
	remeberTimer = setTimeout('remeberKeyValue=""; remeberTimer=-1', 5000);
}
function showOkTip(num) {
	$(".tipc").style("display","block");
    $("#tipInfo").html('<br/>是否跳转到第<font color="#ff0000">'+num+'</font>页<p><a href="#" onclick="jump('+num+')"><i>确认</i></a>&nbsp;|&nbsp;<a href="#" onclick="closeTip()"><i>取消</i></a>');
    $("#tipInfo").find("a")[0].focus();
}
function jump(page){
	var lasttype = $('#typeul').find('.there').html();
    if(page > <?php echo $wiki_pager->getLastPage();?>) {
        page=1; 
    }    
    var mypost="sort=<?php echo $sort?>&type=<?php echo $searchCondition['type']?>&area=<?php echo $searchCondition['area']?>&tag=<?php echo $searchCondition['tag']?>&lasttype="+lasttype+"&page="+page+"&pagechange=1";
    location.href='/vod/index?'+mypost;    
}    
function closeTip(){
	$(".tipc").style("display","none");
    $('#navul').find('a')[0].focus();
    remeberKeyValue=""; 
    remeberTimer=-1;
}
</script>