<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
			<ul id="navul">
				<li><a href="/default/index" class="there">智能门户</a></li>
				<li><a href="/vod/index">影片库</a></li>
				<li><a href="/channel/index">一周节目</a></li>
				<li><a href="/user/cliplist">我的片单</a></li>
				<li><a href="/search">搜索</a></li>
			</ul>
		</div>
		
		<div class="liststy playnow">
			<div class="hlist">
				<ul class="clr" id="livelist">
                    <?php include_component('default','liveList');?>
				</ul>
			</div>
		</div>
		
		<div class="liststy youlike">
			<div class="hlist">
				<ul class="clr" id="recommend">
                    <?php 
                          $m=0;
                          foreach($wikis as $wiki):
                          //$wiki = $recommend->getWiki();
                    ?>                
					<li>
						<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>">
							<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" />
							<span><b><?php echo $wiki->getTitle();?></b></span>
						</a>
					</li>
            		<?php
                          $m++;
                          endforeach;
                    ?>
				</ul>
			</div>
		</div>
		
		<div class="liststy playnow commant">
			<div class="hlist">
				<ul id="theme">
                    <?php 
                          foreach($themes as $theme):
                    ?>                  
					<li>
						<a href="<?php echo url_for('theme/show?tid='.$theme->getId()) ?>">
							<img src="<?php echo  thumb_url($theme->getImg(), 220, 125);?>" alt="" />
							<span><b><?php echo mb_strcut($theme->getTitle(),0,24,'utf-8');?></b></span>
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
    function showIndexPage() {
        if($("#wapper").style("visibility") == "hidden"){
            $("#wapper").style("visibility","visible");
        }else{
            $("#wapper").style("visibility","hidden");
        }
    }
    function initPage() {	
    	publicInit();	
        playVideo();
        $("#navul").splotNav();
        //var recommendLength=$("#recommend").find("li").length-1;
        //var livelistLength=$("#livelist").find("li").length-1;
        //var themeLength=$("#theme").find("li").length-1;

        $("#recommend").scrollul($("#recommend"));  //推荐滚动
        //scrollSingle($("#recommend"),5);
        $("#recommend").scroll("b",4);                   //推荐文字滚动
        //scroll($("#recommend"),4);  //超过4个字符后滚动
        
        $("#livelist").scrollul($("#livelist"));      //直播滚动
        $("#theme").scrollul($("#theme"));            //专题滚动

        //$("#theme").scroll("b",8);                    //专题文字滚动
        /*
        var orecommend=document.getElementById("recommend");
        var oli=orecommend.getElementsByTagName("li");
        var onum=oli.length-1;
        $('#recommend').find("li").each().mouseover(function(){
        	go(orecommend,oli,onum);
        });
        
        
        var olivelist=document.getElementById("livelist");
        var liveli=olivelist.getElementsByTagName("li");
        var lith=liveli.length-1;
        $('#livelist').find("li").each().mouseover(function(){
        	go(olivelist,liveli,lith);
        });
        */
    }
</script>