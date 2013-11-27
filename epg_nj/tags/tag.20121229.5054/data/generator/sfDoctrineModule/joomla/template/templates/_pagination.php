<div class="button2-right">
    <div class="start">
        <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=1">
            [?php echo __('First page', array(), 'sf_admin') ?]
        </a>
    </div>
</div>
<div class="button2-right">
    <div class="prev">
        <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getPreviousPage() ?]">
          [?php echo __('Previous page', array(), 'sf_admin') ?]
        </a>
    </div>
</div>
<div class="button2-left">
    <div class="page">
        [?php foreach ($pager->getLinks() as $page): ?]
            [?php if ($page == $pager->getPage()): ?]
            <span>[?php echo $page ?]</span>
            [?php else: ?]
              <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $page ?]">[?php echo $page ?]</a>
            [?php endif; ?]
        [?php endforeach; ?]
    </div>
</div>
<div class="button2-left">
    <div class="next">
        <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getNextPage() ?]">
            [?php echo __('Next page', array(), 'sf_admin') ?]
        </a>
    </div>
</div>
<div class="button2-left">
    <div class="end">
        <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getLastPage() ?]">
            [?php echo __('Last page', array(), 'sf_admin') ?]
        </a>
    </div>
</div>