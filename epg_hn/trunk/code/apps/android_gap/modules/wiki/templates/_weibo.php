<?php if (0 < count($weiboData) && (!isset($weiboData['error_code']))) :?>
<?php foreach($weiboData as $weibo) :?>                        
<li><img src='<?php echo $weibo['user']['profile_image_url']?>' alt='' /><p><a href='#'><span><?php echo date('Y-m-d H:i:s', strtotime($weibo['created_at']))?></span><strong><?php echo $weibo['user']['name']?></strong></a><?php echo $weibo['text']?></p></li>
<?php endforeach?>
<?php else: ?>
<li>暂时没有数据..</li>
<?php endif;?>                            