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
 * sfValidatorMondongoUnique.
 *
 * Based on sfValidatorDoctrineUnique.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfValidatorMondongoUnique extends sfValidatorBase
{
  /**
   * Options:
   *
   *   * model:              the model (required)
   *   * field:              the fields (or fields as array) (required)
   *   * include_identifier: if include the identifier
   *   * case_insensitive:   if check case insensitive (bool or array of fields)
   *   * throw_field_error:  if throw the error in field; false global error, true first field, or string a explicit field
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('model');
    $this->addRequiredOption('field');

    $this->addOption('include_identifier', true);
    $this->addOption('case_insensitive', false);

    $this->addOption('throw_field_error', false);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    $repository = sfContext::getInstance()->get('mondongo')->getRepository($this->getOption('model'));
    $fields     = (array) $this->getOption('field');

    // case_insensitive
    if (true === $this->getOption('case_insensitive'))
    {
      $caseInsensitive = $fields;
    }
    else if ($this->getOption('case_insensitive'))
    {
      $caseInsensitive = (array) $this->getOption('case_insensitive');

      foreach ($caseInsensitive as $field)
      {
        if (!in_array($field, $fields, true))
        {
          throw new InvalidArgumentException(sprintf('Teh field "%s" of the case_insensitive option does not exists in the field option.', $field));
        }
      }
    }
    else
    {
      $caseInsensitive = array();
    }

    // query
    $query = array();
    foreach ($fields as $formField => $field)
    {
      if (is_int($formField))
      {
        $formField = $field;
      }

      $value = $values[$formField];
      if (in_array($field, $caseInsensitive, true))
      {
        $value = new MongoRegex('/'.$value.'/i');
      }

      $query[$field] = $value;
    }

    // check
    if ($document = $repository->findOne(array('query' => $query)))
    {
      $error = true;

      if (!$this->getOption('include_identifier') &&  isset($values['_id']) && $document->getId()->__toString() == $values['_id'])
      {
        $error = false;
      }

      // error
      if ($error)
      {
        $error = new sfValidatorError($this, 'invalid');

        if ($formField = $this->getOption('throw_field_error'))
        {
          if (true === $formField)
          {
            foreach ($fields as $formField => $field)
            {
              if (is_int($formField))
              {
                $formField = $field;
              }

              break;
            }
          }
          else if (!in_array($formField, $fields))
          {
            throw new InvalidArgumentException(sprintf('The form field "%s" is not related.', $field));
          }

          $error = new sfValidatorErrorSchema($this, array($field => $error));
        }

        throw $error;
      }
    }

    return $values;
  }
}
