<div id="content">
<div class="content_inner">
    <header>
        <h2 class="content"><?php echo $pageTitle;?></h2>
    </header>
    <?php foreach($queueStatus as $key => $status) {?>
    <h1><?php echo $key;?></h1>
    <p>maxqueue:<?php echo $status['maxqueue'];?></p>
    <p>getpos:<?php echo $status['getpos'];?></p>
    <p>unread:<?php echo $status['unread'];?></p>    
    <?php } ?>
    <div class="clear"></div>
</div>
</div>