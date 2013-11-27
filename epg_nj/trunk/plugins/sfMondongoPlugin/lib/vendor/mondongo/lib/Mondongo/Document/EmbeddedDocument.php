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
 * The base class for documents embeddeds.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
abstract class EmbeddedDocument
{
    /**
     * Returns if the document is modified.
     *
     * @return bool Returns if the document is modified.
     */
    public function isModified()
    {
        if ($this->getFieldsModified()) {
            return true;
        }

        if (isset($this->data['embeddeds'])) {
            foreach ($this->data['embeddeds'] as $name => $embed) {
                if (null !== $embed) {
                    // one
                    if ($embed instanceof EmbeddedDocument) {
                        if ($embed->isModified()) {
                            return true;
                        }
                    // many
                    } else {
                        foreach ($embed as $e) {
                            if ($e->isModified()) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Returns the fields modified.
     *
     * @return array The fields modified.
     */
    public function getFieldsModified()
    {
        return $this->fieldsModified;
    }

    /**
     * Clear the fields modified.
     *
     * @return void
     */
    public function clearFieldsModified()
    {
        $this->fieldsModified = array();
    }

    /**
     * Revert the fields modified.
     *
     * @return void
     */
    public function revertFieldsModified()
    {
        foreach ($this->getFieldsModified() as $name => $value) {
            $this->data['fields'][$name] = $value;
        }
        $this->clearFieldsModified();
    }

    /**
     * Clear modified.
     *
     * @return void
     */
    public function clearModified()
    {
        $this->clearFieldsModified();

        if (isset($this->data['embeddeds'])) {
            foreach ($this->data['embeddeds'] as $embed) {
                if (null !== $embed) {
                    // one
                    if ($embed instanceof EmbeddedDocument) {
                        $embed->clearModified();
                    // many
                    } else {
                        foreach ($embed as $e) {
                            $e->clearModified();
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns the document data.
     *
     * @return array The document data.
     */
    public function getDocumentData()
    {
        return $this->data;
    }

    /**
     * Returns the data to Mongo.
     *
     * @return array The data to Mongo.
     */
    public function dataToMongo()
    {
        $data = array();

        // fields
        if (isset($this->data['fields'])) {
            $fields = array();
            foreach ($this->data['fields'] as $name => $value) {
                if (null !== $value) {
                    $fields[$name] = $value;
                }
            }
            $data = array_merge($data, $this->fieldsToMongo($fields));
        }

        // embeddeds
        if (isset($this->data['embeddeds'])) {
            foreach ($this->data['embeddeds'] as $name => $embed) {
                if (null !== $embed) {
                    // one
                    if ($embed instanceof EmbeddedDocument) {
                        $data[$name] = $embed->dataToMongo();
                    // many
                    } else {
                        foreach ($embed as $key => $e) {
                            $data[$name][$key] = $e->dataToMongo();
                        }
                    }
                }
            }
        }

        return $data;
    }

    /*
     * Events.
     */
    public function preInsert()
    {
    }

    public function postInsert()
    {
    }

    public function preUpdate()
    {
    }

    public function postUpdate()
    {
    }

    public function preSave()
    {
    }

    public function postSave()
    {
    }

    public function preDelete()
    {
    }

    public function postDelete()
    {
    }
}
