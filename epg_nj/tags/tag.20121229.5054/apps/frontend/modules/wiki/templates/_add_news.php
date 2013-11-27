<div class="module added-recently">
    <h3>最新添加 <!--<small><a href="#">更多&raquo;</a></small>--></h3>
    <ul>
       <?php foreach($wikis as $wiki): ?>
      <li>
          <span class="cat">[<?php echo $model[$wiki->getModel()] ?>]</span> <span class="title"><a href="<?php echo url_for('wiki/show?slug=').$wiki->getSlug() ?>" target="_blank"><?php echo $wiki->getTitle() ?></a></span>
          <span class="time"><?php echo $wiki->getCreatedAt()->format('Y-m-d') ?></span>
      </li>
      <?php endforeach; ?>
<!--      <li> <span class="cat">[人物]</span> <span class="title"><a href="#">江湖一哥</a></span> <span class="time">2010-11-30</span> </li>
      <li> <span class="cat">[人物]</span> <span class="title"><a href="#">王刚</a></span> <span class="time">2010-11-30</span> </li>
      <li> <span class="cat">[影视]</span> <span class="title"><a href="#">铁梨花</a></span> <span class="time">2010-11-30</span> </li>
      <li> <span class="cat">[影视]</span> <span class="title"><a href="#">夏目奈奈</a></span> <span class="time">2010-11-30</span> </li>-->
    </ul>
</div>