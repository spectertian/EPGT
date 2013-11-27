<div class="main">
	<form method="get" class="src clr" action="<?php echo url_for('search/result')?>" name="searchForm">
    	<img src="<?php echo thumb_url($wiki->getCover(), 300, 450)?>" alt=""/>
    	<div>
        	<ul>
            	<li><h2>搜索</h2></li>
                <li><input type="text" class="search" name="key" id="key"/><a class="btn" onclick="searchForm.submit();">搜索</a></li>
                <li class="tag"><span>热词榜:</span>
                <?php if($tags = $wiki->getTags()):?>
                <?php foreach($tags as $tag) : $i++;?>
                <a href="<?php echo url_for('search/result?key='.$tag)?>"><?php echo $tag;?></a>
                <?php endforeach;?>
                <?php endif; ?>
                </li>
            </ul>
        </div>
    </form>
</div>