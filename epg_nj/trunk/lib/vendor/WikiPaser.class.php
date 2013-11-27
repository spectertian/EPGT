<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Text/Wiki.php';

class WikiPasers {
    /**
     * 输出html
     * @param <String> $text
     * @return <String>
     * @author ward
     * @date 2010-11-16
     */
    static  function render($text='') {
        $wiki   = Text_Wiki::singleton('Creole');
        $conf = array(
    	'charset'   => 'UTF-8'
        );
        $wiki->setFormatConf('Xhtml',$conf);
        $wiki->setRenderConf('Xhtml', 'image', 'base', '/images/');
        $wiki->setRenderConf('Xhtml', 'wikilink', 'view_url',
                'http://example.php/view.php?page=%s');
        $wiki->setRenderConf('Xhtml', 'wikilink', 'new_url',
                'http://example.php/new.php?page=%s');
        $wiki->setRenderConf('Xhtml', 'wikilink', 'new_text',
                '创建');
        return $wiki->transform($text);
    }
}
?>
