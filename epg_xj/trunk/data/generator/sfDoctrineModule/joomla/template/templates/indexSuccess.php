[?php use_helper('I18N', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<div id="content-box">
    <div class="border">
        <div class="padding">
            <div id="toolbar-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                    [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]
                    <div class="header icon-48-addedit">[?php echo <?php echo $this->getI18NString('list.title') ?> ?]</div>
                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>

            <!--<div id="submenu-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                    <div class="submenu-box">
                        <div class="submenu-pad">
                            <ul id="submenu" class="configuration">
                                <li><a id="site" class="">前台</a></li>
                                <li><a id="system">系统</a></li>
                                <li><a id="server" class="active">服务器</a></li>
                            </ul>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>-->
            
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_header', array('pager' => $pager)) ?]
            [?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]
            <div id="element-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                        <?php if ($this->configuration->hasFilterForm()): ?>
                            [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'configuration' => $configuration)) ?]
                        <?php endif; ?>
                        
                    <?php if ($this->configuration->getValue('list.batch_actions')): ?>
                        <script type="text/javascript">
                        function submitform(action){
                            if (action) {
                                    document.adminForm.batch_action.value=action;
                            }
                            if (typeof document.adminForm.onsubmit == "function") {
                                    document.adminForm.onsubmit();
                            }
                            document.adminForm.submit();
                        }
                        </script>
                        <form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" name="adminForm" method="post">
                    <?php endif; ?>
                        [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?]
                    <?php if ($this->configuration->getValue('list.batch_actions')): ?>
                        [?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?]
                          <input type="hidden" name="[?php echo $form->getCSRFFieldName() ?]" value="[?php echo $form->getCSRFToken() ?]" />
                        [?php endif; ?]
                        <input type="hidden" name="batch_action" value="" />
                        </form>
                    <?php endif; ?>
                    
                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
</div>