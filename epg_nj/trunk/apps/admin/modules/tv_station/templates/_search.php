<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('tv_station_collection', array('action' => 'filter')) ?>" method="post">
<?php echo $form->renderHiddenFields() ?>
            过滤:
            <?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?>
            <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
              <?php include_partial('tv_station/filters_field', array(
                'name'       => $name,
                'attributes' => $field->getConfig('attributes', array()),
                'label'      => $field->getConfig('label'),
                'help'       => $field->getConfig('help'),
                'form'       => $form,
                'field'      => $field,
              )) ?>
            <?php endforeach; ?>
            <?php echo link_to(__('Reset', array(), 'sf_admin'), 'tv_station_collection', array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post')) ?>
            <input type="submit" value="<?php echo __('Filter', array(), 'sf_admin') ?>" />


