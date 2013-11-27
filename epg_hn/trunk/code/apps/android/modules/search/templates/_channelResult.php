<?php foreach($results as $channel) :?>
<div class="row">
    <a href="<?php echo url_for('channel/show?code='.$channel->getCode())?>"><p class="txt1"><?php echo $channel->getName()?></p></a>
</div>
<?php endforeach;?>