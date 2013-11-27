<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
			<ul id="navul">
				<li><a href="/vod/index"  class="there">影片库</a></li>
				<li><a href="/channel/index">一周节目</a></li>
				<li><a href="/user/cliplist">我的片单</a></li>
				<li><a href="/search">搜索</a></li>
                <li><a href="/default/index">智能门户</a></li>
			</ul>
		</div>
		<div class="moviemenu">
			<ul id="typeul" class="clr">
				<?php foreach ($types as $value):?>
				<li><a href="javascript:void(0);" <?php if ($value == $searchCondition['type']):?> class="there" <?php endif;?> onclick="listForm('type','<?php echo $value?>');"><?php echo $value;?></a></li>
				<?php endforeach;?>	
			</ul>
			<ul id="areaul" class="clr">
				<li><a href="javascript:void(0);" <?php if($sf_request->getParameter('area')=="全部" || $sf_request->getParameter('area')=="" ):?> class="there" <?php endif;?> onclick="listForm('area','全部');">全部</a></li>
				<?php foreach ($areas as $value):?>
				<li><a href="javascript:void(0);" <?php if ($value == $searchCondition['area']):?> class="there" <?php endif;?>  onclick="listForm('area','<?php echo $value?>');"><?php echo $value;?></a></li>
				<?php endforeach;?>	
			</ul>
			
			<ul id="tagul" class="clr">
                <!--显示tags-->
			</ul>
			
		</div>		
		<?php if ($wiki_pager->count() > 0):?>
		<div class="movielist">
            <form name="listform">
            <input type="hidden" name="type" id="f_type" value="<?php echo $searchCondition['type']?>">
            <input type="hidden" name="area" id="f_area" value="<?php echo $searchCondition['area']?>">
            <input type="hidden" name="tag" id="f_tag" value="<?php echo $searchCondition['tag']?>">
            <input type="hidden" name="page" id="f_page" value="<?php echo $page?>">
			<ul id="movieList" class="marqueet clr">
                <?php $i = 0; ?>
				<?php foreach ($wiki_pager as $wiki): ?>
				<li>
					<a id="<?php echo "a".$i;?>" href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" <?php if($i % 7 == 6) echo "class='last'" ?> <?php if($i % 7 == 0 and $page > 1) echo "class='first'" ?>>
						<img src="<?php echo thumb_url($wiki->getCover(), 105, 140)?>"/>
						<span><b><?php echo $wiki->getTitle();?></b></span>
					</a>
				</li>
                <?php $i++;?>
				<?php endforeach;?>
			</ul>
			</form>
		</div>
		<?php else:?>
        <div class="no-result">尚未有对应的片源</div>
		<?php endif;?>	
        <a href="#" class="back backs"></a><a href="#" class="next nexts"></a>	
		<div class="help">
			<ul>
				<li><img src="/img/fx.jpg" alt="选择"/>选择</li>
				<li><img src="/img/ok.jpg" alt="选择"/>进入</li>
				<li><img src="/img/cd.jpg" alt="选择"/>云媒体首页</li>
				<li><img src="/img/pd.jpg" alt="选择"/>帮助</li>
                <li><img src="/img/backs.png" alt=""/></li>
                <li><img src="/img/nexts.png" alt=""/></li>
                <li id="log"></li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">

$(function(){
	
});

function initPage() {	
	publicInit();
    showTags("电视剧",$("#f_tag").attr("value"));
    $("#navul").splotNav();
    ckeckPageEvt();	
    //$("#a0")[0].focus();
    //$(".marqueet").spanMarq({speed: 5, miniLength: 4});
    $(".marqueet").scroll("b",4);                   //wiki标题文字滚动
    playVideo();
}
function showTags(mytype,mytag){
    $.ajax({
        url: '<?php echo url_for('vod/showTags')?>',
        type: 'post',
        dataType: 'html',
        data: {type: mytype,mytag:mytag},
        success: function(data){
            var orgstring = '';
        	$('#tagul').find('a').each(function(){
            	var thishtml = $(this).html();
            	orgstring = orgstring+thishtml;
            })
            //alert(orgstring);
            var newdata = data;
            var newdatastring = newdata.replace(/<\/?.+?>/g,"").replace(/[\r\n]/g,"").replace(/[ ]/g,"").replace(/[	]/g,'');
			//alert(newdatastring);
            if(newdatastring != orgstring){
	            $('#tagul').html(data);
            }
            //$('#tagul').find('.there').focus();
        }
    });
} 
function listForm(name,value) {
    $("#"+name+"ul").find("a").each(function(){
        if($(this).html() == value)
            $(this).addClass("there");
        else 
            $(this).removeClass("there");
    });
    $("#f_"+name).attr("value",value);
    var data = {"type" : $("#f_type").attr("value"),
                "area" : $("#f_area").attr("value"),
                "tag" : $("#f_tag").attr("value"),
                "page" : $("#f_page").attr("value")};
    $("#f_type").change(function(){
		alert('changed');
    });
    showTags($("#f_type").attr("value"),$("#f_tag").attr("value"));           
    $.post("/vod/ajax",data, function(response){
        $("#movieList").html(response);
        //$("#a0")[0].focus();
        ckeckPageEvt();
        $(".marqueet").scroll("b",4);    
    });
    return false;
}

function showPagePrior() {   
    var page = parseInt($("#f_page").attr("value"));
    if(page <= 1) {
        showTip("已经是第一页了！");
        return false;
    }
    var data = {"type" : $("#f_type").attr("value"),
                "area" : $("#f_area").attr("value"),
                "tag" : $("#f_tag").attr("value"),                
                "page" : page - 1};
    $.post("/vod/ajax",data, function(response){
        $("#movieList").html(response);           
        $("#a0")[0].focus();            
        var page = parseInt($("#f_page").attr("value")); 
        $("#f_page").attr("value",page -1 ); 
        ckeckPageEvt();
        $(".marqueet").scroll("b",4);
    });
}

function showPageNext() {
    var page = parseInt($("#f_page").attr("value"));
    var data = {"type" : $("#f_type").attr("value"),
                "area" : $("#f_area").attr("value"),
                "tag" : $("#f_tag").attr("value"),                
                "page" : page + 1};
    $.post("/vod/ajax",data, function(response){
        if(response) {
            $("#movieList").html(response);           
            $("#a0")[0].focus();   
            var page = parseInt($("#f_page").attr("value"))+1;            
            $("#f_page").attr("value", page);
            ckeckPageEvt();
            $(".marqueet").scroll("b",4);
        } 
    });
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
</script>