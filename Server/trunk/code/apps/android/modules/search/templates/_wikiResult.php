<?php foreach($results->getResults() as $wiki) :?>
<div class="row">
    <a href="<?php echo url_for('wiki/show?id='.$wiki->getId())?>"><p class="txt1"><?php echo $wiki->getTitle()?></p></a>
</div>
<?php endforeach;?>