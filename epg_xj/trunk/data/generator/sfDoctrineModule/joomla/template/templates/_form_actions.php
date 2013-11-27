<div class="toolbar" id="toolbar">
    <table class="toolbar">
        <tr>
            <?php foreach (array('new', 'edit') as $action): ?>
                <?php if ('new' == $action): ?>
                [?php if ($form->isNew()): ?]
                <?php else: ?>
                [?php else: ?]
                <?php endif; ?>
                <?php foreach ($this->configuration->getValue($action.'.actions') as $name => $params): ?>
                <?php if (isset($params['component'])): ?>
                    [?php include_component('<?php echo $this->getModuleName() ?>', <?php echo $params['component'];?>) ?]
                <?php elseif (isset($params['partial'])): ?>
                    [?php include_partial('<?php echo $this->getModuleName() ?>/<?php echo $params['partial']; ?>') ?]
                <?php else: ?>
                    <?php if ('_delete' == $name): ?>
                      <?php
                      $html = <<<EOF
<td class="button">
[?php echo link_to("<span class=\"icon-32-delete\"></span>\n".__("{$params['label']}", array(), 'sf_admin'), \$helper->getUrlForAction('delete'), \$form->getObject(), array('method' => 'delete', 'confirm' => "{$params['confirm']}" ? __("{$params['confirm']}", array(), 'sf_admin') : "{$params['confirm']}")) ?]
</td>
EOF;
                      ?>
                      <?php echo $this->addCredentialCondition($html, $params)."\n" ?>
                    <?php elseif ('_list' == $name): ?>
                      <?php
                      $html = <<<EOF
<td class="button">
[?php echo link_to("<span class=\"icon-32-cancel\"></span>\n".__("{$params["label"]}", array(), "sf_admin"), \$helper->getUrlForAction("list")) ?]
</td>
EOF;
                      ?>
                      <?php echo $this->addCredentialCondition($html, $params)."\n" ?>
                    <?php elseif ('_save' == $name): ?>
                      <?php
                      $html = <<<EOF
<td class="button" id="toolbar-publish">
    <a href="#" onclick="javascript:submitform()" class="toolbar">
        <span class="icon-32-save" title="{$params['label']}"></span>
        [?php echo __('{$params['label']}', array(), 'sf_admin') ?]
    </a>
</td>
EOF;
                      ?>
                      <?php echo $this->addCredentialCondition($html, $params) ?>
                    <?php elseif ('_save_and_add' == $name): ?>
                      <?php echo $this->addCredentialCondition('[?php echo $helper->linkToSaveAndAdd($form->getObject(), '.$this->asPhp($params).') ?]', $params) ?>
                    <?php else: ?>
                    <?php $class_suffix = isset($params['class'])?$params['class']:$params['class_suffix']; ?>
                    <?php
                    $html = <<<EOF
<td class="button">
[?php echo link_to("<span class=\"icon-32-$class_suffix\"></span>\n".__("{$params["label"]}", array(), "sf_admin"), \$helper->getUrlForAction("{$params['action']}")) ?]
</td>
EOF;
                    ?>
                    <?php echo $this->addCredentialCondition($html, $params) ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            [?php endif; ?]
        </tr>
    </table>
</div>
