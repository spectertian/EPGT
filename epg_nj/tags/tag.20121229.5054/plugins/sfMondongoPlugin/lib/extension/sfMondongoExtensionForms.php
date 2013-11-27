<?php

/*
 * Copyright 2010 Pablo Díez Pascual <pablodip@gmail.com>
 *
 * This file is part of sfMondongoPlugin.
 *
 * sfMondongoPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sfMondongoPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with sfMondongoPlugin. If not, see <http://www.gnu.org/licenses/>.
 */

use Mondongo\Inflector;
use Mondongo\Mondator\Definition\Definition;
use Mondongo\Mondator\Definition\Method;
use Mondongo\Mondator\Extension;
use Mondongo\Mondator\Output\Output;

/**
 * sfMondongoExtensionForms.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoExtensionForms extends Extension
{
  protected function setUp()
  {
    $this->addRequiredOption('output');
  }

  protected function doProcess()
  {
    if ($this->configClass['is_embedded'])
    {
      return;
    }

    /*
     * Definitions.
     */
    $this->definitions['form'] = $definition = new Definition($this->class.'Form');
    $definition->setParentClass('Base'.$this->class.'Form');
    $definition->setDocComment(<<<EOF
/**
 * {$this->class} Form.
 */
EOF
    );

    $this->definitions['form_base'] = $definition = new Definition('Base'.$this->class.'Form');
    $definition->setParentClass('BaseFormMondongo');
    $definition->setDocComment(<<<EOF
/**
 * {$this->class} Base Form.
 */
EOF
    );

    /*
     * Outputs.
     */
    $this->outputs['form'] = new Output($this->getOption('output'));

    $this->outputs['form_base'] = new Output($this->getOption('output').'/Base', true);

    /*
     * Process.
     */
    $this->processSetupMethod();
    $this->processGetModelNameMethod();

    /*
     * Plugins.
     */
    if (isset($this->configClass['plugin_name']) && isset($this->configClass['plugin_dir']))
    {
      // definitions
      $this->definitions['form']->setParentClass('Plugin'.$this->class.'Form');

      $this->definitions['form_plugin'] = $definition = new Definition('Plugin'.$this->class.'Form');
      $definition->setParentClass('Base'.$this->class.'Form');
      $definition->setDocComment(<<<EOF
/**
 * {$this->class} Plugin Form.
 */
EOF
      );

      // outputs
      $this->outputs['form']->setDir($this->outputs['form']->getDir().'/'.$this->configClass['plugin_name']);

      $this->outputs['form_base']->setDir($this->outputs['form']->getDir().'/Base');

      $this->outputs['form_plugin'] = new Output($this->configClass['plugin_dir'].'/lib/model/form');
    }
  }

  /*
   * "setup" method.
   */
  protected function processSetupMethod()
  {
    /*
     * Widgets.
     */
    $widgets = array();
    // fields
    foreach ($this->configClass['fields'] as $name => $field)
    {
      $widgets[$name] = array(
        'class'   => $this->getWidgetClassForType($field['type']),
        'options' => $this->getWidgetOptionsForType($field['type']),
      );
    }
    // references
    foreach ($this->configClass['references'] as $name => $reference)
    {
      $options = array('model' => $reference['class'], 'multiple' => 'one' == $reference['type'] ? false : true);

      $widgets[$reference['field']] = array(
        'class'   => 'sfWidgetFormMondongoChoice',
        'options' => var_export($options, true),
      );
    }
    // code
    $widgetsCode = '';
    foreach ($widgets as $name => $widget)
    {
      $widgetsCode .= <<<EOF
            '$name' => new {$widget['class']}({$widget['options']}),

EOF;
    }

    /*
     * Validators.
     */
    $validators = array();
    // fields
    foreach ($this->configClass['fields'] as $name => $field)
    {
      $validators[$name] = array(
        'class'   => $this->getValidatorClassForType($field['type']),
        'options' => $this->getValidatorOptionsForType($field['type'], $field),
      );
    }
    // references
    foreach ($this->configClass['references'] as $name => $validator)
    {
      $options = array('model' => $reference['class'], 'multiple' => 'one' == $reference['type'] ? false : true);

      $validators[$reference['field']] = array(
        'class'   => 'sfValidatorMondongoChoice',
        'options' => var_export($options, true),
      );
    }
    // code
    $validatorsCode = '';
    foreach ($validators as $name => $validator)
    {
      $validatorsCode .= <<<EOF
            '$name' => new {$validator['class']}({$validator['options']}),

EOF;
    }

    /*
     * nameFormat
     */
    $nameFormat = Inflector::underscore($this->class);

    $code = <<<EOF
        \$this->setWidgets(array(
$widgetsCode
        ));

        \$this->setValidators(array(
$validatorsCode
        ));

        \$this->widgetSchema->setNameFormat('{$nameFormat}[%s]');
EOF
    ;

    $method = new Method('public', 'setup', '', $code);
    $method->setDocComment(<<<EOF
    /**
     * @see sfForm
     */
EOF
    );

    $this->definitions['form_base']->addMethod($method);
  }

  /*
   * "getModelName" method.
   */
  protected function processGetModelNameMethod()
  {
    $code = <<<EOF
        return '$this->class';
EOF;

    $method = new Method('public', 'getModelName', '', $code);
    $method->setDocComment(<<<EOF
    /**
     * @see sfMondongoForm
     */
EOF
    );

    $this->definitions['form_base']->addMethod($method);
  }

  /*
   * Widgets.
   */
  protected function getWidgetClassForType($type)
  {
    $class = 'sfWidgetFormInputText';

    switch ($type)
    {
      case 'bin_data':
        $class = 'sfWidgetFormInputFile';
        break;
      case 'boolean':
        $class = 'sfWidgetFormInputCheckbox';
        break;
      case 'date':
        $class = 'sfWidgetFormDateTime';
        break;
    }

    return $class;
  }

  protected function getWidgetOptionsForType($type)
  {
    $options    = array();
    $attributes = array();

    $options    = count($options) ? sprintf('array(%s)', implode(', ', $options)) : 'array()';
    $attributes = count($attributes) ? sprintf('array(%s)', implode(', ', $attributes)) : 'array()';

    return sprintf('%s, %s', $options, $attributes);
  }

  /**
   * Validators.
   */
  protected function getValidatorClassForType($type)
  {
    $class = 'sfValidatorString';

    switch ($type)
    {
      case 'bin_data':
        $class = 'sfValidatorFile';
        break;
      case 'boolean':
        $class = 'sfValidatorBoolean';
        break;
      case 'date':
        $class = 'sfValidatorDateTime';
        break;
      case 'float':
        $class = 'sfValidatorNumber';
        break;
      case 'integer':
        $class = 'sfValidatorInteger';
        break;
    }

    return $class;
  }

  protected function getValidatorOptionsForType($type, array $field)
  {
    $options    = array();
    $attributes = array();

    if (isset($field['required']) && !$field['required'])
    {
      $options['required'] = false;
    }

    $options    = count($options) ? sprintf('array(%s)', implode(', ', $options)) : 'array()';
    $attributes = count($attributes) ? sprintf('array(%s)', implode(', ', $attributes)) : 'array()';

    return sprintf('%s, %s', $options, $attributes);
  }
}
