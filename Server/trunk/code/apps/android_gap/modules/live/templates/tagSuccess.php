<script language="javascript"> 
/*
    $("#standard img").lazyload({ 
        container: $("#standard"),
        placeholder:"/img/ccc.gif",
        loading:true,
        threshold:200,
        effectspeed:10,
        effect : "fadeIn"
    });
*/    
</script>
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
            
            <div class="content " data-role="page">
            	<div class="ll">
                	<h2 class="tab"><a href="<?php echo url_for("channel/index") ?>">频道列表</a><a href="<?php echo url_for("live/index") ?>" class="there">正在播放</a></h2>
                    <div class="clr">
                    	<div class="tvchoice listchoice">
                        	<ul>
                                <li <?php if($tag=='all'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=all&mode=".$mode)?>" class="all">全部</a></li>
                                <li <?php if($tag=='电视剧'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=电视剧&mode=".$mode)?>" class="tv">电视剧</a></li>
                                <li <?php if($tag=='电影'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=电影&mode=".$mode)?>" class="mv">电影</a></li>
                                <li <?php if($tag=='体育'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=体育&mode=".$mode)?>" class="sports">体育</a></li>
                                <li <?php if($tag=='娱乐'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=娱乐&mode=".$mode)?>" class="entertainment">娱乐</a></li>
                                <li <?php if($tag=='少儿'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=少儿&mode=".$mode)?>" class="children">少儿</a></li>
                                <li <?php if($tag=='科教'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=科教&mode=".$mode)?>" class="science">科教</a></li>
                                <li <?php if($tag=='财经'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=财经&mode=".$mode)?>" class="finance">财经</a></li>
                                <li <?php if($tag=='综合'):?>class="there"<?php endif;?>><a href="<?php echo url_for("live/index/?tag=综合&mode=".$mode)?>" class="total">综合</a></li>
                            </ul>
                        </div>



<?php if($mode=='list'):?>
                        <div class="daychoice">
                        	<h2 class="taber"><a href="<?php echo url_for("live/index?tag=".$tag."&mode=tile")?>" class="tile">平铺</a><a href="<?php echo url_for("live/index?tag=".$tag."&mode=list")?>" class="listss">列表</a></h2>                        
                            
                            <div class="timechoice tileslist" id="standard">
                            	<ul>
                                <?php foreach($wikiPlays as $wikiPlay) :?>
                                <?php $wiki = $wikiPlay->getWiki();?>    
                                		<li><a href="#"><?php echo mb_strcut($wiki->getChannel(), 0, 27, 'utf-8');?></a>
                                           <a href="javascript:void(0)" onclick="getWiki('<?php echo $wiki->getId()?>')"><?php echo mb_strcut($wiki->getTitle(), 0, 27, 'utf-8');?></a>
                                        <span><?php echo mb_strcut($wiki->getTags(), 0, 27, 'utf-8');?></span>
                                        </li>       
                                <?php endforeach;?>       
                            	</ul>
                            </div>
                        </div>

<?php else:?>    
                        <div class="daychoice">
                        	<h2 class="taber"><a href="<?php echo url_for("live/index?tag=".$tag."&mode=tile")?>" class="tiles">平铺</a><a href="<?php echo url_for("live/index?tag=".$tag."&mode=list")?>" class="lists">列表</a></h2>
                            
                            <div class="mvlist" id="standard">

                            	<ul class="clr">
                                <?php foreach($wikiPlays as $wikiPlay) :?>
                                <?php $wiki = $wikiPlay->getWiki();?>                                
                                    	<li><a href="javascript:void(0)" onclick="getWiki('<?php echo $wiki->getId()?>')"><img class="lazy" src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>" alt=""/><?php echo $wiki->getTitle()?></a></li>                          
                                <?php endforeach;?>    
                                </ul>   
                             
                            </div>
                        </div>                                         
<?php endif;?>                        
                    </div>
                </div>

                <div class="lr">
                <?php include_partial('channel/program', array('program_now'=>$program_now))?>
                </div>
            </div>
        </content>
<script type="text/javascript">
//加载选中节目信息
function getWiki(wikiid){
    
    $.ajax({
        url: '<?php echo url_for('live/show')?>',
        type: 'get',
        dataType: 'html',
        data: {'id': wikiid},
        beforeSend: function (XMLHttpRequest) {
            $('.lr').html("数据加载中...");
        },         
        success: function(html){
            $('.lr').html(html); 
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
            $('.lr').html(textStatus);
        }

    });
   
}
</script>        