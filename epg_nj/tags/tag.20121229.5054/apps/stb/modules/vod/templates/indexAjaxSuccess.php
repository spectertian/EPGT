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
				<li><a href="javascript:void(0);" <?php if ($value == $searchCondition['type']):?> class="there" <?php endif;?> onclick="showTags('<?php echo $value;?>',$('#f_tag').attr('value'));listForm('type','<?php echo $value?>',1);"><?php echo $value;?></a></li>
				<?php endforeach;?>	
			</ul>
			<ul id="areaul" class="clr">
				<li><a href="javascript:void(0);" <?php if($sf_request->getParameter('area')=="全部" || $sf_request->getParameter('area')=="" ):?> class="there" <?php endif;?> onclick="listForm('area','全部',1);">全部</a></li>
				<?php foreach ($areas as $value):?>
				<li><a href="javascript:void(0);" <?php if ($value == $searchCondition['area']):?> class="there" <?php endif;?>  onclick="listForm('area','<?php echo $value?>',1);"><?php echo $value;?></a></li>
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
                <li><img src="/img/backs.png" alt=""/><img src="/img/nexts.png" alt=""/>翻页</li>
                <li id="log"></li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">

function initPage() {	
	publicInit();
    showTags("电视剧",$("#f_tag").attr("value"));
    $("#navul").splotNav();
    ckeckPageEvt();	
    $(".marqueet").scroll("b",4);                   //wiki标题文字滚动
    playVideo();
}
function showTags(mytype,mytag){
    $("#f_tag").attr("value","全部");
    $.ajax({
        url: '<?php echo url_for('vod/showTags')?>',
        type: 'post',
        dataType: 'html',
        data: {type: mytype,mytag:mytag},
        success: function(data){
	       $('#tagul').html(data);
           $('#tagul').find("a").eq(0).addClass("there");
        }
    });
} 
function listForm(name,value) {
    var pageTop=arguments[2]?arguments[2]:0;
    if(pageTop==1){
        $("#f_page").attr("value",1 ); //如果第三个参数为1，则置当前页数为1
    }  
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
    $.ajaxSetup ({
            cache: false //关闭AJAX相应的缓存
        });                
    $.post("/vod/ajax",data, function(response){
        $("#movieList").html(response);
        //$("#a0")[0].focus();
        ckeckPageEvt();
        $(".marqueet").scroll("b",4);    
    });
    return false;
}

function showPagePrior() {   
    var pagenum = parseInt($("#f_page").attr("value"));
    if(pagenum <= 1) {
        showTip("已经是第一页了！");
        return false;
    }
    pagenum=pagenum - 1;
    var data = {"type" : $("#f_type").attr("value"),
                "area" : $("#f_area").attr("value"),
                "tag" : $("#f_tag").attr("value"),                
                "page" :pagenum };
    $.ajaxSetup ({
            cache: false //关闭AJAX相应的缓存
        });    
    $.post("/vod/ajax",data, function(response){
        $("#movieList").html(response);           
        $("#a0")[0].focus();
        $("#f_page").attr("value",pagenum ); 
        ckeckPageEvt();
        $(".marqueet").scroll("b",4);
    });
    return false;
}

function showPageNext() {
    var pagenum = parseInt($("#f_page").attr("value"));
    pagenum=pagenum + 1;
    var data = {"type" : $("#f_type").attr("value"),
                "area" : $("#f_area").attr("value"),
                "tag" : $("#f_tag").attr("value"),                
                "page" : pagenum};
     
    $.ajaxSetup ({
            cache: false //关闭AJAX相应的缓存
        });   
    /*     
    $.post("/vod/ajax",data, function(response){
        if(response) {
            $("#movieList").html(response);           
            $("#a0")[0].focus();            
            $("#f_page").attr("value", pagenum);
            ckeckPageEvt();
            $(".marqueet").scroll("b",4);
        } 
    });
    */
    $.ajax({
        url: "/vod/ajax",
        type: "post",
        data: data,
        dataType: "html",
        cache:false,
        ifModified :true,
        beforeSend :function(xmlHttp){
            xmlHttp.setRequestHeader("If-Modified-Since","0");
            xmlHttp.setRequestHeader("Cache-Control","no-cache");        
        },        
        success: function(data){
            $("#movieList").html(data);           
            $("#a0")[0].focus();            
            $("#f_page").attr("value", pagenum);
            ckeckPageEvt();
            $(".marqueet").scroll("b",4);
        }
    });

    /*
    //这种方法还是会导致电视死机
    var mypost="type="+$("#f_type").attr("value")+"&area="+$("#f_area").attr("value")+"&tag="+$("#f_tag").attr("value")+"&page="+pagenum;
    var data=ajax_get("/vod/ajax?"+mypost);
    //var data=ajax_post("/vod/ajax",mypost);  //这种不能翻页
    if(data) {
        $("#movieList").html(data);           
        $("#a0")[0].focus();            
        $("#f_page").attr("value", pagenum);
        ckeckPageEvt();
        $(".marqueet").scroll("b",4);
    }
    */
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