<content class="clr">
        	<menu class="menu" data-role="menu">
            	<ul>
            		<li><a href="<?php echo url_for("/") ?>" class="index">首页</a></li>
                    <li class="there"><a href="<?php echo url_for("live/index") ?>" class="zb">直播</a></li>
                    <li><a href="#" class="jj">剧集</a></li>
                    <li><a href="#" class="lm">栏目</a></li>
                    <li><a href="#" class="sz">设置</a></li>		
                </ul>
            </menu>
            
            <div class="content clr" data-role="page">
            	<div class="mvinfo">
                	<h2><?php echo $wiki->getTitle()?></h2>
                    <?php
                    $screenshots=$wiki->getScreenshots();
                    $len=count($screenshots);
                    if($len>0)
                        $tupian=$screenshots[0];
                    else
                        $tupian='';    
                    ?>
                    <img src="<?php echo thumb_url($tupian, 320, 175)?>" alt="<?php echo $wiki->getTitle() ?>" class="covers"/>
                    <div class="choicecover">
                    	<ul class="clr">
                        <?php $i=0; foreach($screenshots as $value): if($i<6):?>
                        	<li><a href="#"><img src="<?php echo thumb_url($value)?>" alt=""/></a></li>
                        <?php endif; $i++; endforeach;?>
                        </ul>
                    </div>
                    
                    <div class="mvinfos">
                    	<h3 class="tabs"><a href="#" class="there">简介</a><a href="#">直播</a><a href="#">点播</a></h3>
                        <div class="mvh" id="wrapper_introduction">
                            
                            <?php include_partial('introduction', array('wiki' => $wiki,'model'=>$wiki->getModel()))?>
                            <ul class="mvjj zblist">
                            <?php include_partial('program_guide', array('programs' => $related_programs,'model'=>$wiki->getModel()))?>
                            </ul>
                            <ol class="mvjj jjlists">
                            <?php include_partial('dianbo', array('wiki' => $wiki,'model'=>$wiki->getModel()))?>
                            </ol>
                            
                        </div>
                    </div>
                    
                </div>
                
                <div class="mvcomment">
                	<h2><a href="#" class="there">新浪微博评论</a>|<a href="#">站内评论</a><a href="#" class="fbpl">去新浪微博发表评论</a></h2>
                    <div class="mclist" id="sinaweibo"><div id="standard"><ul id="weibo"><!--微博内容--></ul></div></div>
                </div>
                
                <div class="mvnnex">
                	<div class="tag">
                    	<h2>标签</h2>
                        <p>
                        <?php if($tags = $wiki->getTags()):?>
                        <?php foreach($tags as $tag) : $i++;?>
                        <a href="<?php echo url_for("live/index/?tag=".$tag) ?>"><?php echo $tag;?></a>
                        <?php endforeach;?>
                        <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="ilike">
                    	<h2>猜你喜欢</h2>
                        <?php include_component('wiki', 'related_movies')?>
                    </div>
                </div>
            </div>
        </content>
<script type="text/javascript">
var scroll_introduction;
$(document).ready(function() {
    weibo();  //加载微博数据
    loaded_show();
    scroll_introduction.refresh();
})
function loaded_show() {
	scroll_introduction = new iScroll('wrapper_introduction', { vScrollbar: false});
}
//加载微博数据
function weibo(){
    $.ajax({
        url: '<?php echo url_for('@wiki_weibo')?>',
        type: 'get',
        dataType: 'html',
        data: {'title': '<?php echo $wiki->getTitle()?>'},
        beforeSend: function (XMLHttpRequest) {
            //$('#standard').html('数据加载中...');   //不能用这个，否则就没有ul标签了
            $('#weibo').html("<li>数据加载中...</li>");
        },         
        success: function(html){
            //alert('执行后');
            //$('#standard').html(html);
            $('#weibo').html(html); 
            //以下方法也可以
            //$('#weibo').html('');
            //$('#weibo').append(html);
            scroll1.refresh();
            scroll2.refresh();            
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
            $('#standard').html(textStatus);
        }

    });
}

</script>        