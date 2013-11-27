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
 * Mondator.
 *
 * @package Mondongo
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class Mondator
{
    protected $configClasses = array();

    protected $extensions = array();

    protected $outputs = array();

    /**
     * Set a config class.
     *
     * @param string $class       The class.
     * @param array  $configClass The config class.
     *
     * @return void
     */
    public function setConfigClass($class, array $configClass)
    {
        $this->configClasses[$class] = $configClass;
    }

    /**
     * Set the config classes.
     *
     * @param array $configClasses An array of config classes (class as key and config class as value).
     *
     * @return void
     */
    public function setConfigClasses(array $configClasses)
    {
        $this->configClasses = array();
        foreach ($configClasses as $class => $configClass) {
            $this->setConfigClass($class, $configClass);
        }
    }

    /**
     * Returns if a config class exists.
     *
     * @param string $class The class.
     *
     * @return bool Returns if the config class exists.
     */
    public function hasConfigClass($class)
    {
        return array_key_exists($class, $this->configClasses);
    }

    /**
     * Returns the config classes.
     *
     * @return array The config classes.
     */
    public function getConfigClasses()
    {
        return $this->configClasses;
    }

    /**
     * Returns a config class.
     *
     * @param string $class The class.
     *
     * @return array The config class.
     *
     * @throws \InvalidArgumentException If the config class does not exists.
     */
    public function getConfigClass($class)
    {
        if (!$this->hasConfigClass($class)) {
            throw new \InvalidArgumentException(sprintf('The config class "%s" does not exists.', $class));
        }

        return $this->configClasses[$class];
    }

    /**
     * Add a extension.
     *
     * @param Mondongo\Mondator\Extension $extension The extension.
     *
     * @return void
     */
    public function addExtension(Extension $extension)
    {
        $this->extensions[] = $extension;
    }

    /**
     * Set the extensions.
     *
     * @param array $extensions An array of extensions.
     *
     * @return void
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = array();
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }

    /**
     * Returns the extensions.
     *
     * @return array The extensions.
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Generate the containers.
     *
     * @return array The containers.
     */
    public function generateContainers()
    {
        $containers = array();

        // configClasses
        $configClasses = new \ArrayObject();
        foreach ($this->getConfigClasses() as $class => $configClass) {
            $configClasses[$class] = new \ArrayObject($configClass);
        }

        // process
        $this->processConfigClasses($containers, $configClasses);

        return $containers;
    }

    protected function processConfigClasses(&$containers, \ArrayObject $configClasses)
    {
        foreach ($configClasses as $class => $configClass) {
            if (isset($containers[$class])) {
                throw new \RuntimeException(sprintf('The class "%s" has several config class.', $class));
            }

            $containers[$class] = $container = new Container();

            foreach ($this->getExtensions() as $extension) {
                $newConfigClasses = new \ArrayObject();

                $extension->process($container, $class, $configClass, $newConfigClasses);

                if ($newConfigClasses) {
                    foreach ($newConfigClasses as &$newConfigClass) {
                        if (is_array($newConfigClass)) {
                            $newConfigClass = new \ArrayObject($newConfigClass);
                        }
                    }
                    $this->processConfigClasses($containers, $newConfigClasses);
                }
            }
        }
    }

    /**
     * Dump containers.
     *
     * @param array $containers An array of containers.
     *
     * @return void
     */
    public function dumpContainers(array $containers)
    {
        // directories
        foreach ($containers as $container) {
            foreach ($container->getDefinitions() as $name => $definition) {
                $output = $container->getOutputs()->getOutput($name);
                $dir    = $output->getDir();

                if (!file_exists($dir) && false === @mkdir($dir, 0777, true)) {
                    throw new \RuntimeException(sprintf('Unable to create the %s directory (%s).', $name, $dir));
                }

                if (!is_writable($dir)) {
                    throw new \RuntimeException(sprintf('Unable to write in the %s directory (%s).', $name, $dir));
                }
            }
        }

        // output
        foreach ($containers as $container) {
            foreach ($container->getDefinitions() as $name => $definition) {
                $output = $container->getOutputs()->getOutput($name);
                $dir    = $output->getDir();

                $file = $dir.DIRECTORY_SEPARATOR.$definition->getClassName().'.php';

                if (!file_exists($file) || $output->getOverride()) {
                    $dumper  = new Dumper($definition);
                    $content = $dumper->dump();

                    if (false === @file_put_contents($file, $content)) {
                        throw new \RuntimeException(sprintf('Failed to write the file "%s".', $file));
                    }
                    chmod($file, 0644);
                }
            }
        }
    }

    /**
     * Generate and dump the containers.
     *
     * @return void
     */
    public function process()
    {
        $this->dumpContainers($this->generateContainers());
    }
}
