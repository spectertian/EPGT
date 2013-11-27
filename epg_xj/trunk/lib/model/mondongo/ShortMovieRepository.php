<?php

/**
 * Repository of ShortMovie document.
 */
class ShortMovieRepository extends \BaseShortMovieRepository
{
  /**
   * Find documents.
   *
   * Options:
   *
   *   * query:  the query (array)
   *   * fields: the fields (array)
   *   * sort:   the sort
   *   * limit:  the limit
   *   * skip:   the skip
   *   * one:    if returns one result (incompatible with limit)
   *
   * @param array $options An array of options.
   *
   * @return mixed The document/s found within the parameters.
   */
 /*  public function find(array $options = array())
  {
    // query
    if (!isset($options['query'])) {
      $options['query'] = array();
    }
  
    // fields
    if (!isset($options['fields'])) {
      $options['fields'] = array();
    }
  
    // cursor
    $cursor = $this->getCollection()->find($options['query'], $options['fields']);
  
    // sort
    if (isset($options['sort'])) {
      $cursor->sort($options['sort']);
    }
  
    // one
    if (isset($options['one'])) {
      $cursor->limit(1);
      // limit
    } elseif (isset($options['limit'])) {
      $cursor->limit($options['limit']);
    }
  
    // skip
    if (isset($options['skip'])) {
      $cursor->skip($options['skip']);
    }
  
    // results
    $results = array();
    foreach ($cursor as $data) {
      if (isset($data['model'])) {
        //$document_class = $data['model'];
        $document = $this->factory($data['model']);
      } else {
        //$document_class = $this->documentClass;
        $document = new $this->documentClass;
      }
      $results[] = $document; // = new $document_class();
      if ($this->isFile) {
        $file = $data;
        $data = $file->file;
        $data['file'] = $file;
      }
      $document->setDocumentData($data);
    }
  
    if ($results) {
      // one
      if (isset($options['one'])) {
        return array_shift($results);
      }
  
      return $results;
    }
  
    return null;
  } */
  /**
   * 模糊查询WIKI_NAME
   * @param  string $wiki_title
   * @return Wiki
   * @author huang
   */
  public function likeShortName($name){
    $reg_str = "/^".$name.".*?/im";
    $regex_obj = new MongoRegex($reg_str);
    $rs = $this->find(array(
        'query' => array(
            'name' => $regex_obj,
            'state'=> 1
        ),
        "sort" => array("updated_at",-1)
      )
    );
    return $rs;
  }
}