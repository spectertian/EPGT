<?php foreach($searchHot as $value):?>
<li><a href="<?php echo url_for('search/list?q=title:'.$value) ?>"><?php echo mb_strcut($value, 0, 12, 'utf-8');?></a></li>
<?php endforeach;?>