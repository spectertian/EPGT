<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php sfContext::getInstance()->getConfiguration()->loadHelpers('LurlFor');?>
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