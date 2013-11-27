<div class="toolbar" id="toolbar">
    <table class="toolbar">
        <tr>
            <?php if ($listActions = $this->configuration->getValue('list.batch_actions')): ?>
            <?php foreach ((array) $listActions as $action => $params): ?>
            <?php $class_suffix = isset($params['class'])?$params['class']:$params['class_suffix']; ?>
            <?php
            $html = <<<EOF
<td class="button" id="toolbar-publish">
    <a href="#" onclick="javascript:submitform('$action')" class="toolbar">
        <span class="icon-32-$class_suffix" title="{$params['label']}"></span>
        [?php echo __('{$params['label']}', array(), 'sf_admin') ?]
    </a>
</td>
EOF;
            ?>
            <?php echo $this->addCredentialCondition($html, $params) ?>

            <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if ($actions = $this->configuration->getValue('list.actions')): ?>
                <?php foreach ($actions as $name => $params): ?>
                    <?php if ('_new' == $name): ?>
                    <?php
                    $html = <<<EOF
<td class="button" id="toolbar-new">
[?php echo link_to("<span class=\"icon-32-new\" title=\"New\"></span>\n".__("{$params["label"]}", array(), "sf_admin"), \$helper->getUrlForAction("new")) ?]
</td>
EOF;
                    ?>
                    <?php echo $this->addCredentialCondition($html, $params)."\n" ?>
                    <?php else: ?>
                    
                    <?php $class_suffix = isset($params['class'])?$params['class']:$params['class_suffix']; ?>
                    <?php
                    $html = <<<EOF
<td class="button">
[?php echo link_to("<span class=\"icon-32-$class_suffix\"></span>\n".__("{$params["label"]}", array(), "sf_admin"), \$helper->getUrlForAction("{$params['action']}")) ?]
</td>
EOF;
                    ?>
                    <?php echo $this->addCredentialCondition($html, $params)."\n" ?>
                    
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    </table>
</div>