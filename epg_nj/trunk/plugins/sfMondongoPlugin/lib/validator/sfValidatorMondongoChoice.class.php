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

/**
 * sfValidatorMondongoChoice.
 *
 * Based on sfValidatorDoctrineChoice.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfValidatorMondongoChoice extends sfValidatorBase
{
  /**
   * Options:
   *
   *   * model:    the model (required)
   *   * field:    the field for choice (_id by default)
   *   * query:    the query for check (empty array by default)
   *   * multiple: if it's a choice multiple (false by default)
   *   * min:      the min of documnents to select (null by default)
   *   * max:      the max of document to select (null by default)
   *
   *  Messages:
   *
   *   * min: the message when the choices are less that the min option
   *   * max: the message when the choices are more that the max option
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('field', '_id');
    $this->addOption('query', array());
    $this->addOption('multiple', false);
    $this->addOption('min');
    $this->addOption('max');

    $this->addMessage('min', 'At least %min% values must be selected (%count% values selected).');
    $this->addMessage('max', 'At most %max% values must be selected (%count% values selected).');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $repository = sfContext::getInstance()->get('mondongo')->getRepository($this->getOption('model'));

    $field = $this->getOption('field');
    $query = $this->getOption('query');

    if ($this->getOption('multiple'))
    {
      if (!is_array($value))
      {
        $value = array($value);
      }

      if (isset($value[0]) && !$value[0])
      {
        unset($value[0]);
      }

      $count = count($value);

      if ($this->hasOption('min') && $count < $this->getOption('min'))
      {
        throw new sfValidatorError($this, 'min', array('count' => $count, 'min' => $this->getOption('min')));
      }

      if ($this->hasOption('max') && $count > $this->getOption('max'))
      {
        throw new sfValidatorError($this, 'max', array('count' => $count, 'max' => $this->getOption('max')));
      }

      $queryValue = array();
      foreach ($value as $v)
      {
        $queryValue[] = new MongoId($v);
      }

      $query[$field] = array('$in' => $queryValue);

      if ($repository->count($query) != $count)
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }
    else
    {
      $query[$field] = new MongoId($value);

      if (!$repository->count($query))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    return $value;
  }
}
