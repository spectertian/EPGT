<div class="wapper" id="wapper">
	<div class="bg">
		<span class="week" title="按一返回智能门户"></span>
		
		<h1 class="titbg" id="wikititle"><?php echo $wikiTop['title']?>--<span><?php echo $wikiTop['wiki']['title']?></span></h1>
		
		<div class="intro clr">
			<ul id="wikiinfo">
				<li>主演：<?php echo $wikiTop['wiki']['actors']?></li>
				<li>国家/地区：<?php echo $wikiTop['wiki']['area']?></li>
				<li>类型：<?php echo $wikiTop['wiki']['type']?></li>
				<li>年代：<?php echo $wikiTop['wiki']['playdate']?></li>
				<li>编辑推荐：<?php echo $wikiTop['remark']?></li>
			</ul>
			
			<ol>
				<li><img src="<?php echo $wikiTop['wiki']['screens'][0]?>"  id="wikiscreen1"/></li>
				<li><img src="<?php echo $wikiTop['wiki']['screens'][1]?>"  id="wikiscreen2"/></li>
			</ol>
		</div>
		
		<div class="movelist">
			<span class="next" title="下一个"></span>
			<span class="back" title="上一个"></span>
			<div class="hidelist">
				<ul class="shows clr" id="shows">
                    <?php
                         $i=0; 
                         foreach($wikis as $wiki):
                             $actors=mb_strimwidth($wiki['wiki']['actors'],0,51,'...','utf-8');
                             $remark=mb_strimwidth($wiki['remark'],0,90,'...','utf-8');
                             
                    ?>
					<li>
						<a  id="<?php echo "a".$i;?>" href="<?php echo url_for('wiki/show?slug='.$wiki['wiki']['slug']) ?>" onmouseover="showWiki('<?php echo $remark?>','<?php echo $wiki['title']?>','<?php echo $wiki['wiki']['title']?>','<?php echo $wiki['wiki']['area']?>','<?php echo $wiki['wiki']['type']?>','<?php echo $actors?>','<?php echo $wiki['wiki']['playdate']?>','<?php echo $wiki['wiki']['screens'][0]?>','<?php echo $wiki['wiki']['screens'][1]?>')">
							<img src="<?php echo  thumb_url($wiki['wiki']['cover'], 120, 165);?>" />
							<span><b><?php echo $wiki['wiki']['title'];?></b></span>
						</a>
					</li>
                    <?php
                         $i++;
                         endforeach;
                    ?>
				</ul>
			</div>
		</div>
		
		<span class="movelisticon"></span>
		
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
        //$(".shows").find("a").get(0).focus();
    	//$("#shows").scrollul($("#shows"));  //推荐滚动
        $("#shows").animateNav({speed: 10, step: 70, width: 135});
        $("#shows").scroll("b",4);       //推荐文字滚动
    }
    function showWiki(remark,themetitle,title,area,type,actors,playdate,screen1,screen2){
        $("#wikititle").html(themetitle+"--<span>"+title+"</span>");
        var wiki=$("#wikiinfo");
        wiki.find('li').eq(0).html("主演："+actors);
        wiki.find('li').eq(1).html("国家/地区："+area);
        wiki.find('li').eq(2).html("类型："+type);
        wiki.find('li').eq(3).html("年代："+playdate);
        wiki.find('li').eq(4).html("编辑推荐："+remark);       
        $("#wikiscreen1").attr('src', screen1);
        $("#wikiscreen2").attr('src', screen2);
        /*
        document.getElementById("wikititle").innerHTML=themetitle+"--<span>"+title+"</span>";
    	  var wiki = document.getElementById("wikiinfo");
        wiki.getElementsByTagName("li")[0].innerHTML="主演："+actors;
        wiki.getElementsByTagName("li")[1].innerHTML="国家/地区："+area;
        wiki.getElementsByTagName("li")[2].innerHTML="类型："+type;
        wiki.getElementsByTagName("li")[3].innerHTML="年代："+playdate;
        wiki.getElementsByTagName("li")[4].innerHTML="编辑推荐："+remark;
        document.getElementById("wikiscreen1").src=screen1;
        document.getElementById("wikiscreen2").src=screen2;
        */
    }
</script>