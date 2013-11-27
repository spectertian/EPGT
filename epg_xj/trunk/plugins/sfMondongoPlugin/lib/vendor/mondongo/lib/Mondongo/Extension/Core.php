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

namespace Mondongo\Extension;

use Mondongo\Mondator\Definition\Container;
use Mondongo\Mondator\Definition\Definition;
use Mondongo\Mondator\Definition\Method;
use Mondongo\Mondator\Definition\Property;
use Mondongo\Mondator\Extension;
use Mondongo\Mondator\Output\Output;
use Mondongo\Type\Container as TypeContainer;
use Mondongo\Inflector;

/**
 * The Mondongo Core extension.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Core extends Extension
{
    /**
     * @inheritdoc
     */
    protected function setup()
    {
        $this->addOptions(array(
            'default_document_output'   => null,
            'default_repository_output' => null,
            'default_extensions'        => array(),
        ));
    }

    /**
     * @inheritdoc
     */
    protected function doProcess()
    {
        // is embedded
        $this->configClass['is_embedded'] = isset($this->configClass['is_embedded']) ? (bool) $this->configClass['is_embedded'] : false;

        // definitions and outputs
        $this->processInitDefinitionsAndOutputs();

        if (!$this->configClass['is_embedded']) {
            $this->processDocumentGetMondongoMethod();
            $this->processDocumentGetRepositoryMethod();
            $this->processInitConnectionName();
            $this->processInitCollectionName();
            $this->processInitIndexes();
        }

        // init
        $this->processInitFields();
        $this->processInitReferences();
        $this->processInitEmbeddeds();

        if (!$this->configClass['is_embedded']) {
            $this->processInitRelations();
        }

        $this->processInitExtensionsEvents();

        // default extensions
        foreach ($this->getOption('default_extensions') as $extension) {
            if ($this->configClass['is_embedded'] && isset($extension['not_with_embeddeds']) && $extension['not_with_embeddeds']) {
                continue;
            }
            $this->processExtensionsFromArray(array($extension));
        }

        // extensions
        if (isset($this->configClass['extensions'])) {
            $this->processExtensionsFromArray($this->configClass['extensions']);
        }

        // is_file
        $this->processIsFile();

        // parse fields
        $this->processParseFields();

        // check
        $this->checkFields();
        $this->checkReferences();
        $this->checkEmbeddeds();
        if (!$this->configClass['is_embedded']) {
            $this->checkRelations();
        }

        // document
        $this->processDocumentDataProperty();
        $this->processDocumentFieldsModifiedsProperty();

        $this->processDocumentSetDocumentDataMethod();
        $this->processDocumentFieldsToMongoMethod();

        $this->processDocumentFields();
        $this->processDocumentReferences();
        $this->processEmbeddedDocuments();
        if (!$this->configClass['is_embedded']) {
            $this->processDocumentRelations();
        }

        $this->processDocumentExtensionsEventsMethods();

        // repository
        if (!$this->configClass['is_embedded']) {
            $this->processRepositoryDocumentClassProperty();
            $this->processRepositoryConnectionNameProperty();
            $this->processRepositoryCollectionNameProperty();
            $this->processRepositoryIsFileProperty();
            $this->processRepositoryEnsureIndexesMethod();
        }
    }

    /*
     * Init Definitions and Outputs.
     */
    protected function processInitDefinitionsAndOutputs()
    {
        /*
         * Classes.
         */
        $classes['document'] = $this->class;
        if (false !== $pos = strrpos($classes['document'], '\\')) {
            $documentNamespace   = substr($classes['document'], 0, $pos);
            $documentClassName   = substr($classes['document'], $pos + 1);
            $repositoryNamespace = substr($documentNamespace, 0, strrpos($documentNamespace, '\\'));
            $classes['document_base']   = $documentNamespace.'\\Base\\'.$documentClassName;
            $classes['repository']      = $repositoryNamespace.'\\Repository\\'.$documentClassName;
            $classes['repository_base'] = $repositoryNamespace.'\\Repository\\Base\\'.$documentClassName;
        } else {
            $classes['document_base']   = 'Base'.$classes['document'];
            $classes['repository']      = $classes['document'].'Repository';
            $classes['repository_base'] = 'Base'.$classes['document'].'Repository';
        }

        /*
         * Definitions
         */

        // document
        $this->definitions['document'] = $definition = new Definition($classes['document']);
        $definition->setParentClass('\\'.$classes['document_base']);
        $definition->setDocComment(<<<EOF
/**
 * {$this->class} document.
 */
EOF
        );

        // document_base
        $this->definitions['document_base'] = $definition = new Definition($classes['document_base']);
        $definition->setIsAbstract(true);
        if ($this->configClass['is_embedded']) {
            $definition->setParentClass('\Mondongo\Document\EmbeddedDocument');
        } else {
            $definition->setParentClass('\Mondongo\Document\Document');
        }
        $definition->setDocComment(<<<EOF
/**
 * Base class of {$this->class} document.
 */
EOF
        );

        if (!$this->configClass['is_embedded']) {
            // repository
            $this->definitions['repository'] = $definition = new Definition($classes['repository']);
            $definition->setParentClass('\\'.$classes['repository_base']);
            $definition->setDocComment(<<<EOF
/**
 * Repository of {$this->class} document.
 */
EOF
            );

            // repository_base
            $this->definitions['repository_base'] = $definition = new Definition($classes['repository_base']);
            $definition->setIsAbstract(true);
            $definition->setParentClass('\\Mondongo\\Repository');
            $definition->setDocComment(<<<EOF
/**
 * Base class of repository of {$this->class} document.
 */
EOF
            );
        }

        /*
         * Outputs
         */

        // document
        $dir = $this->getOption('default_document_output');
        if (isset($this->configClass['document_output'])) {
            $dir = $this->configClass['document_output'];
        }
        if (!$dir) {
            throw new \RuntimeException(sprintf('The document of the class "%s" does not have output.', $this->class));
        }

        $this->outputs['document'] = new Output($dir);

        // document_base
        $this->outputs['document_base'] = new Output($this->outputs['document']->getDir().'/Base', true);

        // repository
        $dir = $this->getOption('default_repository_output');
        if (isset($this->configClass['repository_output'])) {
            $dir = $this->configClass['repository_output'];
        }
        if (!$dir) {
            throw new \RuntimeException(sprintf('The repository of the class "%s" does not have output.', $this->class));
        }

        $this->outputs['repository'] = new Output($dir);

        // repository_base
        $this->outputs['repository_base'] = new Output($this->outputs['repository']->getDir().'/Base', true);
    }

    /*
     * Document "getMondongo" method.
     */
    public function processDocumentGetMondongoMethod()
    {
        $method = new Method('public', 'getMondongo', '', <<<EOF
        return \Mondongo\Container::getForDocumentClass('{$this->definitions['document']->getClass()}');
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Returns the Mondongo of the document.
     *
     * @return Mondongo\Mondongo The Mondongo of the document.
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }

    /*
     * Document "getRepository" method.
     */
    public function processDocumentGetRepositoryMethod()
    {
        $method = new Method('public', 'getRepository', '', <<<EOF
        return \$this->getMondongo()->getRepository('{$this->definitions['document']->getClass()}');
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }

    /*
     * Connection name.
     */
    protected function processInitConnectionName()
    {
        if (!isset($this->configClass['connection'])) {
            $this->configClass['connection'] = null;
        }
    }

    /*
     * Collection name.
     */
    protected function processInitCollectionName()
    {
        if (!isset($this->configClass['collection'])) {
            $this->configClass['collection'] = str_replace('\\', '_', Inflector::underscore($this->class));
        }
    }

    /*
     * Init indexes.
     */
    protected function processInitIndexes()
    {
        if (!isset($this->configClass['indexes'])) {
            $this->configClass['indexes'] = array();
        }
    }

    /*
     * Init Fields.
     */
    protected function processInitFields()
    {
        if (!isset($this->configClass['fields'])) {
            $this->configClass['fields'] = array();
        }
    }

    /*
     * Init References.
     */
    protected function processInitReferences()
    {
        if (!isset($this->configClass['references'])) {
            $this->configClass['references'] = array();
        }
    }

    /*
     * Init Embeddeds.
     */
    protected function processInitEmbeddeds()
    {
        if (!isset($this->configClass['embeddeds'])) {
            $this->configClass['embeddeds'] = array();
        }
    }

    /*
     * Init relations.
     */
    protected function processInitRelations()
    {
        if (!isset($this->configClass['relations'])) {
            $this->configClass['relations'] = array();
        }
    }

    /*
     * Init extensions events.
     */
    protected function processInitExtensionsEvents()
    {
        $this->configClass['extensions_events'] = array(
            'preInsert'  => array(),
            'postInsert' => array(),
            'preUpdate'  => array(),
            'postUpdate' => array(),
            'preSave'    => array(),
            'postSave'   => array(),
            'preDelete'  => array(),
            'postDelete' => array(),
        );
    }

    /*
     * Is File.
     */
    protected function processIsFile()
    {
        if (isset($this->configClass['is_file'])) {
            if (!is_bool($this->configClass['is_file'])) {
                throw new \RuntimeException(sprintf('The "is_file" option of the class "%s" is not boolean.', $this->class));
            }
            $this->configClass['fields']['file'] = 'raw';
        } else {
            $this->configClass['is_file'] = false;
        }
    }

    /*
     * Parse Fields.
     */
    protected function processParseFields()
    {
        foreach ($this->configClass['fields'] as $name => &$field) {
            if (is_string($field)) {
                $field = array('type' => $field);
            }
        }
    }

    /*
     * Check.
     */
    protected function checkFields()
    {
        foreach ($this->configClass['fields'] as $name => $field) {
            if (!is_array($field)) {
                throw new \RuntimeException(sprintf('The field "%s" of the class "%s" is not a string or array.', $name, $this->class));
            }
            if (!isset($field['type'])) {
                throw new \RuntimeException(sprintf('The field "%s" of the class "%s" does not have type.', $name, $this->class));
            }
            if (!TypeContainer::hasType($field['type'])) {
                throw new \RuntimeException(sprintf('The type "%s" of the field "%s" of the class "%s" does not exists.', $field['type'], $name, $this->class));
            }
        }
    }

    protected function checkReferences()
    {
        foreach ($this->configClass['references'] as $name => $reference) {
            if (!isset($reference['class'])) {
                throw new \RuntimeException(sprintf('The reference "%s" of the class "%s" does not have class.', $name, $this->class));
            }
            if (!isset($reference['field'])) {
                throw new \RuntimeException(sprintf('The reference "%s" of the class "%s" does not have field.', $name, $this->class));
            }
            if (!isset($reference['type'])) {
                throw new \RuntimeException(sprintf('The reference "%s" of the class "%s" does not have type.', $name, $this->class));
            }
            if (!in_array($reference['type'], array('one', 'many'))) {
                throw new \RuntimeException(sprintf('The type "%s" of the reference "%s" of the class "%s" is not valid.', $reference['type'], $name, $this->class));
            }
        }
    }

    protected function checkEmbeddeds()
    {
        foreach ($this->configClass['embeddeds'] as $name => $embedded) {
            if (!isset($embedded['class'])) {
                throw new \RuntimeException(sprintf('The embedded "%s" of the class "%s" does not have class.', $name, $this->class));
            }
            if (!isset($embedded['type'])) {
                throw new \RuntimeException(sprintf('The embedded "%s" of the class "%s" does not have type.', $name, $this->class));
            }
            if (!in_array($embedded['type'], array('one', 'many'))) {
                throw new \RuntimeException(sprintf('The type "%s" of the embedded "%s" of the class "%s" is not valid.', $embedded['type'], $name, $this->class));
            }
        }
    }

    protected function checkRelations()
    {
        foreach ($this->configClass['relations'] as $name => $relation) {
            if (!isset($relation['class'])) {
                throw new \RuntimeException(sprintf('The relation "%s" of the class "%s" does not have class.', $name, $this->class));
            }
            if (!isset($relation['field'])) {
                throw new \RuntimeException(sprintf('The relation "%s" of the class "%s" does not have field.', $name, $this->class));
            }
            if (!isset($relation['type'])) {
                throw new \RuntimeException(sprintf('The relation "%s" of the class "%s" does not have type.', $name, $this->class));
            }
            if (!in_array($relation['type'], array('one', 'many'))) {
                throw new \RuntimeException(sprintf('The type "%s" of the relation "%s" of the class "%s" is not valid.', $relation['type'], $name, $this->class));
            }
        }
    }

    /*
     * Document "data" property.
     */
    protected function processDocumentDataProperty()
    {
        $data = array();

        // fields
        foreach ($this->configClass['fields'] as $name => $field) {
            $data['fields'][$name] = isset($field['default']) ? $field['default'] : null;
        }

        // references
        foreach ($this->configClass['references'] as $name => $reference) {
            $data['references'][$name] = null;
        }

        // embeddeds
        foreach ($this->configClass['embeddeds'] as $name => $embed) {
            $data['embeddeds'][$name] = null;
        }

        // relations
        if (!$this->configClass['is_embedded']) {
            foreach ($this->configClass['relations'] as $name => $relation) {
                $data['relations'][$name] = null;
            }
        }

        $property = new Property('protected', 'data', $data);

        $this->definitions['document_base']->addProperty($property);
    }

    /*
     * Document "fieldsModified" property.
     */
    protected function processDocumentFieldsModifiedsProperty()
    {
        $this->fieldsModified = array();
        foreach ($this->configClass['fields'] as $name => $field) {
            if (isset($field['default'])) {
                $this->fieldsModified[$name] = null;
            }
        }

        $property = new Property('protected', 'fieldsModified', $this->fieldsModified);

        $this->definitions['document_base']->addProperty($property);
    }

    /*
     * Document "setDocumentData" method.
     */
    protected function processDocumentSetDocumentDataMethod()
    {
        // _id
        $idCode = <<<EOF
        \$this->id = \$data['_id'];

EOF;
        if ($this->configClass['is_embedded']) {
            $idCode = '';
        }

        // fields
        $fieldsCode = '';
        foreach ($this->configClass['fields'] as $name => $field) {
            $typeCode = str_replace("\n", "\n            ", strtr(TypeContainer::getType($field['type'])->toPHPInString(), array(
                '%from%' => "\$data['$name']",
                '%to%'   => "\$this->data['fields']['$name']",
            )));

            $fieldsCode .= <<<EOF
        if (isset(\$data['$name'])) {
            $typeCode
        }

EOF;
        }

        // embeddeds
        $embeddedsCode = '';
        foreach ($this->configClass['embeddeds'] as $name => $embed) {
            $embedSetter = 'set'.Inflector::camelize($name);
            // one
            if ('one' == $embed['type']) {
                $embeddedsCode .= <<<EOF
        if (isset(\$data['$name'])) {
            \$embed = new \\{$embed['class']}();
            \$embed->setDocumentData(\$data['$name']);
            \$this->$embedSetter(\$embed);
        }

EOF;
            // many
            } elseif ('many' == $embed['type']) {
                $embeddedsCode .= <<<EOF
        if (isset(\$data['$name'])) {
            \$elements = array();
            foreach (\$data['$name'] as \$datum) {
                \$elements[] = \$element = new \\{$embed['class']}();
                \$element->setDocumentData(\$datum);
            }
            \$group = new \Mondongo\Group(\$elements);
            \$group->saveOriginalElements();
            \$this->$embedSetter(\$group);
        }

EOF;
            }
        }

        $resetFieldsModified = $this->fieldsModified ? "\$this->fieldsModified = array();" : '';

        $method = new Method('public', 'setDocumentData', '$data', <<<EOF
$idCode
$fieldsCode
$embeddedsCode
        $resetFieldsModified
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Set the data in the document (hydrate).
     *
     * @return void
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }

    /*
     * Document "fieldsToMongo" method.
     */
    public function processDocumentFieldsToMongoMethod()
    {
        $fieldsCode = '';
        foreach ($this->configClass['fields'] as $name => $field) {
            $typeCode = str_replace("\n", "\n            ", strtr(TypeContainer::getType($field['type'])->toMongoInString(), array(
                '%from%' => "\$fields['$name']",
                '%to%'   => "\$fields['$name']",
            )));

            $fieldsCode .= <<<EOF
        if (isset(\$fields['$name'])) {
            $typeCode
        }

EOF;
        }

        $method = new Method('public', 'fieldsToMongo', '$fields', <<<EOF
$fieldsCode

        return \$fields;
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Convert an array of fields with data to Mongo values.
     *
     * @param array \$fields An array of fields with data.
     *
     * @return array The fields with data in Mongo values.
     */
EOF
        );

        $this->definitions['document_base']->addMethod($method);
    }

    /*
     * Document fields.
     */
    protected function processDocumentFields()
    {
        foreach ($this->configClass['fields'] as $name => $field) {
            // set method
            $method = new Method(
                'public',
                'set'.Inflector::camelize($name),
                '$value',
                $this->getMethodCode(new \ReflectionMethod(__CLASS__, 'setField'), array('$_name_' => "'$name'"))
            );
            $method->setDocComment(<<<EOF
    /**
     * Set the "$name" field.
     *
     * @param mixed \$value The value.
     *
     * @return void
     */
EOF
            );

            $this->definitions['document_base']->addMethod($method);

            // get method
            $method = new Method(
                'public',
                'get'.Inflector::camelize($name),
                '',
                "        return \$this->data['fields']['$name'];"
            );
            $method->setDocComment(<<<EOF
    /**
     * Returns the "$name" field.
     *
     * @return mixed The $name field.
     */
EOF
            );

            $this->definitions['document_base']->addMethod($method);
        }
    }

    private function setField($value)
    {
        if (!array_key_exists($_name_, $this->fieldsModified)) {
            $this->fieldsModified[$_name_] = $this->data['fields'][$_name_];
        } elseif ($value === $this->fieldsModified[$_name_]) {
            unset($this->fieldsModified[$_name_]);
        }

        $this->data['fields'][$_name_] = $value;
    }

    /*
     * Document references.
     */
    protected function processDocumentReferences()
    {
        foreach ($this->configClass['references'] as $name => $reference) {
            $fieldSetter = 'set'.Inflector::camelize($reference['field']);
            $fieldGetter = 'get'.Inflector::camelize($reference['field']);

            $updateMethodName = 'update'.Inflector::camelize($name);

            /*
             * One
             */
            if ('one' == $reference['type']) {
                // setter
                $setterCode = <<<EOF
        if (!\$value instanceof \\{$reference['class']}) {
            throw new \InvalidArgumentException('The reference "$name" is not an instance of "{$reference['class']}".');
        }
        if (\$value->isNew()) {
            throw new \InvalidArgumentException('The reference "$name" is new.');
        }

        \$this->{$fieldSetter}(\$value->getId());
        \$this->data['references']['$name'] = \$value;
EOF;
                $setterDocComment = <<<EOF
    /**
     * Set the "$name" reference.
     *
     * @param {$reference['class']} \$value The value.
     *
     * @return void
     */
EOF;
                // getter
                $getterCode = <<<EOF
        if (null === \$this->data['references']['$name']) {
            \$value = \\Mondongo\Container::getForDocumentClass('{$reference['class']}')
                ->getRepository('{$reference['class']}')
                ->findOneById(\$this->$fieldGetter())
            ;
            if (!\$value) {
                throw new \RuntimeException('The reference "$name" does not exists');
            }
            \$this->data['references']['$name'] = \$value;
        }

        return \$this->data['references']['$name'];
EOF;
                $getterDocComment = <<<EOF
    /**
     * Returns the "$name" reference.
     *
     * @return {$reference['class']} The "$name" reference.
     */
EOF;
            /*
             * Many
             */
            } else {
                // setter
                $setterCode = <<<EOF
        if (!\$value instanceof \Mondongo\Group) {
            throw new \InvalidArgumentException('The reference "$name" is not an instance of Mondongo\Group.');
        }
        \$value->setChangeCallback(array(\$this, '$updateMethodName'));

        \$ids = array();
        foreach (\$value as \$document) {
            if (!\$document instanceof \\{$reference['class']}) {
                throw new \InvalidArgumentException('Some document in the reference "$name" is not an instance of "{$reference['class']}".');
            }
            if (\$document->isNew()) {
                throw new \InvalidArgumentException('Some document in the reference "$name" is new.');
            }
            \$ids[] = \$document->getId();
        }

        \$this->{$fieldSetter}(\$ids);
        \$this->data['references']['$name'] = \$value;
EOF;
                $setterDocComment = <<<EOF
    /**
     * Set the "$name" reference.
     *
     * @param Mondongo\Group \$value A Mondongo\Group instance.
     *
     * @return void
     */
EOF;
                // getter
                $getterCode = <<<EOF
        if (null === \$this->data['references']['$name']) {
            \$group = new \Mondongo\Group();

            if (\$ids = \$this->$fieldGetter()) {
                \$value = \\Mondongo\Container::getForDocumentClass('{$reference['class']}')->getRepository('{$reference['class']}')->find(array(
                    'query' => array('_id' => array('\$in' => \$ids)),
                ));
                if (!\$value || count(\$value) != count(\$ids)) {
                    throw new \RuntimeException('The reference "$name" does not exists');
                }

                \$group->setElements(\$value);
                \$group->setChangeCallback(array(\$this, '$updateMethodName'));
            }

            \$this->data['references']['$name'] = \$group;
        }

        return \$this->data['references']['$name'];
EOF;
                $getterDocComment = <<<EOF
    /**
     * Returns the "$name" reference.
     *
     * @return Mondongo\Group The "$name" reference.
     */
EOF;
            }

            // setter
            $method = new Method('public', 'set'.Inflector::camelize($name), '$value', $setterCode);
            $method->setDocComment($setterDocComment);
            $this->definitions['document_base']->addMethod($method);

            // getter
            $method = new Method('public', 'get'.Inflector::camelize($name), '', $getterCode);
            $method->setDocComment($setterDocComment);
            $this->definitions['document_base']->addMethod($method);

            // update
            if ('many' == $reference['type']) {
                $updateCode = <<<EOF
        if (null !== \$this->data['references']['$name']) {
            \$ids = array();
            foreach (\$this->data['references']['$name'] as \$document) {
                if (!\$document instanceof \\{$reference['class']}) {
                    throw new \RuntimeException('Some document of the "$name" reference is not an instance of "{$reference['class']}".');
                }
                if (\$document->isNew()) {
                    throw new \RuntimeException('Some document of the "$name" reference is new.');
                }
                \$ids[] = \$document->getId();
            }

            if (\$ids !== \$this->$fieldGetter()) {
                \$this->$fieldSetter(\$ids);
            }
        }
EOF;
                $updateDocComment = <<<EOF
    /**
     * Update the "$name" reference.
     *
     * @return void
     */
EOF;

                $method = new Method('public', $updateMethodName, '', $updateCode);
                $method->setDocComment($updateDocComment);
                $this->definitions['document_base']->addMethod($method);
            }
        }
    }

    /*
     * Document embeddeds.
     */
    protected function processEmbeddedDocuments()
    {
        foreach ($this->configClass['embeddeds'] as $name => $embed) {
            /*
             * one
             */
            if ('one' == $embed['type']) {
                // setter
                $setterCode = <<<EOF
        if (!\$value instanceof \\{$embed['class']}) {
            throw new \InvalidArgumentException('The embed "$name" is not an instance of "{$embed['class']}".');
        }

        \$this->data['embeddeds']['$name'] = \$value;
EOF;
                $setterDocComment = <<<EOF
    /**
     * Set the "$name" embed.
     *
     * @param {$embed['class']} \$value The embed.
     *
     * @return void
     */
EOF;
                // getter
                $getterCode = <<<EOF
        if (null === \$this->data['embeddeds']['$name']) {
            \$this->data['embeddeds']['$name'] = new \\{$embed['class']}();
        }

        return \$this->data['embeddeds']['$name'];
EOF;
                $getterDocComment = <<<EOF
    /**
     * Returns the "$name" embed..
     *
     * @return {$embed['class']} The "$name" embed.
     */
EOF;
            /*
             * many
             */
            } else {
                // setter
                $setterCode = <<<EOF
        if (!\$value instanceof \Mondongo\Group) {
            throw new \InvalidArgumentException('The embed "$name" is not an instance of "Mondongo\Group".');
        }

        \$this->data['embeddeds']['$name'] = \$value;
EOF;
                $setterDocComment = <<<EOF
    /**
     * Set the "$name" embed.
     *
     * @param Mondongo\Group \$value A Mondongo group.
     *
     * @return void
     */
EOF;
                // getter
                $getterCode = <<<EOF
        if (null === \$this->data['embeddeds']['$name']) {
            \$this->data['embeddeds']['$name'] = new \\Mondongo\Group();
        }

        return \$this->data['embeddeds']['$name'];
EOF;
                $getterDocComment = <<<EOF
    /**
     * Returns the "$name" embed.
     *
     * @return Mondongo\Group The "$name" embed.
     */
EOF;
            }

            // setter
            $method = new Method('public', 'set'.Inflector::camelize($name), '$value', $setterCode);
            $method->setDocComment($setterDocComment);
            $this->definitions['document_base']->addMethod($method);

            // getter
            $method = new Method('public', 'get'.Inflector::camelize($name), '', $getterCode);
            $method->setDocComment($getterDocComment);
            $this->definitions['document_base']->addMethod($method);
        }
    }

    /*
     * Document relations.
     */
    protected function processDocumentRelations()
    {
        foreach ($this->configClass['relations'] as $name => $relation) {
            /*
             * one
             */
            if ('one' == $relation['type']) {
                $getterCode = <<<EOF
        if (null === \$this->data['relations']['$name']) {
            \$this->data['relations']['$name'] = \Mondongo\Container::getForDocumentClass('{$relation['class']}')->getRepository('{$relation['class']}')->find(array(
                'query' => array('{$relation['field']}' => \$this->getId()),
                'one'   => true,
            ));
        }

        return \$this->data['relations']['$name'];
EOF;
                $getterDocComment = <<<EOF
    /**
     * Returns the "$name" relation.
     *
     * @return {$relation['class']} The "$name" relation.
     */
EOF;
            /*
             * many
             */
            } else {
                $getterCode = <<<EOF
        if (null === \$this->data['relations']['$name']) {
            \$this->data['relations']['$name'] = \Mondongo\Container::getForDocumentClass('{$relation['class']}')->getRepository('{$relation['class']}')->find(array(
                'query' => array('{$relation['field']}' => \$this->getId()),
            ));
        }

        return \$this->data['relations']['$name'];
EOF;
                $getterDocComment = <<<EOF
    /**
     * Returns the "$name" relation.
     *
     * @return array The "$name" relation.
     */
EOF;
            }

            $method = new Method('public', 'get'.Inflector::camelize($name), '', $getterCode);
            $method->setDocComment($getterDocComment);
            $this->definitions['document_base']->addMethod($method);
        }
    }

    /*
     * Document extensions events.
     */
    protected function processDocumentExtensionsEventsMethods()
    {
        foreach (array(
            'preInsert', 'postInsert',
            'preUpdate', 'postUpdate',
            'preSave'  , 'postSave'  ,
            'preDelete', 'postDelete',
        ) as $event) {
            $code = '';
            foreach ($this->configClass['extensions_events'][$event] as $method) {
                $code .= "        \$this->$method();\n";
            }
            $this->definitions['document_base']->addMethod(new Method('public', $event.'Extensions', '', $code));
        }
    }

    /*
     * Repository "documentClass" property.
     */
    protected function processRepositoryDocumentClassProperty()
    {
        $property = new Property('protected', 'documentClass', $this->definitions['document']->getClass());

        $this->definitions['repository_base']->addProperty($property);
    }

    /*
     * Repository "connectionName" property.
     */
    protected function processRepositoryConnectionNameProperty()
    {
        $property = new Property('protected', 'connectionName', $this->configClass['connection']);

        $this->definitions['repository_base']->addProperty($property);
    }

    /*
     * Repository "collectionName" property.
     */
    protected function processRepositoryCollectionNameProperty()
    {
        $property = new Property('protected', 'collectionName', $this->configClass['collection']);

        $this->definitions['repository_base']->addProperty($property);
    }

    /*
     * Repository "isFile" property.
     */
    protected function processRepositoryIsFileProperty()
    {
        $property = new Property('protected', 'isFile', $this->configClass['is_file']);

        $this->definitions['repository_base']->addProperty($property);
    }

    /*
     * Repository "ensureIndexes" method.
     */
    protected function processRepositoryEnsureIndexesMethod()
    {
        $code = '';
        foreach ($this->configClass['indexes'] as $key => $index) {
            $keys    = \Mondongo\Mondator\Dumper::exportArray($index['keys'], 12);
            $options = \Mondongo\Mondator\Dumper::exportArray(array_merge(isset($index['options']) ? $index['options'] : array(), array('safe' => true)), 12);

            $code .= <<<EOF
        \$this->getCollection()->ensureIndex($keys, $options);

EOF;
        }

        $method = new Method('public', 'ensureIndexes', '', $code);
        $method->setDocComment(<<<EOF
    /**
     * Ensure indexes.
     *
     * @return void
     */
EOF
        );

        $this->definitions['repository_base']->addMethod($method);
    }
}
