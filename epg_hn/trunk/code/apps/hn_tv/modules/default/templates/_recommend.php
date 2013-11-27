<div class="list">
<?php foreach($recommends as $recommend):?>
    <?php $wiki = $recommend->getWiki() ?>
    <?php if(!empty($wiki)):?>
    <h3><a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>"><?php echo mb_strcut($wiki->getTitle(), 0, 12, 'utf-8');?></a></h3>
    <?php endif;?>
<?php endforeach;?>
</div>