<?php

/*
 * Copyright 2010 Pablo Díez Pascual <pablodip@gmail.com>
 *
 * This file is part of Mondongo.
 *
 * Mondongo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Mondongo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Mondongo. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Mondongo\Document;

/**
 * The base class for documents.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
abstract class Document extends EmbeddedDocument
{
    protected $id;

    /**
     * Set the document \MongoId.
     *
     * @param \MongoId $id The \MongoId object.
     *
     * @return void
     */
    public function setId(\MongoId $id)
    {
        $this->id = $id;
    }

    /**
     * Returns the \MongoId of document.
     *
     * @return \MongoId|null The \MongoId of document if exists, null otherwise.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns if the document is new.
     *
     * @return bool Returns if the document is new.
     */
    public function isNew()
    {
        return null === $this->id;
    }

    /**
     * Save the document.
     *
     * @return void
     */
    public function save()
    {
        $this->getRepository()->save($this);
    }

    /**
     * Delete the document.
     *
     * @return void
     */
    public function delete()
    {
        $this->getRepository()->delete($this);
    }

    /**
     * Returns the query for save.
     *
     * @return array Returns the query for save.
     */
    public function getQueryForSave()
    {
        $query = array();

        return $this->queryDocument($query, $this);
    }

    public function queryDocument($query, $document, array $name = null)
    {
        $data = $document->getDocumentData();

        // fields
        if ($fieldsModified = $document->getFieldsModified()) {
            $fields = array();
            foreach (array_keys($fieldsModified) as $field) {
                if (null !== $value = $data['fields'][$field]) {
                    $fields[$field] = $value;
                }
            }

            if ($fields) {
                $fields = $document->fieldsToMongo($fields);
            }

            foreach (array_keys($fieldsModified) as $field) {
                // insert
                if ($this->isNew()) {
                    // base
                    if (null === $name) {
                        $query[$field] = $fields[$field];
                    // embed
                    } else {
                        $q =& $query;
                        foreach ($name as $n) {
                            if (!isset($q[$n])) {
                                $q[$n] = array();
                            }
                            $q =& $q[$n];
                        }
                        $q[$field] = $fields[$field];
                    }
                // update
                } else {
                    $fieldName = (null !== $name ? implode('.', $name).'.' : '').$field;

                    // set
                    if (array_key_exists($field, $fields)) {
                        $query['$set'][$fieldName] = $fields[$field];
                    // unset
                    } else {
                        $query['$unset'][$fieldName] = 1;
                    }
                }
            }
        }

        // embeddeds
        if (isset($data['embeddeds'])) {
            foreach ($this->data['embeddeds'] as $embedName => $embed) {
                if (null !== $embed) {
                    $embedName = null !== $name ? array_merge($name, array($embedName)) : array($embedName);

                    // one
                    if ($embed instanceof EmbeddedDocument) {
                        $query = $this->queryDocument($query, $embed, $embedName);
                    // many
                    } else {
                        $elements = $embed->getElements();

                        // insert
                        if ($this->isNew()) {
                            foreach ($elements as $key => $element) {
                                $query = $this->queryDocument($query, $element, array_merge($embedName, array($key)));
                            }
                        // update
                        } else {
                            $originalElements = $embed->getOriginalElements();

                            // insert
                            foreach ($elements as $key => $element) {
                                if (!isset($originalElements[$key]) || spl_object_hash($element) != spl_object_hash($originalElements[$key])) {
                                    $query['$pushAll'][implode('.', $embedName)][] = $element->dataToMongo();
                                // update
                                } else {
                                    $query = $this->queryDocument($query, $element, array_merge($embedName, array($key)));
                                }
                            }

                            // delete
                            foreach ($originalElements as $key => $element) {
                                if (!isset($elements[$key]) || spl_object_hash($element) != spl_object_hash($elements[$key])) {
                                    $query['$pullAll'][implode('.', $embedName)][] = $element->dataToMongo();
                                }
                            }
                        }
                    }
                }
            }
        }

        return $query;
    }
}
