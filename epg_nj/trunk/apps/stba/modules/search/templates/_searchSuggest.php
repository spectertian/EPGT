<?php foreach($searchHot as $value):?>
<li><a href="javascript:void(0);" onclick="goSearch('<?php echo $value?>');"><?php echo mb_strcut($value, 0, 12, 'utf-8');?></a></li>
<?php endforeach;?>