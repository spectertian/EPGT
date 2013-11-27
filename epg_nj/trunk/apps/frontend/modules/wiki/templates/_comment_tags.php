<?php if (!empty($commentTags)) : $i = 1;?>
<div class="mod" id="tags">
  <div class="hd">
    <h3>印象</h3>
    <div class="more"><!--<a href="#">更多&gt;&gt;</a>--></div>
  </div>
  <div class="bd">
    <ul>
      <?php foreach($commentTags as $tag => $count) : $i++ ?>
      <?php if ($i > 10) break;?>
      <li><a href="#"><?php echo $tag?> <span class="rec">(<?php echo $count?>)</span></a></li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
<?php endif;?>