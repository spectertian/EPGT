<?php

// autoloader
require(__DIR__.'/../lib/vendor/symfony/src/Symfony/Component/HttpFoundation/UniversalClassLoader.php');

use Symfony\Component\HttpFoundation\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Mondongo\\Tests' => __DIR__,
    'Mondongo'        => __DIR__.'/../lib',
    'Model'           => __DIR__,
));
$loader->register();

/*
 * Mondator
 */
use \Mondongo\Mondator\Mondator;
use \Mondongo\Mondator\Output\Output;

// namespaced
$configClasses = array(
    'Model\\Document\\Author' => array(
        'fields' => array(
            'name'         => 'string',
            'telephone_id' => 'reference_one',
        ),
        'references' => array(
            'telephone' => array('class' => 'Model\\Document\\AuthorTelephone', 'field' => 'telephone_id', 'type' => 'one'),
        ),
        'relations' => array(
            'articles' => array('class' => 'Model\\Document\\Article', 'field' => 'author_id', 'type' => 'many'),
        ),
    ),
    'Model\\Document\\AuthorTelephone' => array(
        'fields' => array(
            'number' => 'string',
        ),
        'relations' => array(
            'author' => array('class' => 'Model\\Document\\Author', 'field' => 'telephone_id', 'type' => 'one'),
        ),
    ),
    'Model\\Document\\Category' => array(
        'fields' => array(
            'name' => 'string',
        ),
        'relations' => array(
            'articles' => array('class' => 'Model\\Document\\Article', 'field' => 'category_ids', 'type' => 'many'),
        ),
    ),
    'Model\\Document\\Comment' => array(
        'is_embedded' => true,
        'fields' => array(
            'name' => 'string',
            'text' => 'string',
        ),
    ),
    'Model\\Document\\Source' => array(
        'is_embedded' => true,
        'fields' => array(
            'name' => 'string',
            'url'  => 'string',
        ),
    ),
    'Model\\Document\\Article' => array(
        'collection' => 'article',
        'fields' => array(
            'title'        => 'string',
            'slug'         => 'string',
            'content'      => 'string',
            'is_active'    => 'boolean',
            'author_id'    => 'reference_one',
            'category_ids' => 'reference_many',
        ),
        'references' => array(
            'author'     => array('class' => 'Model\\Document\\Author', 'field' => 'author_id', 'type' => 'one'),
            'categories' => array('class' => 'Model\\Document\\Category', 'field' => 'category_ids', 'type' => 'many'),
        ),
        'embeddeds' => array(
            'source'   => array('class' => 'Model\\Document\\Source', 'type' => 'one'),
            'comments' => array('class' => 'Model\\Document\\Comment', 'type' => 'many'),
        ),
        'relations' => array(
            'summary' => array('class' => 'Model\\Document\\Summary', 'field' => 'article_id', 'type' => 'one'),
            'news'    => array('class' => 'Model\\Document\\News', 'field' => 'article_id', 'type' => 'many'),
        ),
        'indexes' => array(
            array(
                'keys'    => array('slug' => 1),
                'options' => array('unique' => true),
            ),
            array(
                'keys' => array('author_id' => 1, 'is_active' => 1),
            ),
        ),
    ),
    'Model\\Document\\News' => array(
        'fields' => array(
            'title'      => 'string',
            'article_id' => 'reference_one',
        ),
        'references' => array(
            'article' => array('class' => 'Model\\Document\\Article', 'field' => 'article_id', 'type' => 'one'),
        ),
    ),
    'Model\\Document\\Summary' => array(
        'fields' => array(
            'article_id' => 'reference_one',
            'text'       => 'string',
        ),
        'references' => array(
            'article' => array('class' => 'Model\\Document\\Article', 'field' => 'article_id', 'type' => 'one'),
        ),
    ),
    'Model\\Document\\User' => array(
        'fields' => array(
            'username'  => 'string',
            'is_active' => array('type' => 'boolean', 'default' => true),
        ),
    ),
    'Model\\Document\\Image' => array(
        'is_file'    => true,
        'collection' => 'image',
        'fields'  => array(
            'name'        => 'string',
            'description' => 'string',
        ),
    ),
    'Model\\Document\\ConnectionGlobal' => array(
        'connection' => 'global',
    ),
    'Model\\Document\\CollectionName' => array(
        'collection' => 'my_name',
    ),
    'Model\\Document\\Events' => array(
        'fields' => array(
            'name' => 'string',
        ),
    ),
    'Model\\Document\\EmbedNot' => array(
        'is_embedded' => true,
        'relations' => array(
            'article' => array('class' => 'Model\\Document\\Article', 'field' => 'embed_not_id', 'type' => 'one'),
        ),
    ),
);

$mondator = new Mondator();
$mondator->setConfigClasses($configClasses);
$mondator->setExtensions(array(
    new Mondongo\Extension\Core(array(
        'default_document_output'      => __DIR__.'/Model/Document',
        'default_repository_output'    => __DIR__.'/Model/Repository',
    )),
    new Mondongo\Extension\DocumentDataCamelCaseMap(),
    new Mondongo\Extension\DocumentFromToArray(),
    new Mondongo\Extension\DocumentArrayAccess(),
    new Mondongo\Extension\DocumentPropertyOverloading(),
    new Mondongo\Extension\DocumentDataMap(),
));
$mondator->process();

// not namespaced
$mondator = new Mondator();
$mondator->setConfigClasses(array(
    'Article' => array(
        'fields' => array(
            'title'   => 'string',
            'content' => 'string',
        ),
    ),
));
$mondator->setExtensions(array(
    new Mondongo\Extension\Core(array(
        'default_document_output'   => __DIR__.'/model',
        'default_repository_output' => __DIR__.'/model',
    )),
    new Mondongo\Extension\DocumentDataCamelCaseMap(),
    new Mondongo\Extension\DocumentFromToArray(),
    new Mondongo\Extension\DocumentArrayAccess(),
    new Mondongo\Extension\DocumentPropertyOverloading(),
    new Mondongo\Extension\DocumentDataMap(),
));
$mondator->process();

foreach (array(__DIR__.'/model/Base', __DIR__.'/model') as $dir) {
    foreach (new DirectoryIterator($dir) as $file) {
        if ($file->isFile()) {
            require_once($file->getPathname());
        }
    }
}
