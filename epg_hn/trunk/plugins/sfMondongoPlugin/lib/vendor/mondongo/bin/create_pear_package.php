<?php

/*
 * Based on Twig create_pear_package script:
 * http://github.com/fabpot/Twig/blob/master/bin/create_pear_package.php
 */

if (!isset($argv[1]))
{
  exit('You must provide the version (1.0.0)'."\n");
}

if (!isset($argv[2]))
{
  exit('You must provide the stability (alpha, beta, or stable)'."\n");
}

$context = array(
  'date'          => date('Y-m-d'),
  'version'       => $argv[1],
  'api_version'   => $argv[1],
  'stability'     => $argv[2],
  'api_stability' => $argv[2],
);

$context['files']    = "\n";
$context['filelist'] = "\n";
$path = realpath(dirname(__FILE__).'/../lib');
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
  if (preg_match('/\.php$/', $file))
  {
    $name = 'lib/'.str_replace($path.'/', '', $file);
    $context['files']    .= '    <file baseinstalldir="Mondongo" name="'.$name.'" role="php" />'."\n";
    $context['filelist'] .= '    <install as="'.str_replace('lib/', '', $name).'" name="'.$name.'" />'."\n";
  }
}

$template = file_get_contents(dirname(__FILE__).'/../package.xml.tpl');

foreach ($context as $key => $value)
{
  $template = str_replace(sprintf('##%s##', strtoupper($key)), $value, $template);
}

file_put_contents(dirname(__FILE__).'/../package.xml', $template);
