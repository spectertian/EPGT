<?php


class FavoritesTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Favorites');
    }

    public function getFavoriteContents($user_id, $type) {
        $favorites = Doctrine::getTable('Favorites')->createQuery()
                    ->select('content')
                    ->where('user_id = ?', $user_id)
                    ->andWhere('type = ?', $type)
                    ->execute();
        
        $contents = array();
        foreach($favorites as $favorite) {
            $content = $favorite->getContent();
            $contents[] = $content;
            unset($content);
        }
        return $contents;
    }
}