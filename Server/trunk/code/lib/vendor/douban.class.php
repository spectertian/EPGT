<?php
/**
 * 豆瓣抓取类
 *
 * @author superwen
 */
require_once("lib/vendor/simple_html_dom.php");
 
class Douban
{
    static function getMoiveBySubject($id)
    {
        $url = "https://api.douban.com/v2/movie/subject/".$id;
        $html = Common::get_url_content($url);
        $movie = json_decode($html,true);
        if(!$movie) {
            return null;
        }
        
    }
}