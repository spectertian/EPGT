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

namespace Mondongo\Mondator;

/**
 * Extension is the base class for extensions.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
abstract class Extension
{
    protected $options         = array();
    protected $requiredOptions = array();

    protected $container;
    protected $class;
    protected $configClass;

    protected $newConfigClasses;

    protected $definitions;
    protected $outputs;

    /**
     * Constructor.
     *
     * @param array $options An array of options.
     *
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->setUp();

        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }

        // required options
        if ($diff = array_diff($this->requiredOptions, array_keys($options))) {
            throw new \RuntimeException(sprintf('%s requires the options: "%s".', get_class($this), implode(', ', $diff)));
        }
    }

    /**
     * Set up the extension.
     *
     * @return void
     */
    protected function setUp()
    {
    }

    /**
     * Add an option.
     *
     * @param string $name         The option name.
     * @param mixed  $defaultValue The default value (optional, null by default).
     *
     * @return void
     */
    protected function addOption($name, $defaultValue = null)
    {
        $this->options[$name] = $defaultValue;
    }

    /**
     * Add options.
     *
     * @param array $options An array with options (name as key and default value as value).
     *
     * @return void
     */
    protected function addOptions(array $options)
    {
        foreach ($options as $name => $defaultValue) {
            $this->addOption($name, $defaultValue);
        }
    }

    /**
     * Add a required option.
     *
     * @param string $name The option name.
     *
     * @return void
     */
    protected function addRequiredOption($name)
    {
        $this->addOption($name);

        $this->requiredOptions[] = $name;
    }

    /**
     * Add required options.
     *
     * @param array $options An array with the name of the required option as value.
     *
     * @return void
     */
    protected function addRequiredOptions(array $options)
    {
        foreach ($options as $name) {
            $this->addRequiredOption($name);
        }
    }

    /**
     * Returns if exists an option.
     *
     * @param string $name The name.
     *
     * @return bool Returns true if the option exists, false otherwise.
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Set an option.
     *
     * @param string $name  The name.
     * @param mixed  $value The value.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the option does not exists.
     */
    public function setOption($name, $value)
    {
        if (!$this->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('The option "%s" does not exists.', $name));
        }

        $this->options[$name] = $value;
    }

    /**
     * Returns the options.
     *
     * @return array The options.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return an option.
     *
     * @param string $name The name.
     *
     * @return mixed The value of the option.
     *
     * @throws \InvalidArgumentException If the options does not exists.
     */
    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('The option "%s" does not exists.', $name));
        }

        return $this->options[$name];
    }

    /**
     * Process the extension.
     *
     * @param Mondongo\Mondator\Container $container        The container.
     * @param string                      $class            The class.
     * @param \ArrayObject                $configClass      The config class.
     * @param \ArrayObject                $newConfigClasses The new config classes.
     *
     * @return void
     */
    public function process(Container $container, $class, \ArrayObject $configClass, \ArrayObject $newConfigClasses)
    {
        $this->container   = $container;
        $this->class       = $class;
        $this->configClass = $configClass;

        $this->newConfigClasses = $newConfigClasses;

        $this->definitions = $container->getDefinitions();
        $this->outputs     = $container->getOutputs();

        $this->doProcess();

        $this->container   = null;
        $this->class       = null;
        $this->configClass = null;

        $this->newConfigClasses = null;

        $this->definitions = null;
        $this->outputs     = null;
    }

    abstract protected function doProcess();

    /*
     * Tools.
     */
    protected function processExtensionsFromArray(array $extensions)
    {
        foreach ($extensions as $key => $data) {
            if (!isset($data['class'])) {
                throw new \InvalidArgumentException(sprintf('The extension "%s" does not have class.', $data['class']));
            }
            $extension = new $data['class'](isset($data['options']) ? $data['options'] : array());
            $extension->process($this->container, $this->class, $this->configClass, $this->newConfigClasses);
        }
    }

    protected function getMethodCode(\ReflectionMethod $method, array $replace = array())
    {
        $lines = file($method->getFileName());

        $code = '';
        for ($i = $method->getStartLine(); $i <= $method->getEndLine(); $i++) {
            $code .= $lines[$i - 1];
        }

        $code = substr($code, strpos($code, '{') + 1);
        $code = substr($code, 0, strrpos($code, '}'));
        $code = '        '.trim($code);

        return strtr($code, $replace);
    }

    protected function getNamespace($class)
    {
        if (false !== $pos = strrpos($class, '\\')) {
            if ('\\' == $class[0]) {
                $class = substr($class, 1);
                $pos   = $pos - 1;
            }

            return substr($class, 0, $pos);
        }

        return null;
    }

    protected function getClassName($class)
    {
        if (false !== $pos = strrpos($class, '\\')) {
            return substr($class, $pos + 1);
        }

        return $class;
    }
}
